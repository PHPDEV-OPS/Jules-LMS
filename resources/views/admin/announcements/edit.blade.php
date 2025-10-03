@extends('layouts.admin')

@section('title', 'Edit Announcement')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Announcement</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $announcement->title }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.announcements.show', $announcement) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">visibility</span>
                View Details
            </a>
            <a href="{{ route('admin.announcements.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Announcement Details</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $announcement->title) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                           placeholder="Enter announcement title">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content *</label>
                    <textarea name="content" id="content" rows="8" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Enter announcement content...">{{ old('content', $announcement->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course</label>
                    <select name="course_id" id="course_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="">All Courses (General Announcement)</option>
                        @foreach(\App\Models\Course::all() as $course)
                            <option value="{{ $course->id }}" 
                                {{ (old('course_id', $announcement->course_id) == $course->id) ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave empty for general announcements visible to all students</p>
                </div>

                <!-- Priority and Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" id="priority"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="low" {{ (old('priority', $announcement->priority) === 'low') ? 'selected' : '' }}>Low</option>
                            <option value="normal" {{ (old('priority', $announcement->priority) === 'normal') ? 'selected' : '' }}>Normal</option>
                            <option value="high" {{ (old('priority', $announcement->priority) === 'high') ? 'selected' : '' }}>High</option>
                            <option value="urgent" {{ (old('priority', $announcement->priority) === 'urgent') ? 'selected' : '' }}>Urgent</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="general" {{ (old('type', $announcement->type) === 'general') ? 'selected' : '' }}>General</option>
                            <option value="course_update" {{ (old('type', $announcement->type) === 'course_update') ? 'selected' : '' }}>Course Update</option>
                            <option value="system_maintenance" {{ (old('type', $announcement->type) === 'system_maintenance') ? 'selected' : '' }}>System Maintenance</option>
                            <option value="deadline_reminder" {{ (old('type', $announcement->type) === 'deadline_reminder') ? 'selected' : '' }}>Deadline Reminder</option>
                            <option value="event" {{ (old('type', $announcement->type) === 'event') ? 'selected' : '' }}>Event</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Visibility Options -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">Active (visible to students)</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="pin_to_top" id="pin_to_top" value="1" 
                               {{ old('pin_to_top', $announcement->pin_to_top) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="pin_to_top" class="ml-2 block text-sm text-gray-900">Pin to top of announcements</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scheduling Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Publishing Schedule</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" id="published_at" 
                               value="{{ old('published_at', $announcement->published_at ? $announcement->published_at->format('Y-m-d\\TH:i') : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('published_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave empty to publish immediately</p>
                    </div>

                    <div>
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-2">Expiration Date & Time</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" 
                               value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\\TH:i') : '') }}"
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
            <a href="{{ route('admin.announcements.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">save</span>
                Update Announcement
            </button>
        </div>
    </form>
</div>
@endsection