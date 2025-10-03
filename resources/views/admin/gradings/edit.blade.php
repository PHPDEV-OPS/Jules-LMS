@extends('layouts.admin')

@section('title', 'Edit Grade')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Grade</h1>
            <p class="mt-1 text-sm text-gray-500">Update grade and feedback for student submission</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.gradings.show', $grading) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">visibility</span>
                View Details
            </a>
            <a href="{{ route('admin.gradings.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Grade Form -->
    <form method="POST" action="{{ route('admin.gradings.update', $grading) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Grade Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Assessment (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assessment</label>
                    <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                        <div class="text-sm font-medium text-gray-900">{{ $grading->assessment->title ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $grading->assessment->course->title ?? '' }}</div>
                    </div>
                </div>

                <!-- Student (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <div class="px-3 py-2 border border-gray-200 rounded-md bg-gray-50">
                        <div class="text-sm font-medium text-gray-900">{{ $grading->student->name ?? 'N/A' }}</div>
                        <div class="text-sm text-gray-500">{{ $grading->student->email ?? '' }}</div>
                    </div>
                </div>

                <!-- Grade Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="marks" class="block text-sm font-medium text-gray-700 mb-2">Marks Obtained *</label>
                        <input type="number" name="marks" id="marks" 
                               value="{{ old('marks', $grading->marks) }}" required
                               min="0" step="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Total Marks</label>
                        <input type="number" id="total_marks" 
                               value="{{ $grading->assessment->total_marks ?? 100 }}" 
                               readonly
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50">
                    </div>
                </div>

                <!-- Percentage Display -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Percentage</label>
                    <div id="percentage-display" class="text-2xl font-bold text-blue-600">{{ round(($grading->marks / ($grading->assessment->total_marks ?? 100)) * 100) }}%</div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select name="status" id="status" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="submitted" {{ (old('status', $grading->status) === 'submitted') ? 'selected' : '' }}>Submitted</option>
                        <option value="graded" {{ (old('status', $grading->status) === 'graded') ? 'selected' : '' }}>Graded</option>
                        <option value="in_progress" {{ (old('status', $grading->status) === 'in_progress') ? 'selected' : '' }}>In Progress</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Feedback Section -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Feedback</h2>
            </div>
            <div class="px-6 py-6">
                <textarea name="feedback" id="feedback" rows="6" 
                          placeholder="Provide detailed feedback for the student..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('feedback', $grading->feedback) }}</textarea>
                @error('feedback')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-2">This feedback will be visible to the student</p>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.gradings.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">save</span>
                Update Grade
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const marksInput = document.getElementById('marks');
    const totalMarksInput = document.getElementById('total_marks');
    const percentageDisplay = document.getElementById('percentage-display');

    function calculatePercentage() {
        const marks = parseFloat(marksInput.value) || 0;
        const total = parseFloat(totalMarksInput.value) || 100;
        const percentage = total > 0 ? Math.round((marks / total) * 100) : 0;
        
        percentageDisplay.textContent = percentage + '%';
        
        // Update color based on grade
        if (percentage >= 90) percentageDisplay.className = 'text-2xl font-bold text-green-600';
        else if (percentage >= 80) percentageDisplay.className = 'text-2xl font-bold text-blue-600';
        else if (percentage >= 70) percentageDisplay.className = 'text-2xl font-bold text-yellow-600';
        else if (percentage >= 60) percentageDisplay.className = 'text-2xl font-bold text-orange-600';
        else percentageDisplay.className = 'text-2xl font-bold text-red-600';
    }

    marksInput.addEventListener('input', calculatePercentage);
});
</script>
@endsection