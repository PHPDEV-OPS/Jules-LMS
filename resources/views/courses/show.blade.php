@extends('layouts.dashboard')

@section('title', $course->title)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('courses.index') }}" class="text-gray-400 hover:text-gray-500">
                    <span class="material-icons">home</span>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mx-2">chevron_right</span>
                    <a href="{{ route('courses.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Courses</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mx-2">chevron_right</span>
                    <span class="text-sm font-medium text-gray-900">{{ $course->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Course Header -->
            <div class="bg-white shadow sm:rounded-lg overflow-hidden">
                <!-- Hero Image -->
                <div class="h-64 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <div class="text-center text-white">
                        <span class="material-icons text-8xl mb-4">school</span>
                        <div class="text-sm font-medium px-3 py-1 bg-white bg-opacity-20 rounded-full inline-block">
                            {{ ucfirst($course->level ?? 'Intermediate') }} Level
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $course->title }}</h1>
                            <p class="mt-1 text-lg text-gray-600">{{ $course->course_code }}</p>
                        </div>
                        @if($course->credits)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $course->credits }} Credits
                            </span>
                        @endif
                    </div>

                    <!-- Course Meta Info -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @if($course->instructor)
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-3">person</span>
                                <div>
                                    <div class="text-sm text-gray-500">Instructor</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $course->instructor }}</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($course->start_date)
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-3">event</span>
                                <div>
                                    <div class="text-sm text-gray-500">Start Date</div>
                                    <div class="text-sm font-medium text-gray-900">{{ $course->start_date->format('M j, Y') }}</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex items-center">
                            <span class="material-icons text-gray-400 mr-3">people</span>
                            <div>
                                <div class="text-sm text-gray-500">Enrollment</div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $course->enrollments_count ?? 0 }}
                                    @if($course->max_capacity)
                                        / {{ $course->max_capacity }} students
                                    @else
                                        students enrolled
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Course Description</h3>
                    <div class="text-gray-700 prose max-w-none">
                        @if($course->description)
                            {!! nl2br(e($course->description)) !!}
                        @else
                            <p class="text-gray-500 italic">No description available for this course.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Prerequisites -->
            @if($course->prerequisites)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Prerequisites</h3>
                        <div class="text-gray-700">
                            {!! nl2br(e($course->prerequisites)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Course Objectives -->
            @if($course->objectives)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Learning Objectives</h3>
                        <div class="text-gray-700">
                            {!! nl2br(e($course->objectives)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recent Activity -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @php
                                $recentEnrollments = $course->enrollments()->with('student')->latest()->take(5)->get();
                            @endphp
                            
                            @forelse($recentEnrollments as $enrollment)
                                <li>
                                    <div class="relative pb-8 {{ !$loop->last ? '' : 'pb-0' }}">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <span class="material-icons text-white text-sm">person_add</span>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">
                                                        <span class="font-medium text-gray-900">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</span>
                                                        enrolled in this course
                                                    </p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->diffForHumans() : 'Recently' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-4 text-gray-500">No recent activity</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Enrollment Card -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    @auth('student')
                        @php
                            $enrollment = auth()->guard('student')->user()->enrollments()->where('course_id', $course->id)->first();
                        @endphp
                        
                        @if($enrollment)
                            <!-- Already Enrolled -->
                            <div class="text-center">
                                @if($enrollment->status === 'active')
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <span class="material-icons text-sm mr-1">check_circle</span>
                                            Enrolled
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">You are enrolled in this course</p>
                                    <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                       class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <span class="material-icons mr-2">launch</span>
                                        Continue Learning
                                    </a>
                                @elseif($enrollment->status === 'completed')
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                            <span class="material-icons text-sm mr-1">verified</span>
                                            Completed
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">You have completed this course</p>
                                    <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                       class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <span class="material-icons mr-2">visibility</span>
                                        View Certificate
                                    </a>
                                @elseif($enrollment->status === 'dropped')
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <span class="material-icons text-sm mr-1">cancel</span>
                                            Dropped
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">You have dropped this course</p>
                                    @if(!$course->max_capacity || ($course->enrollments_count ?? 0) < $course->max_capacity)
                                        <form action="{{ route('student.enroll') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" 
                                                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                <span class="material-icons mr-2">refresh</span>
                                                Re-enroll
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        @else
                            <!-- Not Enrolled -->
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Ready to start learning?</h3>
                                
                                @if($course->max_capacity && ($course->enrollments_count ?? 0) >= $course->max_capacity)
                                    <div class="mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                            <span class="material-icons text-sm mr-1">people</span>
                                            Course Full
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-4">This course has reached maximum capacity</p>
                                    <button disabled 
                                            class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-500 bg-gray-100 cursor-not-allowed">
                                        <span class="material-icons mr-2">block</span>
                                        Enrollment Full
                                    </button>
                                @else
                                    <p class="text-sm text-gray-600 mb-6">Join {{ $course->enrollments_count ?? 0 }} other students in this course</p>
                                    <form action="{{ route('student.enroll') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <button type="submit" 
                                                class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <span class="material-icons mr-2">add</span>
                                            Enroll Now
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @else
                        <!-- Not Logged In -->
                        <div class="text-center">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Join this course</h3>
                            <p class="text-sm text-gray-600 mb-6">Create an account or sign in to enroll</p>
                            <div class="space-y-3">
                                <a href="{{ route('student.register') }}" 
                                   class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <span class="material-icons mr-2">person_add</span>
                                    Sign Up
                                </a>
                                <a href="{{ route('login') }}" 
                                   class="w-full inline-flex justify-center items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <span class="material-icons mr-2">login</span>
                                    Sign In
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Course Details -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Course Details</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course Code</dt>
                            <dd class="text-sm text-gray-900">{{ $course->course_code }}</dd>
                        </div>
                        
                        @if($course->credits)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Credits</dt>
                                <dd class="text-sm text-gray-900">{{ $course->credits }}</dd>
                            </div>
                        @endif
                        
                        @if($course->duration)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duration</dt>
                                <dd class="text-sm text-gray-900">{{ $course->duration }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Level</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($course->level ?? 'Intermediate') }}</dd>
                        </div>
                        
                        @if($course->category)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('-', ' ', $course->category)) }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $course->created_at->format('M j, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Share Course -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Share Course</h3>
                    <div class="flex space-x-3">
                        <button onclick="copyToClipboard()" 
                                class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">link</span>
                            Copy Link
                        </button>
                        <a href="mailto:?subject={{ urlencode($course->title) }}&body={{ urlencode('Check out this course: ' . request()->url()) }}" 
                           class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">mail</span>
                            Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="material-icons mr-2 text-sm">check</span>Copied!';
        button.classList.add('bg-green-50', 'text-green-700', 'border-green-300');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-50', 'text-green-700', 'border-green-300');
        }, 2000);
    });
}
</script>
@endsection