@extends('layouts.dashboard')

@section('title', 'Practice Tests')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Practice Tests
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Practice your knowledge without affecting your grades
            </p>
        </div>
    </div>

    @if($enrolledCourses->isEmpty())
        <div class="text-center py-12">
            <i class="material-icons text-gray-400 text-6xl mb-4">school</i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Courses Available</h3>
            <p class="text-gray-500 mb-4">You need to enroll in courses to access practice tests.</p>
            <a href="{{ route('student.courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Browse Courses
            </a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($enrolledCourses as $enrollment)
                @if($enrollment->course->assessments->isNotEmpty())
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $enrollment->course->title }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ $enrollment->course->assessments->count() }} practice test(s) available
                            </p>
                        </div>
                        <div class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($enrollment->course->assessments as $assessment)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between mb-3">
                                            <h4 class="text-sm font-medium text-gray-900 leading-tight">
                                                {{ $assessment->title }}
                                            </h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Practice
                                            </span>
                                        </div>
                                        
                                        @if($assessment->description)
                                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                                {{ $assessment->description }}
                                            </p>
                                        @endif

                                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                            <div class="flex items-center">
                                                <i class="material-icons text-xs mr-1">quiz</i>
                                                {{ $assessment->questions->count() }} questions
                                            </div>
                                            @if($assessment->duration_minutes)
                                                <div class="flex items-center">
                                                    <i class="material-icons text-xs mr-1">schedule</i>
                                                    {{ $assessment->duration_minutes }} min
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex space-x-2">
                                            <a href="{{ route('student.practice-tests.show', $assessment) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="material-icons text-sm mr-1">info</i>
                                                Details
                                            </a>
                                            <a href="{{ route('student.practice-tests.start', $assessment) }}" 
                                               class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                                <i class="material-icons text-sm mr-1">play_arrow</i>
                                                Start
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        @if($enrolledCourses->every(fn($enrollment) => $enrollment->course->assessments->isEmpty()))
            <div class="text-center py-12">
                <i class="material-icons text-gray-400 text-6xl mb-4">quiz</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Practice Tests Available</h3>
                <p class="text-gray-500 mb-4">Your enrolled courses don't have any assessments available for practice yet.</p>
            </div>
        @endif
    @endif
</div>
@endsection