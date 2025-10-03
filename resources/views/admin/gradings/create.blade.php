@extends('layouts.admin')

@section('title', 'Add Grade')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add Grade</h1>
            <p class="mt-1 text-sm text-gray-500">Enter or modify grades for student submissions</p>
        </div>
        <a href="{{ route('admin.gradings.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons text-sm mr-2">arrow_back</span>
            Back to List
        </a>
    </div>

    <!-- Grade Form -->
    <form method="POST" action="{{ route('admin.gradings.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Grade Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Assessment Selection -->
                <div>
                    <label for="assessment_id" class="block text-sm font-medium text-gray-700 mb-2">Assessment *</label>
                    <select name="assessment_id" id="assessment_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('assessment_id') border-red-500 @enderror">
                        <option value="">Select an assessment</option>
                        @foreach($assessments as $assessment)
                            <option value="{{ $assessment->id }}" 
                                    data-total-marks="{{ $assessment->total_marks }}"
                                    {{ old('assessment_id') == $assessment->id ? 'selected' : '' }}>
                                {{ $assessment->title }} ({{ $assessment->course->title ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('assessment_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student *</label>
                    <select name="student_id" id="student_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('student_id') border-red-500 @enderror">
                        <option value="">Select a student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="marks" class="block text-sm font-medium text-gray-700 mb-2">Marks Obtained *</label>
                        <input type="number" name="marks" id="marks" value="{{ old('marks') }}" required
                               min="0" step="0.01" placeholder="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('marks') border-red-500 @enderror">
                        @error('marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Enter the marks obtained by the student</p>
                    </div>

                    <div>
                        <label for="total_marks" class="block text-sm font-medium text-gray-700 mb-2">Total Marks</label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks') }}" 
                               min="1" step="0.01" placeholder="100" readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Automatically set based on assessment</p>
                    </div>
                </div>

                <!-- Percentage Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Percentage</label>
                    <div id="percentage-display" class="text-2xl font-bold text-blue-600">0%</div>
                    <p class="text-xs text-gray-500 mt-1">Calculated automatically</p>
                </div>

                <!-- Submission Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="submitted_at" class="block text-sm font-medium text-gray-700 mb-2">Submission Date</label>
                        <input type="datetime-local" name="submitted_at" id="submitted_at" 
                               value="{{ old('submitted_at', date('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('submitted_at') border-red-500 @enderror">
                        @error('submitted_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attempt_number" class="block text-sm font-medium text-gray-700 mb-2">Attempt Number</label>
                        <input type="number" name="attempt_number" id="attempt_number" 
                               value="{{ old('attempt_number', 1) }}" 
                               min="1" max="10"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('attempt_number') border-red-500 @enderror">
                        @error('attempt_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Feedback & Comments</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Feedback -->
                <div>
                    <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">Feedback</label>
                    <textarea name="feedback" id="feedback" rows="6" 
                              placeholder="Provide detailed feedback for the student..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('feedback') border-red-500 @enderror">{{ old('feedback') }}</textarea>
                    @error('feedback')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">This feedback will be visible to the student</p>
                </div>

                <!-- Grade Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('status') border-red-500 @enderror">
                        <option value="submitted" {{ old('status') === 'submitted' ? 'selected' : '' }}>Submitted (Not Graded)</option>
                        <option value="graded" {{ old('status', 'graded') === 'graded' ? 'selected' : '' }}>Graded</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submission Data (Optional) -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Submission Data (Optional)</h2>
            </div>
            <div class="px-6 py-6">
                <div>
                    <label for="submission_data" class="block text-sm font-medium text-gray-700 mb-2">Student's Answers/Work</label>
                    <textarea name="submission_data" id="submission_data" rows="8" 
                              placeholder="Enter or paste student's submission content (answers, uploaded file content, etc.)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 font-mono text-sm @error('submission_data') border-red-500 @enderror">{{ old('submission_data') }}</textarea>
                    @error('submission_data')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Optional: Record of what the student submitted</p>
                </div>
            </div>
        </div>

        <!-- Options -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Grade Options</h2>
            </div>
            <div class="px-6 py-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="send_notification" id="send_notification" value="1" 
                           {{ old('send_notification', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="send_notification" class="ml-2 block text-sm text-gray-900">
                        Send grade notification to student
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="publish_grade" id="publish_grade" value="1" 
                           {{ old('publish_grade', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="publish_grade" class="ml-2 block text-sm text-gray-900">
                        Make grade visible to student immediately
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.gradings.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" name="action" value="save_draft"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <span class="material-icons text-sm mr-2">save</span>
                Save as Draft
            </button>
            <button type="submit" name="action" value="finalize"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">grading</span>
                Submit Grade
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assessmentSelect = document.getElementById('assessment_id');
    const marksInput = document.getElementById('marks');
    const totalMarksInput = document.getElementById('total_marks');
    const percentageDisplay = document.getElementById('percentage-display');

    // Update total marks when assessment changes
    assessmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const totalMarks = selectedOption.dataset.totalMarks || 100;
        totalMarksInput.value = totalMarks;
        calculatePercentage();
    });

    // Calculate percentage when marks change
    marksInput.addEventListener('input', calculatePercentage);
    totalMarksInput.addEventListener('input', calculatePercentage);

    function calculatePercentage() {
        const marks = parseFloat(marksInput.value) || 0;
        const total = parseFloat(totalMarksInput.value) || 100;
        const percentage = total > 0 ? Math.round((marks / total) * 100) : 0;
        
        percentageDisplay.textContent = percentage + '%';
        
        // Update color based on grade
        percentageDisplay.className = 'text-2xl font-bold ' + getGradeColor(percentage);
    }

    function getGradeColor(percentage) {
        if (percentage >= 90) return 'text-green-600';
        if (percentage >= 80) return 'text-blue-600';
        if (percentage >= 70) return 'text-yellow-600';
        if (percentage >= 60) return 'text-orange-600';
        return 'text-red-600';
    }

    // Set max marks for marks input when assessment changes
    assessmentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const totalMarks = selectedOption.dataset.totalMarks || 100;
        marksInput.max = totalMarks;
    });

    // Initialize if assessment is pre-selected
    if (assessmentSelect.value) {
        assessmentSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection