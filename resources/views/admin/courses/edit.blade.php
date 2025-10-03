@extends('layouts.admin')

@section('title', 'Edit Course - ' . $course->course_name)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-red-100 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="{{ route('admin.courses.show', $course) }}" class="text-red-600 hover:text-red-700 transition-colors">
                        <span class="material-icons text-3xl">arrow_back</span>
                    </a>
                    Edit Course
                </h1>
                <p class="text-gray-600 mt-1">{{ $course->course_code }} - {{ $course->course_name }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto px-6 py-6">
        <div class="max-w-4xl mx-auto">
            <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-red-600">info</span>
                            Basic Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Course Code *</label>
                                <input type="text" name="course_code" value="{{ old('course_code', $course->course_code) }}" 
                                       placeholder="e.g., CS101, MATH201"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('course_code') border-red-500 @enderror">
                                @error('course_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Credits *</label>
                                <select name="credits" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('credits') border-red-500 @enderror">
                                    <option value="">Select Credits</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('credits', $course->credits) == $i ? 'selected' : '' }}>{{ $i }} Credit{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                @error('credits')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Course Name *</label>
                            <input type="text" name="course_name" value="{{ old('course_name', $course->course_name) }}" 
                                   placeholder="Enter course name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('course_name') border-red-500 @enderror">
                            @error('course_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                            <textarea name="description" rows="4" 
                                      placeholder="Enter course description"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('description') border-red-500 @enderror">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instructor *</label>
                                <input type="text" name="instructor" value="{{ old('instructor', $course->instructor) }}" 
                                       placeholder="Instructor name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('instructor') border-red-500 @enderror">
                                @error('instructor')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category *</label>
                                <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('category') border-red-500 @enderror">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $key => $value)
                                        <option value="{{ $key }}" {{ old('category', $course->category) === $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule & Pricing -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-red-600">schedule</span>
                            Schedule & Pricing
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $course->start_date?->format('Y-m-d')) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('start_date') border-red-500 @enderror">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">End Date *</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $course->end_date?->format('Y-m-d')) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('end_date') border-red-500 @enderror">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price ($) *</label>
                                <input type="number" name="price" value="{{ old('price', $course->price) }}" step="0.01" min="0"
                                       placeholder="0.00"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('price') border-red-500 @enderror">
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">Enter 0 for free courses</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Max Students *</label>
                                <input type="number" name="max_students" value="{{ old('max_students', $course->max_students) }}" min="1"
                                       placeholder="e.g., 30"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('max_students') border-red-500 @enderror">
                                @error('max_students')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media & Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                            <span class="material-icons text-red-600">image</span>
                            Media & Status
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Current Image -->
                        @if($course->image_url)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Image</label>
                                <div class="flex items-start gap-4">
                                    <img src="{{ Storage::url($course->image_url) }}" alt="{{ $course->course_name }}" 
                                         class="w-32 h-24 object-cover rounded-lg border border-gray-200">
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600">Upload a new image to replace the current one</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $course->image_url ? 'New Course Image' : 'Course Image' }}
                            </label>
                            <input type="file" name="image" accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('image') border-red-500 @enderror">
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Recommended: 800x600px, JPG or PNG format</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="active" 
                                           {{ old('status', $course->status) === 'active' ? 'checked' : '' }}
                                           class="text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="inactive" 
                                           {{ old('status', $course->status) === 'inactive' ? 'checked' : '' }}
                                           class="text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="draft" 
                                           {{ old('status', $course->status) === 'draft' ? 'checked' : '' }}
                                           class="text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-gray-700">Draft</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Enrollment Warning -->
                @if($course->enrollments()->count() > 0)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <span class="material-icons text-yellow-600 mt-0.5">warning</span>
                            <div>
                                <h4 class="text-sm font-medium text-yellow-800 mb-1">Course Has Active Enrollments</h4>
                                <p class="text-sm text-yellow-700">
                                    This course has {{ $course->enrollments()->count() }} enrollment(s). 
                                    Changes to dates, pricing, or status may affect enrolled students.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form Actions -->
                <div class="flex justify-between pt-6 border-t border-gray-200">
                    <div class="flex gap-4">
                        <a href="{{ route('admin.courses.show', $course) }}" 
                           class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </a>
                        @if($course->enrollments()->count() === 0)
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" 
                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                                    <span class="material-icons text-sm">delete</span>
                                    Delete Course
                                </button>
                            </form>
                        @endif
                    </div>
                    <button type="submit" 
                            class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">save</span>
                        Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection