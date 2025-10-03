@extends('layouts.admin')

@section('title', 'Issue Certificate')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Issue Certificate</h1>
            <p class="mt-1 text-sm text-gray-500">Create a new certificate for course completion</p>
        </div>
        <a href="{{ route('admin.certificates.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <span class="material-icons text-sm mr-2">arrow_back</span>
            Back to List
        </a>
    </div>

    <!-- Certificate Form -->
    <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
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

                <!-- Certificate Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Certificate Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           placeholder="Certificate of Completion"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave blank to use default title</p>
                </div>

                <!-- Grade and Completion Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Final Grade (%)</label>
                        <input type="number" name="grade" id="grade" value="{{ old('grade') }}" 
                               min="0" max="100" step="0.01" placeholder="85.5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('grade') border-red-500 @enderror">
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                        <input type="date" name="completion_date" id="completion_date" 
                               value="{{ old('completion_date', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('completion_date') border-red-500 @enderror">
                        @error('completion_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Issue Date -->
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                    <input type="date" name="issue_date" id="issue_date" required
                           value="{{ old('issue_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('issue_date') border-red-500 @enderror">
                    @error('issue_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Certificate Template -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Certificate Template</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Template Selection -->
                <div>
                    <label for="certificate_template_id" class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                    <select name="certificate_template_id" id="certificate_template_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('certificate_template_id') border-red-500 @enderror">
                        <option value="">Default Template</option>
                        @if(isset($templates))
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" {{ old('certificate_template_id') == $template->id ? 'selected' : '' }}>
                                    {{ $template->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('certificate_template_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Choose a template or use the default design</p>
                </div>

                <!-- Custom Message -->
                <div>
                    <label for="custom_message" class="block text-sm font-medium text-gray-700 mb-2">Custom Message</label>
                    <textarea name="custom_message" id="custom_message" rows="4" 
                              placeholder="Add a personal message or achievement note (optional)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('custom_message') border-red-500 @enderror">{{ old('custom_message') }}</textarea>
                    @error('custom_message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Certificate Options -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Certificate Options</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">Certificate Number</label>
                        <input type="text" name="certificate_number" id="certificate_number" 
                               value="{{ old('certificate_number') }}" 
                               placeholder="Auto-generated if left blank"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('certificate_number') border-red-500 @enderror">
                        @error('certificate_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank for auto-generation</p>
                    </div>

                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" 
                               value="{{ old('expiry_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 @error('expiry_date') border-red-500 @enderror">
                        @error('expiry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Leave blank for no expiry</p>
                    </div>
                </div>

                <!-- Status Options -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="send_email" id="send_email" value="1" 
                               {{ old('send_email', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="send_email" class="ml-2 block text-sm text-gray-900">
                            Send certificate email to student
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="is_public" id="is_public" value="1" 
                               {{ old('is_public') ? 'checked' : '' }}
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="is_public" class="ml-2 block text-sm text-gray-900">
                            Make certificate publicly verifiable
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.certificates.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" name="action" value="save_draft"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                <span class="material-icons text-sm mr-2">save</span>
                Save as Draft
            </button>
            <button type="submit" name="action" value="issue"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">workspace_premium</span>
                Issue Certificate
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate certificate number
    const generateNumber = () => {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        return `CERT-${year}${month}-${random}`;
    };

    // Generate number when fields change
    const courseSelect = document.getElementById('course_id');
    const studentSelect = document.getElementById('student_id');
    const numberInput = document.getElementById('certificate_number');

    function updateCertificateNumber() {
        if (!numberInput.value && courseSelect.value && studentSelect.value) {
            numberInput.placeholder = generateNumber();
        }
    }

    courseSelect.addEventListener('change', updateCertificateNumber);
    studentSelect.addEventListener('change', updateCertificateNumber);

    // Filter students by course enrollment
    courseSelect.addEventListener('change', function() {
        // This would normally fetch enrolled students via AJAX
        // For now, we'll show all students
    });
});
</script>
@endsection