<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackupsList();
        $backupSettings = [
            'auto_backup_enabled' => config('backup.auto_backup_enabled', false),
            'backup_frequency' => config('backup.frequency', 'daily'),
            'max_backups' => config('backup.max_backups', 10),
            'backup_location' => config('backup.location', 'local'),
        ];

        $diskSpace = [
            'total' => $this->getSafeDiskSpace(storage_path('app/backups'), 'total'),
            'free' => $this->getSafeDiskSpace(storage_path('app/backups'), 'free'),
            'used_by_backups' => $this->getBackupsSize(),
        ];

        return view('admin.system.backup', compact('backups', 'backupSettings', 'diskSpace'));
    }

    public function createBackup(Request $request)
    {
        $request->validate([
            'backup_type' => 'required|in:full,database_only,files_only',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            $backupName = 'backup_' . date('Y-m-d_H-i-s');
            $backupPath = storage_path('app/backups/' . $backupName);
            
            if (!File::exists(storage_path('app/backups'))) {
                File::makeDirectory(storage_path('app/backups'), 0755, true);
            }

            $backupInfo = [
                'name' => $backupName,
                'type' => $request->backup_type,
                'description' => $request->description ?? 'Manual backup',
                'created_at' => now()->toISOString(),
                'size' => 0,
                'files' => [],
            ];

            // Create database backup
            if (in_array($request->backup_type, ['full', 'database_only'])) {
                $dbBackupFile = $this->createDatabaseBackup($backupPath);
                $backupInfo['files'][] = $dbBackupFile;
            }

            // Create files backup
            if (in_array($request->backup_type, ['full', 'files_only'])) {
                $filesBackupFile = $this->createFilesBackup($backupPath);
                $backupInfo['files'][] = $filesBackupFile;
            }

            // Create ZIP archive
            $zipFile = $backupPath . '.zip';
            $this->createZipArchive($backupPath, $zipFile);
            
            // Calculate final size
            $backupInfo['size'] = File::size($zipFile);
            
            // Save backup metadata
            file_put_contents($zipFile . '.info', json_encode($backupInfo, JSON_PRETTY_PRINT));
            
            // Clean up temporary files
            File::deleteDirectory($backupPath);
            
            // Clean old backups if needed
            $this->cleanOldBackups();

            return back()->with('success', 'Backup created successfully: ' . $backupName . '.zip');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    public function downloadBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($backupPath)) {
            return back()->with('error', 'Backup file not found.');
        }

        return response()->download($backupPath);
    }

    public function deleteBackup($filename)
    {
        $backupPath = storage_path('app/backups/' . $filename);
        $infoPath = $backupPath . '.info';
        
        try {
            if (File::exists($backupPath)) {
                File::delete($backupPath);
            }
            if (File::exists($infoPath)) {
                File::delete($infoPath);
            }
            
            return back()->with('success', 'Backup deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    public function restoreBackup(Request $request, $filename)
    {
        $request->validate([
            'restore_type' => 'required|in:full,database_only,files_only',
            'confirm_restore' => 'required|accepted',
        ]);

        $backupPath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($backupPath)) {
            return back()->with('error', 'Backup file not found.');
        }

        try {
            // Extract backup
            $extractPath = storage_path('app/temp/restore_' . time());
            $this->extractZipArchive($backupPath, $extractPath);

            // Restore database
            if (in_array($request->restore_type, ['full', 'database_only'])) {
                $this->restoreDatabase($extractPath);
            }

            // Restore files
            if (in_array($request->restore_type, ['full', 'files_only'])) {
                $this->restoreFiles($extractPath);
            }

            // Clean up
            File::deleteDirectory($extractPath);

            return back()->with('success', 'System restored successfully from backup.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore backup: ' . $e->getMessage());
        }
    }

    public function scheduleBackup(Request $request)
    {
        $request->validate([
            'auto_backup_enabled' => 'boolean',
            'backup_frequency' => 'required|in:hourly,daily,weekly,monthly',
            'max_backups' => 'required|integer|min:1|max:100',
        ]);

        // Update backup configuration
        $this->updateBackupConfig([
            'auto_backup_enabled' => $request->boolean('auto_backup_enabled'),
            'frequency' => $request->backup_frequency,
            'max_backups' => $request->max_backups,
        ]);

        return back()->with('success', 'Backup schedule updated successfully.');
    }

    private function getBackupsList()
    {
        $backupsPath = storage_path('app/backups');
        
        if (!File::exists($backupsPath)) {
            return [];
        }

        $files = File::files($backupsPath);
        $backups = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $infoFile = $file . '.info';
                $info = File::exists($infoFile) 
                    ? json_decode(File::get($infoFile), true) 
                    : [];

                $backups[] = [
                    'filename' => basename($file),
                    'name' => $info['name'] ?? pathinfo($file, PATHINFO_FILENAME),
                    'type' => $info['type'] ?? 'unknown',
                    'description' => $info['description'] ?? 'No description',
                    'size' => File::size($file),
                    'created_at' => isset($info['created_at']) 
                        ? Carbon::parse($info['created_at']) 
                        : Carbon::createFromTimestamp(File::lastModified($file)),
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function ($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return $backups;
    }

    private function createDatabaseBackup($backupPath)
    {
        $dbFile = $backupPath . '/database.sql';
        File::makeDirectory($backupPath, 0755, true);

        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");

        if ($connection === 'sqlite') {
            File::copy($database, $backupPath . '/database.sqlite');
            return 'database.sqlite';
        } else {
            // For MySQL/PostgreSQL, use mysqldump or pg_dump
            $command = $this->getDatabaseDumpCommand($connection, $database, $dbFile);
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('Database backup failed');
            }
            
            return 'database.sql';
        }
    }

    private function createFilesBackup($backupPath)
    {
        File::makeDirectory($backupPath . '/files', 0755, true);
        
        // Copy important directories
        $directories = [
            'storage/app' => 'files/storage_app',
            'public/uploads' => 'files/uploads',
            'public/assets' => 'files/assets',
        ];

        foreach ($directories as $source => $destination) {
            $sourcePath = base_path($source);
            $destPath = $backupPath . '/' . $destination;
            
            if (File::exists($sourcePath)) {
                File::copyDirectory($sourcePath, $destPath);
            }
        }

        return 'files/';
    }

    private function createZipArchive($sourcePath, $zipFile)
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipFile, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create zip file');
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sourcePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
    }

    private function extractZipArchive($zipFile, $extractPath)
    {
        $zip = new ZipArchive();
        
        if ($zip->open($zipFile) !== TRUE) {
            throw new \Exception('Cannot open zip file');
        }

        File::makeDirectory($extractPath, 0755, true);
        $zip->extractTo($extractPath);
        $zip->close();
    }

    private function restoreDatabase($extractPath)
    {
        $connection = config('database.default');
        
        if ($connection === 'sqlite') {
            $dbFile = $extractPath . '/database.sqlite';
            if (File::exists($dbFile)) {
                $database = config("database.connections.{$connection}.database");
                File::copy($dbFile, $database);
            }
        } else {
            $sqlFile = $extractPath . '/database.sql';
            if (File::exists($sqlFile)) {
                $command = $this->getDatabaseRestoreCommand($connection, $sqlFile);
                exec($command, $output, $returnCode);
                
                if ($returnCode !== 0) {
                    throw new \Exception('Database restore failed');
                }
            }
        }
    }

    private function restoreFiles($extractPath)
    {
        $filesPath = $extractPath . '/files';
        
        if (File::exists($filesPath)) {
            // Restore storage/app
            if (File::exists($filesPath . '/storage_app')) {
                File::copyDirectory($filesPath . '/storage_app', storage_path('app'));
            }
            
            // Restore public/uploads
            if (File::exists($filesPath . '/uploads')) {
                File::copyDirectory($filesPath . '/uploads', public_path('uploads'));
            }
            
            // Restore public/assets
            if (File::exists($filesPath . '/assets')) {
                File::copyDirectory($filesPath . '/assets', public_path('assets'));
            }
        }
    }

    private function getDatabaseDumpCommand($connection, $database, $outputFile)
    {
        switch ($connection) {
            case 'mysql':
                return sprintf(
                    'mysqldump -h %s -P %s -u %s -p%s %s > %s',
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.port'),
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    $database,
                    $outputFile
                );
            
            case 'pgsql':
                return sprintf(
                    'pg_dump -h %s -p %s -U %s -d %s -f %s',
                    config('database.connections.pgsql.host'),
                    config('database.connections.pgsql.port'),
                    config('database.connections.pgsql.username'),
                    $database,
                    $outputFile
                );
            
            default:
                throw new \Exception('Unsupported database connection: ' . $connection);
        }
    }

    private function getDatabaseRestoreCommand($connection, $sqlFile)
    {
        switch ($connection) {
            case 'mysql':
                return sprintf(
                    'mysql -h %s -P %s -u %s -p%s %s < %s',
                    config('database.connections.mysql.host'),
                    config('database.connections.mysql.port'),
                    config('database.connections.mysql.username'),
                    config('database.connections.mysql.password'),
                    config('database.connections.mysql.database'),
                    $sqlFile
                );
            
            case 'pgsql':
                return sprintf(
                    'psql -h %s -p %s -U %s -d %s -f %s',
                    config('database.connections.pgsql.host'),
                    config('database.connections.pgsql.port'),
                    config('database.connections.pgsql.username'),
                    config('database.connections.pgsql.database'),
                    $sqlFile
                );
            
            default:
                throw new \Exception('Unsupported database connection: ' . $connection);
        }
    }

    private function getBackupsSize()
    {
        $backupsPath = storage_path('app/backups');
        $totalSize = 0;
        
        if (File::exists($backupsPath)) {
            $files = File::allFiles($backupsPath);
            foreach ($files as $file) {
                $totalSize += $file->getSize();
            }
        }
        
        return $totalSize;
    }

    private function cleanOldBackups()
    {
        $maxBackups = config('backup.max_backups', 10);
        $backups = $this->getBackupsList();
        
        if (count($backups) > $maxBackups) {
            $backupsToDelete = array_slice($backups, $maxBackups);
            
            foreach ($backupsToDelete as $backup) {
                $this->deleteBackup($backup['filename']);
            }
        }
    }

    private function updateBackupConfig(array $config)
    {
        $configFile = config_path('backup.php');
        
        if (!File::exists($configFile)) {
            File::put($configFile, "<?php\n\nreturn [];\n");
        }
        
        // Update configuration (simplified approach)
        // In production, you might want to use a more sophisticated config management
        $existingConfig = include $configFile;
        $newConfig = array_merge($existingConfig, $config);
        
        $configContent = "<?php\n\nreturn " . var_export($newConfig, true) . ";\n";
        File::put($configFile, $configContent);
    }

    private function getSafeDiskSpace($path, $type = 'free')
    {
        try {
            // Ensure the directory exists
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // For Windows, we need to get the drive root
            if (PHP_OS_FAMILY === 'Windows') {
                // Get the drive letter (e.g., 'C:')
                $drive = substr($path, 0, 2);
                $targetPath = $drive . '\\';
            } else {
                $targetPath = $path;
            }

            if ($type === 'total') {
                return disk_total_space($targetPath);
            } else {
                return disk_free_space($targetPath);
            }
        } catch (\Exception $e) {
            // Return 0 if we can't determine disk space
            return 0;
        }
    }

    /**
     * Format bytes to human readable format
     */
    public static function formatBytes($size, $precision = 2)
    {
        if ($size == 0) {
            return '0 B';
        }

        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
}