@extends('layouts.admin')

@section('title', 'Manual Enrollment')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Manual Enrollment
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Enroll a student to a course manually.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">arrow_back</span>
                Back to Enrollments
            </a>
        </div>
    </div>

    <!-- Enrollment Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('enrollments.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- Student Selection -->
                    <div class="sm:col-span-1">
                        <label for="student_id" class="block text-sm font-medium text-gray-700">
                            Student <span class="text-red-500">*</span>
                        </label>
                        <select name="student_id" id="student_id" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('student_id') ? 'border-red-300' : '' }}">
                            <option value="">Select a student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->first_name }} {{ $student->last_name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course Selection -->
                    <div class="sm:col-span-1">
                        <label for="course_id" class="block text-sm font-medium text-gray-700">
                            Course <span class="text-red-500">*</span>
                        </label>
                        <select name="course_id" id="course_id" required 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('course_id') ? 'border-red-300' : '' }}">
                            <option value="">Select a course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" 
                                        data-capacity="{{ $course->max_capacity }}" 
                                        data-enrolled="{{ $course->enrollments_count }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }} ({{ $course->course_code }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="course-info" class="mt-2 text-sm text-gray-600" style="display: none;">
                            <div class="bg-blue-50 p-3 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <span class="material-icons text-blue-500">info</span>
                                    </div>
                                    <div class="ml-3">
                                        <div id="course-capacity"></div>
                                        <div id="course-schedule"></div>
                                        <div id="course-instructor"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Date -->
                    <div class="sm:col-span-1">
                        <label for="enrolled_on" class="block text-sm font-medium text-gray-700">
                            Enrollment Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="enrolled_on" id="enrolled_on" required 
                               value="{{ old('enrolled_on', date('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('enrolled_on') ? 'border-red-300' : '' }}">
                        @error('enrolled_on')
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
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('status')
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
                                  placeholder="Optional notes about this enrollment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Quick Student Registration -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Student Not Found?</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <p class="text-sm text-gray-600 mb-3">
                            If the student is not in the system, you can create a new student account first.
                        </p>
                        <a href="{{ route('students.create') }}?return_to=enrollment" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200">
                            <span class="material-icons mr-2 text-sm">person_add</span>
                            Create New Student
                        </a>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="{{ route('enrollments.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="material-icons mr-2 text-sm">person_add</span>
                    Create Enrollment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('course_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const courseInfo = document.getElementById('course-info');
    
    if (selectedOption.value) {
        const capacity = selectedOption.dataset.capacity;
        const enrolled = selectedOption.dataset.enrolled;
        const remaining = capacity - enrolled;
        
        document.getElementById('course-capacity').innerHTML = 
            `<strong>Capacity:</strong> ${enrolled}/${capacity} students enrolled (${remaining} spots remaining)`;
        
        courseInfo.style.display = 'block';
        
        // Show warning if course is full
        if (remaining <= 0) {
            courseInfo.querySelector('div').className = 'bg-red-50 p-3 rounded-md';
            courseInfo.querySelector('.material-icons').className = 'material-icons text-red-500';
            courseInfo.querySelector('.material-icons').textContent = 'warning';
        } else if (remaining <= 5) {
            courseInfo.querySelector('div').className = 'bg-yellow-50 p-3 rounded-md';
            courseInfo.querySelector('.material-icons').className = 'material-icons text-yellow-500';
            courseInfo.querySelector('.material-icons').textContent = 'warning';
        } else {
            courseInfo.querySelector('div').className = 'bg-blue-50 p-3 rounded-md';
            courseInfo.querySelector('.material-icons').className = 'material-icons text-blue-500';
            courseInfo.querySelector('.material-icons').textContent = 'info';
        }
    } else {
        courseInfo.style.display = 'none';
    }
});
</script>
@endsection