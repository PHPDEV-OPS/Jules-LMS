@extends('layouts.dashboard')

@section('title', 'Course Catalog')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Course Catalog
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Explore and enroll in available courses to enhance your learning journey.
            </p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white p-4 shadow rounded-lg">
        <form method="GET" action="{{ route('courses.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-0">
                <label for="search" class="block text-sm font-medium text-gray-700">Search Courses</label>
                <div class="mt-1 relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Search by title, instructor, or description...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400">search</span>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                <select name="level" id="level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Levels</option>
                    <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    <option value="programming" {{ request('category') === 'programming' ? 'selected' : '' }}>Programming</option>
                    <option value="design" {{ request('category') === 'design' ? 'selected' : '' }}>Design</option>
                    <option value="business" {{ request('category') === 'business' ? 'selected' : '' }}>Business</option>
                    <option value="marketing" {{ request('category') === 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="data-science" {{ request('category') === 'data-science' ? 'selected' : '' }}>Data Science</option>
                </select>
            </div>
            
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                <select name="sort" id="sort" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
                    <option value="created_at" {{ request('sort', 'created_at') === 'created_at' ? 'selected' : '' }}>Newest First</option>
                    <option value="enrollments_count" {{ request('sort') === 'enrollments_count' ? 'selected' : '' }}>Most Popular</option>
                    <option value="start_date" {{ request('sort') === 'start_date' ? 'selected' : '' }}>Start Date</option>
                </select>
            </div>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <span class="material-icons mr-2 text-sm">filter_list</span>
                Filter
            </button>
            
            @if(request()->hasAny(['search', 'level', 'category', 'sort']))
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Course Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">library_books</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available Courses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $courses->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">groups</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">My Enrollments</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ auth()->guard('student')->check() ? auth()->guard('student')->user()->enrollments()->count() : 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">play_circle_filled</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Courses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ auth()->guard('student')->check() ? auth()->guard('student')->user()->enrollments()->where('status', 'active')->count() : 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ auth()->guard('student')->check() ? auth()->guard('student')->user()->enrollments()->where('status', 'completed')->count() : 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($courses as $course)
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <!-- Course Image/Icon -->
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <div class="text-center text-white">
                        <span class="material-icons text-6xl mb-2">school</span>
                        <div class="text-xs font-medium px-2 py-1 bg-white bg-opacity-20 rounded-full inline-block">
                            {{ ucfirst($course->level ?? 'Intermediate') }}
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <!-- Course Header -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $course->title }}</h3>
                            <p class="text-sm text-gray-500">{{ $course->course_code }}</p>
                        </div>
                        @if($course->credits)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $course->credits }} credits
                            </span>
                        @endif
                    </div>

                    <!-- Course Description -->
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                        {{ Str::limit($course->description, 120) }}
                    </p>

                    <!-- Course Details -->
                    <div class="space-y-2 mb-4">
                        @if($course->instructor)
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-sm mr-2">person</span>
                                {{ $course->instructor }}
                            </div>
                        @endif
                        
                        @if($course->start_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-sm mr-2">event</span>
                                Starts {{ $course->start_date->format('M j, Y') }}
                            </div>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-600">
                            <span class="material-icons text-sm mr-2">people</span>
                            {{ $course->enrollments_count ?? 0 }} enrolled
                            @if($course->max_capacity)
                                / {{ $course->max_capacity }}
                            @endif
                        </div>
                    </div>

                    <!-- Enrollment Status -->
                    @auth('student')
                        @php
                            $enrollment = auth()->guard('student')->user()->enrollments()->where('course_id', $course->id)->first();
                        @endphp
                        
                        @if($enrollment)
                            <div class="mb-4">
                                @if($enrollment->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="material-icons text-xs mr-1">play_circle_filled</span>
                                        Enrolled
                                    </span>
                                @elseif($enrollment->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <span class="material-icons text-xs mr-1">verified</span>
                                        Completed
                                    </span>
                                @elseif($enrollment->status === 'dropped')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <span class="material-icons text-xs mr-1">cancel</span>
                                        Dropped
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endauth

                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('courses.show', $course) }}" 
                           class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">visibility</span>
                            View Details
                        </a>
                        
                        @auth('student')
                            @if(!$enrollment)
                                @if(!$course->max_capacity || ($course->enrollments_count ?? 0) < $course->max_capacity)
                                    <form action="{{ route('student.enroll') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <span class="material-icons mr-2 text-sm">add</span>
                                            Enroll
                                        </button>
                                    </form>
                                @else
                                    <span class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                        Course Full
                                    </span>
                                @endif
                            @elseif($enrollment->status === 'active')
                                <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                   class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <span class="material-icons mr-2 text-sm">launch</span>
                                    Continue
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons mr-2 text-sm">login</span>
                                Login to Enroll
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <div class="flex flex-col items-center">
                    <span class="material-icons text-6xl text-gray-400 mb-4">search_off</span>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->hasAny(['search', 'level', 'category']))
                            Try adjusting your search criteria or filters.
                        @else
                            There are no courses available at the moment.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'level', 'category']))
                        <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-900">
                            Clear all filters
                        </a>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            {{ $courses->links() }}
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection