@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $student->first_name }}!</h1>
                    <p class="mt-1 text-gray-600">{{ config('app.name', 'LMS') }} Dashboard</p>
                </div>
                <form method="POST" action="{{ route('student.logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="material-icons mr-2">logout</span>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">school</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Enrolled Courses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $enrollments->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">trending_up</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Available Courses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $availableCourses->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">person</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Profile</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $student->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Enrollments -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">My Enrollments</h2>
                </div>
                <div class="p-6">
                    @if($enrollments->count() > 0)
                        <div class="space-y-4">
                            @foreach($enrollments as $enrollment)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div>
                                        <h3 class="font-medium text-gray-900">{{ $enrollment->course->title }}</h3>
                                        <p class="text-sm text-gray-500">Enrolled: {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y') : 'N/A' }}</p>
                                    </div>
                                    @if($enrollment->status === 'active')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    @elseif($enrollment->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    @endif
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($enrollments->hasPages())
                            <div class="mt-4">
                                {{ $enrollments->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <span class="material-icons text-gray-400 text-4xl mb-2">school</span>
                            <p class="text-gray-500">No enrollments yet</p>
                            <p class="text-sm text-gray-400">Explore available courses to get started</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Available Courses -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Available Courses</h2>
                </div>
                <div class="p-6">
                    @if($availableCourses->count() > 0)
                        <div class="space-y-4">
                            @foreach($availableCourses->take(5) as $course)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                    <div class="flex-1">
                                        <h3 class="font-medium text-gray-900">{{ $course->title }}</h3>
                                        <p class="text-sm text-gray-500">{{ Str::limit($course->description, 60) }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('student.enroll') }}" class="ml-4">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Enroll
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        
                        @if($availableCourses->count() > 5)
                            <div class="mt-4 text-center">
                                <a href="{{ route('courses.index') }}" class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                    View all courses →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-8">
                            <span class="material-icons text-gray-400 text-4xl mb-2">library_books</span>
                            <p class="text-gray-500">No available courses</p>
                            <p class="text-sm text-gray-400">Check back later for new courses</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
@endpush
@endsection