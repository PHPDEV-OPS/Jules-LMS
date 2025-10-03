@extends('layouts.admin')

@section('title', 'Edit Notification')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Notification</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $notification->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.notifications.show', $notification) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">visibility</span>
                View Details
            </a>
            <a href="{{ route('admin.notifications.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.notifications.update', $notification) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Notification Details</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $notification->title) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="Enter notification title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                    <textarea name="message" id="message" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Enter notification message...">{{ old('message', $notification->message) }}</textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type and Priority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                        <select name="type" id="type" required onchange="updateTypeOptions()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Select notification type</option>
                            <option value="system" {{ (old('type', $notification->type) === 'system') ? 'selected' : '' }}>System Notification</option>
                            <option value="course" {{ (old('type', $notification->type) === 'course') ? 'selected' : '' }}>Course Notification</option>
                            <option value="assessment" {{ (old('type', $notification->type) === 'assessment') ? 'selected' : '' }}>Assessment Notification</option>
                            <option value="grade" {{ (old('type', $notification->type) === 'grade') ? 'selected' : '' }}>Grade Notification</option>
                            <option value="announcement" {{ (old('type', $notification->type) === 'announcement') ? 'selected' : '' }}>Announcement</option>
                            <option value="reminder" {{ (old('type', $notification->type) === 'reminder') ? 'selected' : '' }}>Reminder</option>
                            <option value="alert" {{ (old('type', $notification->type) === 'alert') ? 'selected' : '' }}>Alert</option>
                            <option value="maintenance" {{ (old('type', $notification->type) === 'maintenance') ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="low" {{ (old('priority', $notification->priority) === 'low') ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ (old('priority', $notification->priority) === 'normal') ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ (old('priority', $notification->priority) === 'high') ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ (old('priority', $notification->priority) === 'critical') ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Target Information (Read-only for existing notifications) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Target Recipients</label>
                    <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                        @if($notification->user_id)
                            <div class="text-sm font-medium text-gray-900">Specific User</div>
                            <div class="text-sm text-gray-500">{{ $notification->user->name ?? 'Unknown User' }} ({{ $notification->user->email ?? '' }})</div>
                        @elseif($notification->course_id)
                            <div class="text-sm font-medium text-gray-900">Course Participants</div>
                            <div class="text-sm text-gray-500">{{ $notification->course->title ?? 'Unknown Course' }}</div>
                        @elseif($notification->role)
                            <div class="text-sm font-medium text-gray-900">Users by Role</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($notification->role) }}</div>
                        @else
                            <div class="text-sm font-medium text-gray-900">All Users</div>
                            <div class="text-sm text-gray-500">System-wide notification</div>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target recipients cannot be changed after creation</p>
                </div>

                <!-- Additional Options -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_email" id="send_email" value="1" 
                               {{ old('send_email', $notification->send_email) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="send_email" class="ml-2 block text-sm text-gray-900">Send email notification</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_persistent" id="is_persistent" value="1" 
                               {{ old('is_persistent', $notification->is_persistent) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_persistent" class="ml-2 block text-sm text-gray-900">Persistent notification (remains until dismissed)</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="requires_acknowledgment" id="requires_acknowledgment" value="1" 
                               {{ old('requires_acknowledgment', $notification->requires_acknowledgment) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="requires_acknowledgment" class="ml-2 block text-sm text-gray-900">Requires user acknowledgment</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action URL and Scheduling -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Additional Settings</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Action URL -->
                <div>
                    <label for="action_url" class="block text-sm font-medium text-gray-700 mb-2">Action URL</label>
                    <input type="url" name="action_url" id="action_url" value="{{ old('action_url', $notification->action_url) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="https://example.com/action">
                    @error('action_url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Optional URL users can click to take action</p>
                </div>

                <!-- Scheduling -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">Schedule For</label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" 
                               value="{{ old('scheduled_at', $notification->scheduled_at ? $notification->scheduled_at->format('Y-m-d\\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty for immediate sending</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" 
                               value="{{ old('expires_at', $notification->expires_at ? $notification->expires_at->format('Y-m-d\\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no expiration</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="draft" {{ (old('status', $notification->status) === 'draft') ? 'selected' : '' }}>Draft</option>
                        <option value="scheduled" {{ (old('status', $notification->status) === 'scheduled') ? 'selected' : '' }}>Scheduled</option>
                        <option value="sent" {{ (old('status', $notification->status) === 'sent') ? 'selected' : '' }}>Sent</option>
                        <option value="cancelled" {{ (old('status', $notification->status) === 'cancelled') ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.notifications.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">save</span>
                Update Notification
            </button>
        </div>
    </form>
</div>

<script>
function updateTypeOptions() {
    const type = document.getElementById('type').value;
    const priority = document.getElementById('priority');
    
    // Auto-set priority based on type (only if not already set)
    if (!priority.value || priority.value === 'normal') {
        if (type === 'alert' || type === 'maintenance') {
            priority.value = 'high';
        } else if (type === 'system') {
            priority.value = 'critical';
        }
    }
}
</script>
@endsection