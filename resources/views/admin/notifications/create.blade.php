@extends('layouts.admin')

@section('title', 'Create Notification')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Notification</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new system notification for users</p>
        </div>
        <a href="{{ route('admin.notifications.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons text-sm mr-2">arrow_back</span>
            Back to List
        </a>
    </div>

    <!-- Create Form -->
    <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Notification Details</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
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
                              placeholder="Enter notification message...">{{ old('message') }}</textarea>
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
                            <option value="system" {{ old('type') === 'system' ? 'selected' : '' }}>System Notification</option>
                            <option value="course" {{ old('type') === 'course' ? 'selected' : '' }}>Course Notification</option>
                            <option value="assessment" {{ old('type') === 'assessment' ? 'selected' : '' }}>Assessment Notification</option>
                            <option value="grade" {{ old('type') === 'grade' ? 'selected' : '' }}>Grade Notification</option>
                            <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Announcement</option>
                            <option value="reminder" {{ old('type') === 'reminder' ? 'selected' : '' }}>Reminder</option>
                            <option value="alert" {{ old('type') === 'alert' ? 'selected' : '' }}>Alert</option>
                            <option value="maintenance" {{ old('type') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select name="priority" id="priority" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ old('priority', 'normal') === 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') === 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Target Users -->
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Target Recipients *</label>
                    
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="radio" name="target_type" id="all_users" value="all" 
                                   {{ old('target_type', 'all') === 'all' ? 'checked' : '' }}
                                   onchange="updateTargetOptions()"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="all_users" class="ml-2 block text-sm text-gray-900">All Users</label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" name="target_type" id="specific_user" value="user" 
                                   {{ old('target_type') === 'user' ? 'checked' : '' }}
                                   onchange="updateTargetOptions()"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="specific_user" class="ml-2 block text-sm text-gray-900">Specific User</label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" name="target_type" id="course_users" value="course" 
                                   {{ old('target_type') === 'course' ? 'checked' : '' }}
                                   onchange="updateTargetOptions()"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="course_users" class="ml-2 block text-sm text-gray-900">Course Participants</label>
                        </div>

                        <div class="flex items-center">
                            <input type="radio" name="target_type" id="role_users" value="role" 
                                   {{ old('target_type') === 'role' ? 'checked' : '' }}
                                   onchange="updateTargetOptions()"
                                   class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300">
                            <label for="role_users" class="ml-2 block text-sm text-gray-900">Users by Role</label>
                        </div>
                    </div>

                    @error('target_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Target Selection (Dynamic) -->
                <div id="target-selection" class="hidden">
                    <!-- User Selection -->
                    <div id="user-selection" class="hidden">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                        <select name="user_id" id="user_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Choose a user</option>
                            @foreach(\App\Models\User::all() as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Course Selection -->
                    <div id="course-selection" class="hidden">
                        <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Select Course</label>
                        <select name="course_id" id="course_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Choose a course</option>
                            @foreach(\App\Models\Course::all() as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Role Selection -->
                    <div id="role-selection" class="hidden">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                        <select name="role" id="role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Choose a role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrators</option>
                            <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Instructors</option>
                            <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Students</option>
                        </select>
                    </div>
                </div>

                <!-- Additional Options -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_email" id="send_email" value="1" 
                               {{ old('send_email') ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="send_email" class="ml-2 block text-sm text-gray-900">Send email notification</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_persistent" id="is_persistent" value="1" 
                               {{ old('is_persistent') ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_persistent" class="ml-2 block text-sm text-gray-900">Persistent notification (remains until dismissed)</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="requires_acknowledgment" id="requires_acknowledgment" value="1" 
                               {{ old('requires_acknowledgment') ? 'checked' : '' }}
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
                    <input type="url" name="action_url" id="action_url" value="{{ old('action_url') }}"
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
                               value="{{ old('scheduled_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty to send immediately</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" 
                               value="{{ old('expires_at') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('expires_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no expiration</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.notifications.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" name="action" value="schedule"
                    class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">schedule</span>
                Save as Draft
            </button>
            <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">send</span>
                Create & Send
            </button>
        </div>
    </form>
</div>

<script>
function updateTargetOptions() {
    const targetType = document.querySelector('input[name="target_type"]:checked').value;
    const selectionDiv = document.getElementById('target-selection');
    const userDiv = document.getElementById('user-selection');
    const courseDiv = document.getElementById('course-selection');
    const roleDiv = document.getElementById('role-selection');

    // Hide all selections first
    selectionDiv.classList.add('hidden');
    userDiv.classList.add('hidden');
    courseDiv.classList.add('hidden');
    roleDiv.classList.add('hidden');

    // Show appropriate selection based on target type
    if (targetType === 'user') {
        selectionDiv.classList.remove('hidden');
        userDiv.classList.remove('hidden');
    } else if (targetType === 'course') {
        selectionDiv.classList.remove('hidden');
        courseDiv.classList.remove('hidden');
    } else if (targetType === 'role') {
        selectionDiv.classList.remove('hidden');
        roleDiv.classList.remove('hidden');
    }
}

function updateTypeOptions() {
    const type = document.getElementById('type').value;
    const priority = document.getElementById('priority');
    
    // Auto-set priority based on type
    if (type === 'alert' || type === 'maintenance') {
        priority.value = 'high';
    } else if (type === 'system') {
        priority.value = 'critical';
    } else {
        priority.value = 'normal';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateTargetOptions();
});
</script>
@endsection