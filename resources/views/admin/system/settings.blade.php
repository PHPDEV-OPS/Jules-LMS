@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                System Settings
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Configure your LMS application settings and preferences
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <form action="{{ route('admin.system.settings.clear-cache') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">delete_sweep</i>
                    Clear Cache
                </button>
            </form>
            <form action="{{ route('admin.system.settings.optimize') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">speed</i>
                    Optimize
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Settings Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- General Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">General Settings</h3>
                    <form action="{{ route('admin.system.settings.general') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="app_name" class="block text-sm font-medium text-gray-700">Application Name</label>
                                <input type="text" 
                                       name="app_name" 
                                       id="app_name" 
                                       value="{{ $settings['app_name'] }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="app_timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                                <select name="app_timezone" 
                                        id="app_timezone" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="UTC" {{ $settings['app_timezone'] === 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ $settings['app_timezone'] === 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                    <option value="America/Chicago" {{ $settings['app_timezone'] === 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                    <option value="America/Denver" {{ $settings['app_timezone'] === 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                    <option value="America/Los_Angeles" {{ $settings['app_timezone'] === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="app_locale" class="block text-sm font-medium text-gray-700">Language</label>
                                <select name="app_locale" 
                                        id="app_locale" 
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="en" {{ $settings['app_locale'] === 'en' ? 'selected' : '' }}>English</option>
                                    <option value="es" {{ $settings['app_locale'] === 'es' ? 'selected' : '' }}>Spanish</option>
                                    <option value="fr" {{ $settings['app_locale'] === 'fr' ? 'selected' : '' }}>French</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="material-icons text-sm mr-2">save</i>
                                Save General Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Email Settings</h3>
                        <button type="button" 
                                onclick="toggleTestEmail()"
                                class="text-sm text-blue-600 hover:text-blue-900">
                            Test Email
                        </button>
                    </div>
                    
                    <form action="{{ route('admin.system.settings.email') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="mail_driver" class="block text-sm font-medium text-gray-700">Mail Driver</label>
                                <select name="mail_driver" 
                                        id="mail_driver" 
                                        onchange="toggleEmailFields()"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <option value="smtp" {{ $settings['mail_driver'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ $settings['mail_driver'] === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ $settings['mail_driver'] === 'log' ? 'selected' : '' }}>Log (Testing)</option>
                                </select>
                            </div>
                            
                            <div id="smtp-fields" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mail_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                                        <input type="text" 
                                               name="mail_host" 
                                               id="mail_host" 
                                               value="{{ $settings['mail_host'] }}"
                                               placeholder="smtp.gmail.com"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="mail_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                                        <input type="number" 
                                               name="mail_port" 
                                               id="mail_port" 
                                               value="{{ $settings['mail_port'] }}"
                                               placeholder="587"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="mail_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                                        <input type="text" 
                                               name="mail_username" 
                                               id="mail_username" 
                                               value="{{ $settings['mail_username'] }}"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label for="mail_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                                        <input type="password" 
                                               name="mail_password" 
                                               id="mail_password" 
                                               placeholder="Leave blank to keep current"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700">Encryption</label>
                                    <select name="mail_encryption" 
                                            id="mail_encryption" 
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="tls" {{ $settings['mail_encryption'] === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ $settings['mail_encryption'] === 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ $settings['mail_encryption'] === '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700">From Email</label>
                                    <input type="email" 
                                           name="mail_from_address" 
                                           id="mail_from_address" 
                                           value="{{ $settings['mail_from_address'] }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                                    <input type="text" 
                                           name="mail_from_name" 
                                           id="mail_from_name" 
                                           value="{{ $settings['mail_from_name'] }}"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="material-icons text-sm mr-2">save</i>
                                Save Email Settings
                            </button>
                        </div>
                    </form>
                    
                    <!-- Test Email Form -->
                    <div id="test-email-form" class="hidden mt-6 pt-6 border-t border-gray-200">
                        <form action="{{ route('admin.system.settings.test-email') }}" method="POST">
                            @csrf
                            <div class="flex items-end space-x-3">
                                <div class="flex-1">
                                    <label for="test_email" class="block text-sm font-medium text-gray-700">Test Email Address</label>
                                    <input type="email" 
                                           name="test_email" 
                                           id="test_email" 
                                           placeholder="test@example.com"
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <i class="material-icons text-sm mr-2">send</i>
                                    Send Test
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Security Settings</h3>
                    <form action="{{ route('admin.system.settings.security') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="session_lifetime" class="block text-sm font-medium text-gray-700">Session Lifetime (minutes)</label>
                                <input type="number" 
                                       name="session_lifetime" 
                                       id="session_lifetime" 
                                       value="{{ $settings['session_lifetime'] }}"
                                       min="1"
                                       max="525600"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">How long users stay logged in (1-525600 minutes)</p>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="force_https" 
                                       id="force_https" 
                                       {{ $settings['force_https'] ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="force_https" class="ml-2 block text-sm text-gray-900">
                                    Force HTTPS (Recommended for production)
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="maintenance_mode" 
                                       id="maintenance_mode" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">
                                    Enable Maintenance Mode
                                </label>
                                <p class="ml-2 text-sm text-gray-500">(Will put site offline for maintenance)</p>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                <i class="material-icons text-sm mr-2">save</i>
                                Save Security Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="space-y-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">System Information</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">PHP Version:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $systemInfo['php_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Laravel Version:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $systemInfo['laravel_version'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Server:</span>
                            <span class="text-sm font-medium text-gray-900">{{ Str::limit($systemInfo['server_software'], 20) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Memory Limit:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $systemInfo['memory_limit'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Execution Time:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $systemInfo['max_execution_time'] }}s</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Disk Space:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $systemInfo['disk_free_space'] }} / {{ $systemInfo['disk_total_space'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.system.backup') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="material-icons text-sm mr-2">backup</i>
                            Backup & Restore
                        </a>
                        <a href="{{ route('admin.system.activity-logs') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="material-icons text-sm mr-2">history</i>
                            Activity Logs
                        </a>
                        <a href="{{ route('admin.system.help') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="material-icons text-sm mr-2">help</i>
                            Help & Support
                        </a>
                        <a href="{{ route('admin.system.system-info') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="material-icons text-sm mr-2">computer</i>
                            System Info
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEmailFields() {
    const driver = document.getElementById('mail_driver').value;
    const smtpFields = document.getElementById('smtp-fields');
    
    if (driver === 'smtp') {
        smtpFields.style.display = 'block';
    } else {
        smtpFields.style.display = 'none';
    }
}

function toggleTestEmail() {
    const form = document.getElementById('test-email-form');
    form.classList.toggle('hidden');
}

// Initialize email fields visibility
document.addEventListener('DOMContentLoaded', function() {
    toggleEmailFields();
});
</script>
@endsection