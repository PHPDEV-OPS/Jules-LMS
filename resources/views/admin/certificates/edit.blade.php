@extends('layouts.admin')

@section('title', 'Edit Certificate')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Certificate</h1>
            <p class="mt-1 text-sm text-gray-500">Update certificate details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.certificates.show', $certificate) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">visibility</span>
                View Certificate
            </a>
            <a href="{{ route('admin.certificates.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Certificate Form -->
    <form method="POST" action="{{ route('admin.certificates.update', $certificate) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Certificate Information</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Course Selection -->
                <div>
                    <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">Course *</label>
                    <select name="course_id" id="course_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (old('course_id', $certificate->course_id) == $course->id) ? 'selected' : '' }}>
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
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        <option value="">Select a student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ (old('student_id', $certificate->student_id) == $student->id) ? 'selected' : '' }}>
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
                    <input type="text" name="title" id="title" value="{{ old('title', $certificate->title) }}"
                           placeholder="Certificate of Completion"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grade and Completion Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Final Grade (%)</label>
                        <input type="number" name="grade" id="grade" 
                               value="{{ old('grade', $certificate->grade) }}" 
                               min="0" max="100" step="0.01" placeholder="85.5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('grade')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-2">Completion Date</label>
                        <input type="date" name="completion_date" id="completion_date" 
                               value="{{ old('completion_date', $certificate->completion_date?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                        @error('completion_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Issue Date -->
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">Issue Date *</label>
                    <input type="date" name="issue_date" id="issue_date" required
                           value="{{ old('issue_date', $certificate->issue_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('issue_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Certificate Details -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Certificate Details</h2>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Certificate Number -->
                <div>
                    <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">Certificate Number *</label>
                    <input type="text" name="certificate_number" id="certificate_number" required
                           value="{{ old('certificate_number', $certificate->certificate_number) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('certificate_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Unique identifier for this certificate</p>
                </div>

                <!-- Template and Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="certificate_template_id" class="block text-sm font-medium text-gray-700 mb-2">Template</label>
                        <select name="certificate_template_id" id="certificate_template_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="">Default Template</option>
                            @if(isset($templates))
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ (old('certificate_template_id', $certificate->certificate_template_id) == $template->id) ? 'selected' : '' }}>
                                        {{ $template->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('certificate_template_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                        <select name="status" id="status" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                            <option value="issued" {{ (old('status', $certificate->status) === 'issued') ? 'selected' : '' }}>Issued</option>
                            <option value="downloaded" {{ (old('status', $certificate->status) === 'downloaded') ? 'selected' : '' }}>Downloaded</option>
                            <option value="revoked" {{ (old('status', $certificate->status) === 'revoked') ? 'selected' : '' }}>Revoked</option>
                            <option value="expired" {{ (old('status', $certificate->status) === 'expired') ? 'selected' : '' }}>Expired</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Custom Message -->
                <div>
                    <label for="custom_message" class="block text-sm font-medium text-gray-700 mb-2">Custom Message</label>
                    <textarea name="custom_message" id="custom_message" rows="4" 
                              placeholder="Add a personal message or achievement note (optional)"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">{{ old('custom_message', $certificate->custom_message) }}</textarea>
                    @error('custom_message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Expiry Date -->
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" 
                           value="{{ old('expiry_date', $certificate->expiry_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500">
                    @error('expiry_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">Leave blank for no expiry</p>
                </div>
            </div>
        </div>

        <!-- Certificate Options -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Options</h2>
            </div>
            <div class="px-6 py-6 space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" name="is_public" id="is_public" value="1" 
                           {{ old('is_public', $certificate->is_public) ? 'checked' : '' }}
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="is_public" class="ml-2 block text-sm text-gray-900">
                        Make certificate publicly verifiable
                    </label>
                </div>

                @if($certificate->status === 'issued')
                <div class="flex items-center">
                    <input type="checkbox" name="send_updated_email" id="send_updated_email" value="1" 
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                    <label for="send_updated_email" class="ml-2 block text-sm text-gray-900">
                        Send updated certificate email to student
                    </label>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($certificate->status === 'revoked')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <span class="material-icons text-red-400">warning</span>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Certificate Revoked</h3>
                    <p class="mt-2 text-sm text-red-700">This certificate has been revoked. You can change the status to reissue it.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            <a href="{{ route('admin.certificates.index') }}" 
               class="px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </a>
            
            @if($certificate->status !== 'revoked')
            <button type="submit" name="action" value="revoke"
                    onclick="return confirm('Are you sure you want to revoke this certificate? This action cannot be undone.')"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons text-sm mr-2">cancel</span>
                Revoke Certificate
            </button>
            @endif
            
            <button type="submit" name="action" value="update"
                    class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <span class="material-icons text-sm mr-2">save</span>
                Update Certificate
            </button>
        </div>
    </form>
</div>
@endsection