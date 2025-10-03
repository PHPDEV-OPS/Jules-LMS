@extends('layouts.admin')

@section('title', 'Edit Assessment')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Assessment</h1>
            <p class="mt-1 text-sm text-gray-500">Update assessment details and settings</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.assessments.show', $assessment) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">visibility</span>
                View Details
            </a>
            <a href="{{ route('admin.assessments.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Assessment Form -->
    <form method="POST" action="{{ route('admin.assessments.update', $assessment) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Assessment Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                    <select name="course_id" id="course_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (old('course_id', $assessment->course_id) == $course->id) ? 'selected' : '' }}>
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
                    <input type="text" name="title" id="title" value="{{ old('title', $assessment->title) }}" required
                           placeholder="Enter assessment title"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4" 
                              placeholder="Brief description of the assessment"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('description', $assessment->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type and Basic Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Assessment Type *</label>
                        <select name="type" id="type" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ (old('type', $assessment->type) == $key) ? 'selected' : '' }}>
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
                        <input type="number" name="duration_minutes" id="duration_minutes" 
                               value="{{ old('duration_minutes', $assessment->duration_minutes) }}" 
                               min="0" placeholder="0 for unlimited"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
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
                        <input type="number" name="total_marks" id="total_marks" 
                               value="{{ old('total_marks', $assessment->total_marks) }}" 
                               min="1" required step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('total_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="passing_marks" class="block text-sm font-medium text-gray-700 mb-2">Passing Marks *</label>
                        <input type="number" name="passing_marks" id="passing_marks" 
                               value="{{ old('passing_marks', $assessment->passing_marks) }}" 
                               min="0" required step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('passing_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attempts_allowed" class="block text-sm font-medium text-gray-700 mb-2">Attempts Allowed</label>
                        <input type="number" name="attempts_allowed" id="attempts_allowed" 
                               value="{{ old('attempts_allowed', $assessment->attempts_allowed) }}" 
                               min="1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
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
                               value="{{ old('start_date', $assessment->start_date?->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank to make available immediately</p>
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Available Until</label>
                        <input type="datetime-local" name="end_date" id="end_date" 
                               value="{{ old('end_date', $assessment->end_date?->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank for no end date</p>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                           {{ old('is_active', $assessment->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Assessment is active
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
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('instructions', $assessment->instructions) }}</textarea>
                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">These instructions will be shown to students before they start the assessment</p>
            </div>
        </div>

        <!-- Questions Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-medium text-gray-900">Questions</h2>
                <a href="#" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <span class="material-icons text-sm mr-2">add</span>
                    Add Question
                </a>
            </div>
            <div class="px-6 py-6">
                @if($assessment->questions->count() > 0)
                    <div class="space-y-4">
                        @foreach($assessment->questions->sortBy('order') as $question)
                        <div class="border rounded-lg p-4 bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">Question {{ $question->order }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ Str::limit($question->question_text, 100) }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                        </span>
                                        <span class="text-sm text-gray-500">{{ $question->points }} points</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <button type="button" class="text-indigo-600 hover:text-indigo-900">
                                        <span class="material-icons text-lg">edit</span>
                                    </button>
                                    <button type="button" class="text-red-600 hover:text-red-900">
                                        <span class="material-icons text-lg">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <span class="material-icons text-4xl text-gray-300 mb-2 block">quiz</span>
                        <p class="text-lg font-medium text-gray-900">No questions added yet</p>
                        <p class="text-sm text-gray-500">Add questions to make this assessment functional.</p>
                    </div>
                @endif
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
                Update Assessment
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