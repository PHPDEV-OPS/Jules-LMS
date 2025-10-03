@extends('layouts.dashboard')

@section('title', 'Create New Topic')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('student.forums.index') }}" class="text-gray-400 hover:text-gray-500">
                        <i class="material-icons text-sm">forum</i>
                        <span class="sr-only">Forums</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm mx-2">chevron_right</i>
                        <span class="text-sm font-medium text-gray-500">Create New Topic</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">Create New Topic</h1>
            <p class="text-sm text-gray-600 mt-1">Start a new discussion in the forums</p>
        </div>

        <form action="{{ route('student.forums.store-topic') }}" method="POST" class="px-6 py-6 space-y-6">
            @csrf
            
            <!-- Category Selection -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Category *
                </label>
                <select id="category_id" 
                        name="category_id" 
                        class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id || old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Course Selection (Optional) -->
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Related Course (Optional)
                </label>
                <select id="course_id" 
                        name="course_id" 
                        class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">No specific course</option>
                    @foreach($enrolledCourses as $enrollment)
                        <option value="{{ $enrollment->course->id }}" {{ old('course_id') == $enrollment->course->id ? 'selected' : '' }}>
                            {{ $enrollment->course->title }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Topic Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Topic Title *
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title') }}"
                       class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter a descriptive title for your topic"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Topic Content -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Content *
                </label>
                <textarea id="content" 
                          name="content" 
                          rows="12" 
                          class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Share your thoughts, ask questions, or start a discussion..."
                          required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Guidelines -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <i class="material-icons text-blue-500 mr-2 text-sm">info</i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 mb-1">Forum Guidelines</h4>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Be respectful and courteous to other members</li>
                            <li>• Stay on topic and provide constructive contributions</li>
                            <li>• Search existing topics before creating duplicates</li>
                            <li>• Use clear, descriptive titles for better visibility</li>
                            <li>• No spam, advertising, or inappropriate content</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <a href="{{ route('student.forums.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">arrow_back</i>
                    Back to Forums
                </a>
                
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="document.getElementById('content').value = ''; document.getElementById('title').value = '';" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="material-icons text-sm mr-2">refresh</i>
                        Clear
                    </button>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="material-icons text-sm mr-2">send</i>
                        Create Topic
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection