@extends('layouts.admin')

@section('title', 'Create Assessment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Assessment</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new quiz, assignment, or exam</p>
        </div>
        <a href="{{ route('admin.assessments.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons text-sm mr-2">arrow_back</span>
            Back to List
        </a>
    </div>

    <!-- Assessment Form -->
    <form method="POST" action="{{ route('admin.assessments.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Assessment Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                    <select name="course_id" id="course_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('course_id') border-red-500 @enderror">
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Assessment Title *</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           placeholder="Enter assessment title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              placeholder="Brief description of the assessment"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type and Basic Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Assessment Type *</label>
                        <select name="type" id="type" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('type') border-red-500 @enderror">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes') }}" 
                               min="0" placeholder="0 for unlimited"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('duration_minutes') border-red-500 @enderror">
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank or set to 0 for no time limit</p>
                    </div>
                </div>

                <!-- Marks and Attempts -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="total_marks" class="block text-sm font-medium text-gray-700 mb-2">Total Marks *</label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks', 100) }}" 
                               min="1" required step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('total_marks') border-red-500 @enderror">
                        @error('total_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passing_marks" class="block text-sm font-medium text-gray-700 mb-2">Passing Marks *</label>
                        <input type="number" name="passing_marks" id="passing_marks" value="{{ old('passing_marks', 60) }}" 
                               min="0" required step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('passing_marks') border-red-500 @enderror">
                        @error('passing_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attempts_allowed" class="block text-sm font-medium text-gray-700 mb-2">Attempts Allowed</label>
                        <input type="number" name="attempts_allowed" id="attempts_allowed" value="{{ old('attempts_allowed', 1) }}" 
                               min="1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('attempts_allowed') border-red-500 @enderror">
                        @error('attempts_allowed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule & Availability -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Schedule & Availability</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Available From</label>
                        <input type="datetime-local" name="start_date" id="start_date" 
                               value="{{ old('start_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank to make available immediately</p>
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Available Until</label>
                        <input type="datetime-local" name="end_date" id="end_date" 
                               value="{{ old('end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank for no end date</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Activate assessment immediately
                    </label>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Instructions for Students</h2>
            </div>
            <div class="px-6 py-6">
                <textarea name="instructions" id="instructions" rows="6" 
                          placeholder="Enter detailed instructions for students taking this assessment..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('instructions') border-red-500 @enderror">{{ old('instructions') }}</textarea>
                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">These instructions will be shown to students before they start the assessment</p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.assessments.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">save</span>
                Create Assessment
            </button>
        </div>
    </form>
</div>

<script>
// Auto-calculate passing marks percentage
document.addEventListener('DOMContentLoaded', function() {
    const totalMarks = document.getElementById('total_marks');
    const passingMarks = document.getElementById('passing_marks');
    
    function updatePassingMarks() {
        const total = parseFloat(totalMarks.value) || 100;
        const passing = parseFloat(passingMarks.value) || 0;
        const percentage = total > 0 ? Math.round((passing / total) * 100) : 0;
        
        // Show percentage hint
        const existingHint = passingMarks.parentNode.querySelector('.percentage-hint');
        if (existingHint) {
            existingHint.textContent = `${percentage}% of total marks`;
        } else {
            const hint = document.createElement('p');
            hint.className = 'text-xs text-blue-600 mt-1 percentage-hint';
            hint.textContent = `${percentage}% of total marks`;
            passingMarks.parentNode.appendChild(hint);
        }
    }
    
    totalMarks.addEventListener('input', updatePassingMarks);
    passingMarks.addEventListener('input', updatePassingMarks);
    updatePassingMarks();
});
</script>
@endsection