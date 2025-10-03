@extends('layouts.admin')

@section('title', 'Notification Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $notification->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ ucfirst($notification->type) }} Notification • 
                {{ ucfirst($notification->priority) }} Priority • 
                {{ ucfirst($notification->status) }}
            </p>
        </div>
        <div class="flex space-x-3">
            @if($notification->status === 'draft' || $notification->status === 'scheduled')
            <a href="{{ route('admin.notifications.edit', $notification) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Notification
            </a>
            @endif
            <a href="{{ route('admin.notifications.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="rounded-md p-4 
        {{ $notification->status === 'sent' ? 'bg-green-50 border-green-200' : 
           ($notification->status === 'scheduled' ? 'bg-blue-50 border-blue-200' : 
           ($notification->status === 'cancelled' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200')) }}">
        <div class="flex">
            <div class="flex-shrink-0">
                <span class="material-icons text-{{ 
                    $notification->status === 'sent' ? 'green' : 
                    ($notification->status === 'scheduled' ? 'blue' : 
                    ($notification->status === 'cancelled' ? 'red' : 'yellow')) }}-400">
                    {{ $notification->status === 'sent' ? 'check_circle' : 
                       ($notification->status === 'scheduled' ? 'schedule' : 
                       ($notification->status === 'cancelled' ? 'cancel' : 'draft')) }}
                </span>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-{{ 
                    $notification->status === 'sent' ? 'green' : 
                    ($notification->status === 'scheduled' ? 'blue' : 
                    ($notification->status === 'cancelled' ? 'red' : 'yellow')) }}-800">
                    {{ ucfirst($notification->status) }} Notification
                </h3>
                <div class="mt-1 text-sm text-{{ 
                    $notification->status === 'sent' ? 'green' : 
                    ($notification->status === 'scheduled' ? 'blue' : 
                    ($notification->status === 'cancelled' ? 'red' : 'yellow')) }}-700">
                    @if($notification->status === 'sent')
                        This notification has been sent to {{ $notification->recipients_count ?? 'all' }} recipient(s).
                    @elseif($notification->status === 'scheduled')
                        This notification is scheduled to be sent {{ $notification->scheduled_at ? $notification->scheduled_at->format('M d, Y \\a\\t g:i A') : 'soon' }}.
                    @elseif($notification->status === 'cancelled')
                        This notification has been cancelled and will not be sent.
                    @else
                        This notification is in draft mode and has not been sent yet.
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Message Content -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Message Content</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-900 whitespace-pre-line">{{ $notification->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Delivery Information</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Target Recipients</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($notification->user_id)
                                    {{ $notification->user->name ?? 'Unknown User' }}
                                @elseif($notification->course_id)
                                    {{ $notification->course->title ?? 'Unknown Course' }} participants
                                @elseif($notification->role)
                                    All {{ ucfirst($notification->role) }}s
                                @else
                                    All Users
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Recipients Count</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $notification->recipients_count ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $notification->created_at->format('M d, Y \\a\\t g:i A') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sent At</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $notification->sent_at ? $notification->sent_at->format('M d, Y \\a\\t g:i A') : 'Not sent yet' }}
                            </dd>
                        </div>
                        @if($notification->scheduled_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Scheduled For</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $notification->scheduled_at->format('M d, Y \\a\\t g:i A') }}</dd>
                        </div>
                        @endif
                        @if($notification->expires_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Expires At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $notification->expires_at->format('M d, Y \\a\\t g:i A') }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Delivery Statistics -->
            @if($notification->status === 'sent')
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Delivery Statistics</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $notification->recipients_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Total Recipients</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $notification->read_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Read</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $notification->acknowledged_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Acknowledged</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $notification->clicked_count ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Clicked</div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Recent Activity</h3>
                    <div class="space-y-2">
                        <!-- Placeholder for recent activity - would come from related model -->
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-green-400 rounded-full mr-3"></span>
                                <span class="text-sm font-medium text-gray-900">John Doe read the notification</span>
                            </div>
                            <span class="text-sm text-gray-500">2 minutes ago</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-blue-400 rounded-full mr-3"></span>
                                <span class="text-sm font-medium text-gray-900">Jane Smith acknowledged the notification</span>
                            </div>
                            <span class="text-sm text-gray-500">5 minutes ago</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 bg-gray-50 rounded-md">
                            <div class="flex items-center">
                                <span class="w-2 h-2 bg-purple-400 rounded-full mr-3"></span>
                                <span class="text-sm font-medium text-gray-900">Mike Johnson clicked the action link</span>
                            </div>
                            <span class="text-sm text-gray-500">10 minutes ago</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Details Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Details</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($notification->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Priority</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $notification->priority === 'critical' ? 'bg-red-100 text-red-800' :
                                   ($notification->priority === 'high' ? 'bg-orange-100 text-orange-800' :
                                   ($notification->priority === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($notification->priority) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email Notification</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $notification->send_email ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Persistent</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $notification->is_persistent ? 'Yes' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Requires Acknowledgment</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $notification->requires_acknowledgment ? 'Yes' : 'No' }}</dd>
                    </div>
                    @if($notification->action_url)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Action URL</dt>
                        <dd class="mt-1 text-sm">
                            <a href="{{ $notification->action_url }}" target="_blank" 
                               class="text-red-600 hover:text-red-500 break-all">
                                {{ $notification->action_url }}
                            </a>
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    @if($notification->status === 'draft' || $notification->status === 'scheduled')
                    <a href="{{ route('admin.notifications.edit', $notification) }}" 
                       class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">edit</span>
                        Edit Notification
                    </a>
                    @endif

                    @if($notification->status === 'draft')
                    <button onclick="sendNow()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">send</span>
                        Send Now
                    </button>
                    @endif

                    @if($notification->status === 'scheduled')
                    <button onclick="cancelScheduled()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">cancel</span>
                        Cancel Scheduled
                    </button>
                    @endif

                    <button onclick="duplicateNotification()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">content_copy</span>
                        Duplicate
                    </button>

                    <button onclick="exportStats()" 
                            class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">download</span>
                        Export Statistics
                    </button>

                    <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this notification?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <span class="material-icons text-sm mr-3">delete</span>
                            Delete Notification
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendNow() {
    if (confirm('Send this notification immediately?')) {
        // Make AJAX call to send notification
        alert('Notification would be sent immediately.');
    }
}

function cancelScheduled() {
    if (confirm('Cancel the scheduled notification?')) {
        // Make AJAX call to cancel scheduled notification
        alert('Scheduled notification would be cancelled.');
    }
}

function duplicateNotification() {
    if (confirm('Create a copy of this notification?')) {
        // Redirect to create page with notification data
        window.location.href = '{{ route("admin.notifications.create") }}?duplicate={{ $notification->id }}';
    }
}

function exportStats() {
    // Trigger export of notification statistics
    alert('Statistics would be exported to Excel/CSV.');
}
</script>
@endsection