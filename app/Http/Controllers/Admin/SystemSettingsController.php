<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            // General Settings
            'app_name' => config('app.name', 'LMS'),
            'app_url' => config('app.url'),
            'app_timezone' => config('app.timezone'),
            'app_locale' => config('app.locale'),
            
            // Email Settings
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            
            // Database Settings
            'database_connection' => config('database.default'),
            'database_name' => config('database.connections.'.config('database.default').'.database'),
            
            // Cache Settings
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            
            // Security Settings
            'session_lifetime' => config('session.lifetime'),
            'csrf_protection' => true,
            'force_https' => config('app.env') === 'production',
            
            // Storage Settings
            'filesystem_driver' => config('filesystems.default'),
            'storage_path' => storage_path(),
            'public_path' => public_path(),
        ];

        // System Information
        $systemInfo = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'disk_free_space' => $this->formatBytes($this->getSafeDiskSpace('/', 'free')),
            'disk_total_space' => $this->formatBytes($this->getSafeDiskSpace('/', 'total')),
        ];

        return view('admin.system.settings', compact('settings', 'systemInfo'));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_timezone' => 'required|string',
            'app_locale' => 'required|string',
        ]);

        // Update .env file
        $this->updateEnvFile([
            'APP_NAME' => '"' . $request->app_name . '"',
            'APP_TIMEZONE' => $request->app_timezone,
            'APP_LOCALE' => $request->app_locale,
        ]);

        // Clear config cache
        Artisan::call('config:clear');
        
        return back()->with('success', 'General settings updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        $updates = [
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
        ];

        if ($request->mail_driver === 'smtp') {
            $updates['MAIL_HOST'] = $request->mail_host;
            $updates['MAIL_PORT'] = $request->mail_port;
            $updates['MAIL_USERNAME'] = $request->mail_username;
            if ($request->filled('mail_password')) {
                $updates['MAIL_PASSWORD'] = $request->mail_password;
            }
            $updates['MAIL_ENCRYPTION'] = $request->mail_encryption;
        }

        $this->updateEnvFile($updates);
        Artisan::call('config:clear');
        
        return back()->with('success', 'Email settings updated successfully.');
    }

    public function updateSecurity(Request $request)
    {
        $request->validate([
            'session_lifetime' => 'required|integer|min:1|max:525600',
            'force_https' => 'boolean',
            'maintenance_mode' => 'boolean',
        ]);

        $updates = [
            'SESSION_LIFETIME' => $request->session_lifetime,
        ];

        if ($request->boolean('force_https')) {
            $updates['APP_URL'] = str_replace('http://', 'https://', config('app.url'));
        }

        $this->updateEnvFile($updates);
        
        // Handle maintenance mode
        if ($request->boolean('maintenance_mode')) {
            Artisan::call('down');
        } else {
            Artisan::call('up');
        }

        Artisan::call('config:clear');
        
        return back()->with('success', 'Security settings updated successfully.');
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return back()->with('success', 'All caches cleared successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear caches: ' . $e->getMessage());
        }
    }

    public function optimizeApplication()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return back()->with('success', 'Application optimized successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to optimize application: ' . $e->getMessage());
        }
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            \Mail::raw('This is a test email from your LMS system.', function ($message) use ($request) {
                $message->to($request->test_email)
                        ->subject('LMS Test Email');
            });
            
            return back()->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    private function updateEnvFile(array $updates)
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($updates as $key => $value) {
            if (strpos($envContent, $key . '=') !== false) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envFile, $envContent);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function getSafeDiskSpace($path, $type = 'free')
    {
        try {
            // For Windows, we need to get the drive root
            if (PHP_OS_FAMILY === 'Windows') {
                // Use the current drive (usually C:)
                $drive = 'C:';
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
}