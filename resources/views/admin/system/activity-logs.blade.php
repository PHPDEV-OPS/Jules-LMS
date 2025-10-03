@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Activity Logs
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Monitor and review system activities and user actions
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <button type="button" 
                    onclick="exportLogs()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="material-icons text-sm mr-2">download</i>
                Export
            </button>
            <button type="button" 
                    onclick="openClearLogsModal()"
                    class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                <i class="material-icons text-sm mr-2">delete_sweep</i>
                Clear Old Logs
            </button>
        </div>
    </div>

    <!-- Statistics Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-blue-600 text-2xl">timeline</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Activities</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['total_logs']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-green-600 text-2xl">today</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['today_logs']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-orange-600 text-2xl">person</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['unique_users_today']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-purple-600 text-2xl">warning</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Critical Events</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($statistics['critical_logs']) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter Activities</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.system.activity-logs') }}" class="space-y-4 md:space-y-0 md:grid md:grid-cols-5 md:gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search" 
                           value="{{ request('search') }}"
                           placeholder="Search activities..."
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="user_type" class="block text-sm font-medium text-gray-700">User Type</label>
                    <select name="user_type" 
                            id="user_type" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Users</option>
                        <option value="user" {{ request('user_type') === 'user' ? 'selected' : '' }}>Admin Users</option>
                        <option value="student" {{ request('user_type') === 'student' ? 'selected' : '' }}>Students</option>
                    </select>
                </div>
                
                <div>
                    <label for="activity_type" class="block text-sm font-medium text-gray-700">Activity Type</label>
                    <select name="activity_type" 
                            id="activity_type" 
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Types</option>
                        <option value="login" {{ request('activity_type') === 'login' ? 'selected' : '' }}>Login</option>
                        <option value="logout" {{ request('activity_type') === 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="create" {{ request('activity_type') === 'create' ? 'selected' : '' }}>Create</option>
                        <option value="update" {{ request('activity_type') === 'update' ? 'selected' : '' }}>Update</option>
                        <option value="delete" {{ request('activity_type') === 'delete' ? 'selected' : '' }}>Delete</option>
                        <option value="view" {{ request('activity_type') === 'view' ? 'selected' : '' }}>View</option>
                        <option value="error" {{ request('activity_type') === 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
                
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                    <input type="date" 
                           name="date_from" 
                           id="date_from" 
                           value="{{ request('date_from') }}"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-end">
                    <div class="w-full">
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                        <input type="date" 
                               name="date_to" 
                               id="date_to" 
                               value="{{ request('date_to') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="md:col-span-5 flex justify-between">
                    <div>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <i class="material-icons text-sm mr-2">search</i>
                            Apply Filters
                        </button>
                        <a href="{{ route('admin.system.activity-logs') }}" 
                           class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Clear Filters
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Activity History</h3>
            <span class="text-sm text-gray-500">{{ $logs->total() }} activities found</span>
        </div>
        
        @if($logs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Activity
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($log->user)
                                                <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="material-icons text-blue-600 text-sm">person</i>
                                                </div>
                                            @elseif($log->student)
                                                <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i class="material-icons text-green-600 text-sm">school</i>
                                                </div>
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                    <i class="material-icons text-gray-600 text-sm">help</i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $log->user ? $log->user->name : ($log->student ? $log->student->name : 'System') }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $log->user ? 'Admin' : ($log->student ? 'Student' : 'System') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($log->activity_type === 'login') bg-green-100 text-green-800
                                        @elseif($log->activity_type === 'logout') bg-gray-100 text-gray-800
                                        @elseif($log->activity_type === 'create') bg-blue-100 text-blue-800
                                        @elseif($log->activity_type === 'update') bg-yellow-100 text-yellow-800
                                        @elseif($log->activity_type === 'delete') bg-red-100 text-red-800
                                        @elseif($log->activity_type === 'error') bg-red-100 text-red-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($log->activity_type) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-900">{{ $log->description }}</p>
                                    @if($log->properties)
                                        <p class="text-xs text-gray-500 mt-1">
                                            @if(is_array($log->properties))
                                                @foreach($log->properties as $key => $value)
                                                    <span class="inline-block mr-2">{{ $key }}: {{ $value }}</span>
                                                @endforeach
                                            @endif
                                        </p>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $log->ip_address }}
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>
                                        {{ $log->created_at->format('M j, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $log->created_at->format('g:i A') }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button type="button" 
                                            onclick="viewLogDetails({{ $log->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->withQueryString()->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">timeline</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Activities Found</h3>
                <p class="text-gray-500">No activities match your current filters.</p>
            </div>
        @endif
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-2xl w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Activity Details</h3>
                <button type="button" onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <div id="logDetailsContent" class="space-y-4">
                <!-- Content loaded via AJAX -->
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="button" 
                        onclick="closeLogDetailsModal()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Logs Modal -->
<div id="clearLogsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black opacity-50"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Clear Old Logs</h3>
                <button type="button" onclick="closeClearLogsModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="material-icons">close</i>
                </button>
            </div>
            
            <form action="{{ route('admin.system.activity-logs.clear-old') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                        <div class="flex">
                            <i class="material-icons text-yellow-600 text-sm mr-2">warning</i>
                            <div class="text-sm text-yellow-700">
                                <p>This will permanently delete old activity logs. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="days_to_keep" class="block text-sm font-medium text-gray-700">Keep logs from last</label>
                        <select name="days_to_keep" 
                                id="days_to_keep" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="7">7 days</option>
                            <option value="30" selected>30 days</option>
                            <option value="90">90 days</option>
                            <option value="180">180 days</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeClearLogsModal()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <i class="material-icons text-sm mr-2">delete_sweep</i>
                        Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewLogDetails(logId) {
    fetch(`{{ route('admin.system.activity-logs') }}/${logId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('logDetailsContent').innerHTML = data.html;
            document.getElementById('logDetailsModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load log details');
        });
}

function closeLogDetailsModal() {
    document.getElementById('logDetailsModal').classList.add('hidden');
}

function openClearLogsModal() {
    document.getElementById('clearLogsModal').classList.remove('hidden');
}

function closeClearLogsModal() {
    document.getElementById('clearLogsModal').classList.add('hidden');
}

function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    window.location.href = '{{ route("admin.system.activity-logs") }}?' + params.toString();
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
        closeLogDetailsModal();
        closeClearLogsModal();
    }
});
</script>
@endsection