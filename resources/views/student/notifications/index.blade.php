@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
            <p class="mt-1 text-sm text-gray-500">Keep track of your learning activities and updates</p>
        </div>
        @if($unreadCount > 0)
        <div class="flex space-x-3">
            <button onclick="markAllAsRead()" 
                    class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md shadow-sm text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                <span class="material-icons mr-2 text-sm">mark_email_read</span>
                Mark All Read
            </button>
        </div>
        @endif
    </div>

    <!-- Notification Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">notifications</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Notifications</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $notifications->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">mark_email_unread</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Unread</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $unreadCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">check_circle</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Read</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $notifications->total() - $unreadCount }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Recent Notifications</h2>
                @if($unreadCount > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $unreadCount }} unread
                </span>
                @endif
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
            <div class="p-6 {{ !$notification->is_read ? 'bg-blue-50' : 'bg-white' }} hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <!-- Notification Icon -->
                        <div class="flex-shrink-0">
                            @if(!$notification->is_read)
                                <div class="w-3 h-3 bg-blue-600 rounded-full mt-2"></div>
                            @else
                                <div class="w-10 h-10 bg-{{ $notification->type === 'success' ? 'green' : ($notification->type === 'warning' ? 'yellow' : ($notification->type === 'error' ? 'red' : 'blue')) }}-100 rounded-full flex items-center justify-center">
                                    <span class="material-icons text-{{ $notification->type === 'success' ? 'green' : ($notification->type === 'warning' ? 'yellow' : ($notification->type === 'error' ? 'red' : 'blue')) }}-600 text-sm">
                                        @switch($notification->type)
                                            @case('success')
                                                check_circle
                                                @break
                                            @case('warning')
                                                warning
                                                @break
                                            @case('error')
                                                error
                                                @break
                                            @case('assessment')
                                                assignment
                                                @break
                                            @case('course')
                                                school
                                                @break
                                            @case('certificate')
                                                workspace_premium
                                                @break
                                            @default
                                                notifications
                                        @endswitch
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Notification Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-base font-medium text-gray-900 {{ !$notification->is_read ? 'font-bold' : '' }}">
                                    {{ $notification->title }}
                                </h3>
                                
                                <!-- Type Badge -->
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    {{ $notification->type === 'success' ? 'bg-green-100 text-green-800' : 
                                       ($notification->type === 'warning' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($notification->type === 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($notification->type) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-700 mb-3">{{ $notification->message }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-6">
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">schedule</span>
                                    <span>{{ $notification->created_at->format('M j, Y g:i A') }}</span>
                                </div>
                                @if($notification->is_read && $notification->read_at)
                                <div class="flex items-center text-green-600">
                                    <span class="material-icons mr-1 text-sm">check_circle</span>
                                    <span>Read {{ $notification->read_at->diffForHumans() }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Action Data (if any) -->
                            @if($notification->action_url)
                            <div class="mt-3">
                                <a href="{{ $notification->action_url }}" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-500">
                                    <span class="material-icons mr-1 text-sm">open_in_new</span>
                                    {{ $notification->action_text ?? 'View Details' }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex space-x-2 ml-4">
                        @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }})" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-1 text-sm">mark_email_read</span>
                            Mark Read
                        </button>
                        @endif
                        
                        <button onclick="deleteNotification({{ $notification->id }})" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-1 text-sm">delete</span>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                    <span class="material-icons text-4xl text-gray-400">notifications_none</span>
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No Notifications</h3>
                <p class="mt-2 text-sm text-gray-500">You're all caught up! No new notifications to display.</p>
                <div class="mt-6">
                    <a href="{{ route('student.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <span class="material-icons mr-2 text-sm">dashboard</span>
                        Back to Dashboard
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`{{ url('student/notifications') }}/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to show updated status
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    if (confirm('Are you sure you want to mark all notifications as read?')) {
        fetch(`{{ route('student.notifications.mark-all-read') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('Are you sure you want to delete this notification?')) {
        fetch(`{{ url('student/notifications') }}/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}
</script>
@endsection