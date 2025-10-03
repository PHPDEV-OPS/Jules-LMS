@extends('layouts.admin')

@section('title', 'Edit Student - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Student
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update information for {{ $student->first_name }} {{ $student->last_name }}.
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <a href="{{ route('students.show', $student) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">arrow_back</span>
                Back to Details
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <!-- First Name -->
                    <div class="sm:col-span-1">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" required 
                               value="{{ old('first_name', $student->first_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('first_name') ? 'border-red-300' : '' }}">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="sm:col-span-1">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name" required 
                               value="{{ old('last_name', $student->last_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('last_name') ? 'border-red-300' : '' }}">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-1">
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" required 
                               value="{{ old('email', $student->email) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('email') ? 'border-red-300' : '' }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="sm:col-span-1">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">
                            Phone Number
                        </label>
                        <input type="tel" name="phone_number" id="phone_number" 
                               value="{{ old('phone_number', $student->phone_number) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('phone_number') ? 'border-red-300' : '' }}">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="sm:col-span-1">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">
                            Date of Birth <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="date_of_birth" id="date_of_birth" required 
                               value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('date_of_birth') ? 'border-red-300' : '' }}">
                        @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student ID -->
                    <div class="sm:col-span-1">
                        <label for="student_id" class="block text-sm font-medium text-gray-700">
                            Student ID
                        </label>
                        <input type="text" name="student_id" id="student_id" 
                               value="{{ old('student_id', $student->student_id) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('student_id') ? 'border-red-300' : '' }}">
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700">
                            Address
                        </label>
                        <textarea name="address" id="address" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('address') ? 'border-red-300' : '' }}"
                                  placeholder="Full address (optional)">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div class="sm:col-span-1">
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">
                            Emergency Contact Name
                        </label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" 
                               value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('emergency_contact_name') ? 'border-red-300' : '' }}">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact Phone -->
                    <div class="sm:col-span-1">
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">
                            Emergency Contact Phone
                        </label>
                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" 
                               value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('emergency_contact_phone') ? 'border-red-300' : '' }}">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Admin Notes
                        </label>
                        <textarea name="notes" id="notes" rows="4" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 {{ $errors->has('notes') ? 'border-red-300' : '' }}"
                                  placeholder="Any additional notes about this student...">{{ old('notes', $student->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Account Information -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Account Information</h3>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-500">Created:</span>
                                <span class="text-gray-900">{{ $student->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Last Updated:</span>
                                <span class="text-gray-900">{{ $student->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Total Enrollments:</span>
                                <span class="text-gray-900">{{ $student->enrollments->count() }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Active Enrollments:</span>
                                <span class="text-gray-900">{{ $student->enrollments->where('status', 'active')->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 space-x-3">
                <a href="{{ route('students.show', $student) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <span class="material-icons mr-2 text-sm">save</span>
                    Update Student
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white shadow sm:rounded-lg border border-red-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-red-900 mb-2">Danger Zone</h3>
            <p class="text-sm text-red-600 mb-4">
                Permanently delete this student account. This action cannot be undone and will remove all associated enrollments and data.
            </p>
            <form action="{{ route('students.destroy', $student) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to permanently delete this student? This action cannot be undone and will remove all associated enrollments.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <span class="material-icons mr-2 text-sm">delete_forever</span>
                    Delete Student
                </button>
            </form>
        </div>
    </div>
</div>
@endsection