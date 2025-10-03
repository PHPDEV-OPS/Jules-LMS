@extends('layouts.admin')

@section('title', 'Edit Enrollment')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Enrollment
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update enrollment information for {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}.
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <a href="{{ route('enrollments.show', $enrollment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">arrow_back</span>
                Back to Details
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('enrollments.update', $enrollment) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- Student Display (Read-only) -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Student</label>
                        <div class="mt-1 flex items-center space-x-3 p-3 bg-gray-50 rounded-md">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">
                                        {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $enrollment->student->email }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Course Display (Read-only) -->
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Course</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-md">
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->title }}</div>
                            <div class="text-sm text-gray-500">{{ $enrollment->course->course_code }}</div>
                        </div>
                    </div>

                    <!-- Enrollment Date -->
                    <div class="sm:col-span-1">
                        <label for="enrolled_at" class="block text-sm font-medium text-gray-700">
                            Enrollment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="enrolled_at" id="enrolled_at" required 
                               value="{{ old('enrolled_at', $enrollment->enrolled_at->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('enrolled_at') ? 'border-red-300' : '' }}">
                        @error('enrolled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="sm:col-span-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('status') ? 'border-red-300' : '' }}">
                            <option value="active" {{ old('status', $enrollment->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status', $enrollment->status) === 'suspended' ? 'selected' : '' }}>Suspended</option>
                            <option value="completed" {{ old('status', $enrollment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="dropped" {{ old('status', $enrollment->status) === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Completed Date -->
                    <div class="sm:col-span-1" id="completed-date-field" style="display: {{ old('status', $enrollment->status) === 'completed' ? 'block' : 'none' }};">
                        <label for="completion_date" class="block text-sm font-medium text-gray-700">
                            Completion Date
                        </label>
                        <input type="date" name="completion_date" id="completion_date" 
                               value="{{ old('completion_date', $enrollment->completion_date ? $enrollment->completion_date->format('Y-m-d') : '') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('completion_date') ? 'border-red-300' : '' }}">
                        @error('completion_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Drop Date and Reason -->
                    <div class="sm:col-span-1" id="dropped-date-field" style="display: {{ old('status', $enrollment->status) === 'dropped' ? 'block' : 'none' }};">
                        <label for="dropped_at" class="block text-sm font-medium text-gray-700">
                            Drop Date
                        </label>
                        <input type="date" name="dropped_at" id="dropped_at" 
                               value="{{ old('dropped_at', $enrollment->dropped_at ? $enrollment->dropped_at->format('Y-m-d') : '') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('dropped_at') ? 'border-red-300' : '' }}">
                        @error('dropped_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Drop Reason -->
                    <div class="sm:col-span-2" id="drop-reason-field" style="display: {{ old('status', $enrollment->status) === 'dropped' ? 'block' : 'none' }};">
                        <label for="drop_reason" class="block text-sm font-medium text-gray-700">
                            Drop Reason
                        </label>
                        <textarea name="drop_reason" id="drop_reason" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('drop_reason') ? 'border-red-300' : '' }}"
                                  placeholder="Reason for dropping the course...">{{ old('drop_reason', $enrollment->drop_reason) }}</textarea>
                        @error('drop_reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('notes') ? 'border-red-300' : '' }}"
                                  placeholder="Additional notes about this enrollment...">{{ old('notes', $enrollment->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="{{ route('enrollments.show', $enrollment) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="material-icons mr-2 text-sm">save</span>
                    Update Enrollment
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white shadow sm:rounded-lg border border-red-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-red-900 mb-2">Danger Zone</h3>
            <p class="text-sm text-red-600 mb-4">
                Permanently delete this enrollment. This action cannot be undone and will remove all associated data.
            </p>
            <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to permanently delete this enrollment? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <span class="material-icons mr-2 text-sm">delete_forever</span>
                    Delete Enrollment
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('status').addEventListener('change', function() {
    const status = this.value;
    const completedField = document.getElementById('completed-date-field');
    const droppedField = document.getElementById('dropped-date-field');
    const dropReasonField = document.getElementById('drop-reason-field');
    
    // Hide all conditional fields first
    completedField.style.display = 'none';
    droppedField.style.display = 'none';
    dropReasonField.style.display = 'none';
    
    // Show relevant fields based on status
    if (status === 'completed') {
        completedField.style.display = 'block';
        // Auto-fill completion date if not set
        const completedAtInput = document.getElementById('completed_at');
        if (!completedAtInput.value) {
            completedAtInput.value = new Date().toISOString().split('T')[0];
        }
    } else if (status === 'dropped') {
        droppedField.style.display = 'block';
        dropReasonField.style.display = 'block';
        // Auto-fill drop date if not set
        const droppedAtInput = document.getElementById('dropped_at');
        if (!droppedAtInput.value) {
            droppedAtInput.value = new Date().toISOString().split('T')[0];
        }
    }
});
</script>
@endsection