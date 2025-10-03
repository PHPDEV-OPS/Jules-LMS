@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Backup & Restore
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Create, manage, and restore system backups
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" 
                    onclick="openCreateBackupModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="material-icons text-sm mr-2">backup</i>
                Create Backup
            </button>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-blue-600 text-2xl">storage</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Backups</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ count($backups) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-green-600 text-2xl">hard_drive</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Disk Space Used</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Http\Controllers\Admin\BackupController::formatBytes($diskSpace['used_by_backups'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-orange-600 text-2xl">schedule</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Auto Backup</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $backupSettings['auto_backup_enabled'] ? 'Enabled' : 'Disabled' }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-purple-600 text-2xl">folder</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available Space</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ \App\Http\Controllers\Admin\BackupController::formatBytes($diskSpace['free'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backup Schedule Settings -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Backup Schedule</h3>
        </div>
        <div class="px-6 py-4">
            <form action="{{ route('admin.system.backup.schedule') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="auto_backup_enabled" 
                               id="auto_backup_enabled"
                               {{ $backupSettings['auto_backup_enabled'] ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="auto_backup_enabled" class="ml-2 block text-sm text-gray-900">
                            Enable Automatic Backups
                        </label>
                    </div>
                    
                    <div>
                        <label for="backup_frequency" class="block text-sm font-medium text-gray-700">Frequency</label>
                        <select name="backup_frequency" 
                                id="backup_frequency" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="hourly" {{ $backupSettings['backup_frequency'] === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ $backupSettings['backup_frequency'] === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $backupSettings['backup_frequency'] === 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $backupSettings['backup_frequency'] === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="max_backups" class="block text-sm font-medium text-gray-700">Max Backups to Keep</label>
                        <input type="number" 
                               name="max_backups" 
                               id="max_backups" 
                               value="{{ $backupSettings['max_backups'] }}"
                               min="1"
                               max="100"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="material-icons text-sm mr-2">save</i>
                        Save Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Backup List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Available Backups</h3>
        </div>
        
        @if(count($backups) > 0)
            <div class="divide-y divide-gray-200">
                @foreach($backups as $backup)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="material-icons text-gray-400">
                                            @if($backup['type'] === 'full')
                                                backup
                                            @elseif($backup['type'] === 'database_only')
                                                storage
                                            @else
                                                folder
                                            @endif
                                        </i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $backup['name'] }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $backup['description'] }}
                                        </p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $backup['type'] === 'full' ? 'bg-blue-100 text-blue-800' : ($backup['type'] === 'database_only' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst(str_replace('_', ' ', $backup['type'])) }}
                                            </span>
                                            <span>Size: {{ \App\Http\Controllers\Admin\BackupController::formatBytes($backup['size']) }}</span>
                                            <span>Created: {{ $backup['created_at']->format('M j, Y g:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <a href="{{ route('admin.system.backup.download', $backup['filename']) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="material-icons text-sm mr-1">download</i>
                                    Download
                                </a>
                                
                                <button type="button" 
                                        onclick="openRestoreModal('{{ $backup['filename'] }}', '{{ $backup['name'] }}')"
                                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <i class="material-icons text-sm mr-1">restore</i>
                                    Restore
                                </button>
                                
                                <form action="{{ route('admin.system.backup.delete', $backup['filename']) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this backup? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                        <i class="material-icons text-sm mr-1">delete</i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">backup</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Backups Available</h3>
                <p class="text-gray-500 mb-4">Create your first backup to get started.</p>
                <button type="button" 
                        onclick="openCreateBackupModal()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">backup</i>
                    Create First Backup
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Create Backup Modal -->
<div id="createBackupModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create New Backup</h3>
                <button type="button" onclick="closeCreateBackupModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <form action="{{ route('admin.system.backup.create') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="backup_type" class="block text-sm font-medium text-gray-700">Backup Type</label>
                        <select name="backup_type" 
                                id="backup_type" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="full">Full Backup (Database + Files)</option>
                            <option value="database_only">Database Only</option>
                            <option value="files_only">Files Only</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="3"
                                  placeholder="Brief description of this backup..."
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCreateBackupModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="material-icons text-sm mr-2">backup</i>
                        Create Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Restore Backup Modal -->
<div id="restoreBackupModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Restore Backup</h3>
                <button type="button" onclick="closeRestoreModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <form id="restoreForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <i class="material-icons text-yellow-600 text-sm mr-2">warning</i>
                            <div class="text-sm text-yellow-700">
                                <p><strong>Warning:</strong> Restoring a backup will overwrite your current data. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Backup Name</label>
                        <p id="restoreBackupName" class="mt-1 text-sm text-gray-900"></p>
                    </div>
                    
                    <div>
                        <label for="restore_type" class="block text-sm font-medium text-gray-700">Restore Type</label>
                        <select name="restore_type" 
                                id="restore_type" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="full">Full Restore (Database + Files)</option>
                            <option value="database_only">Database Only</option>
                            <option value="files_only">Files Only</option>
                        </select>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="confirm_restore" 
                               id="confirm_restore"
                               required
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="confirm_restore" class="ml-2 block text-sm text-red-700">
                            I understand this will overwrite current data
                        </label>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeRestoreModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="material-icons text-sm mr-2">restore</i>
                        Restore Backup
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateBackupModal() {
    document.getElementById('createBackupModal').classList.remove('hidden');
}

function closeCreateBackupModal() {
    document.getElementById('createBackupModal').classList.add('hidden');
}

function openRestoreModal(filename, name) {
    document.getElementById('restoreBackupName').textContent = name;
    document.getElementById('restoreForm').action = '{{ route("admin.system.backup.restore", "PLACEHOLDER") }}'.replace('PLACEHOLDER', filename);
    document.getElementById('restoreBackupModal').classList.remove('hidden');
}

function closeRestoreModal() {
    document.getElementById('restoreBackupModal').classList.add('hidden');
    document.getElementById('confirm_restore').checked = false;
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeCreateBackupModal();
        closeRestoreModal();
    }
});
</script>
@endsection