@extends('layouts.admin')

@section('title', $category->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <span class="material-icons text-3xl" style="color: {{ $category->color }}">{{ $category->icon }}</span>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">Category details and associated courses</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit
            </a>
            <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to Categories
            </a>
        </div>
    </div>

    <!-- Category Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Category Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about this category</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->name }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->description ?: 'No description provided' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Inactive
                            </span>
                        @endif
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Total Courses</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $category->courses->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Associated Courses -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Associated Courses</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Courses in this category</p>
        </div>
        @if($category->courses->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($category->courses as $course)
                    @php
                        $courseModel = \App\Models\Course::where('title', $course->course_name)->first();
                    @endphp
                    <li class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($courseModel && $courseModel->image)
                                    <img src="{{ $courseModel->image }}" alt="{{ $course->course_name }}" class="w-10 h-10 rounded-lg object-cover">
                                @elseif($courseModel)
                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="material-icons text-white text-sm">{{ $courseModel->fallback_icon }}</span>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-300 flex items-center justify-center">
                                        <span class="material-icons text-gray-600 text-sm">school</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">{{ $course->course_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $course->enrollments_count }} enrollments</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="px-4 py-8 text-center">
                <span class="material-icons text-4xl text-gray-300">school</span>
                <p class="mt-2 text-sm text-gray-500">No courses in this category yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection