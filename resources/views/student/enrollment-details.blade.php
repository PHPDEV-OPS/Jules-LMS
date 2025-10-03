@extends('layouts.dashboard')

@section('title', 'Enrollment Details - ' . $enrollment->course->title)

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-4">
            <li>
                <a href="{{ route('student.dashboard.analytics') }}" class="text-gray-400 hover:text-gray-500">
                    <span class="material-icons">home</span>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mx-2">chevron_right</span>
                    <a href="{{ route('student.dashboard.analytics') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mx-2">chevron_right</span>
                    <span class="text-sm font-medium text-gray-900">{{ $enrollment->course->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Course Header -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="h-48 bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                    <div class="text-center text-white">
                        <span class="material-icons text-6xl mb-2">school</span>
                        <div class="text-xs font-medium px-2 py-1 bg-white bg-opacity-20 rounded-full inline-block">
                            {{ ucfirst($enrollment->course->level ?? 'Intermediate') }} Level
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $enrollment->course->title }}</h1>
                            <p class="mt-1 text-sm text-gray-600">{{ $enrollment->course->course_code }}</p>
                        </div>
                        @if($enrollment->status === 'active')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <span class="material-icons text-sm mr-1">play_circle_filled</span>
                                Active
                            </span>
                        @elseif($enrollment->status === 'completed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <span class="material-icons text-sm mr-1">verified</span>
                                Completed
                            </span>
                        @elseif($enrollment->status === 'dropped')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <span class="material-icons text-sm mr-1">cancel</span>
                                Dropped
                            </span>
                        @endif
                    </div>

                    <div class="mt-4">
                        @if($enrollment->course->instructor)
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="material-icons text-sm mr-2">person</span>
                                Instructor: {{ $enrollment->course->instructor }}
                            </div>
                        @endif
                        <div class="flex items-center text-sm text-gray-600 mt-1">
                            <span class="material-icons text-sm mr-2">event</span>
                            Enrolled on {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('F j, Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Section -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Your Progress</h3>
                    
                    @php
                        $progress = $enrollment->status === 'completed' ? 100 : 
                                   ($enrollment->status === 'active' ? rand(25, 85) : 0);
                    @endphp
                    
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Course Progress</span>
                            <span>{{ $progress }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ rand(8, 15) }}</div>
                            <div class="text-sm text-blue-600">Lessons Completed</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ rand(5, 12) }}</div>
                            <div class="text-sm text-green-600">Assignments Done</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">{{ rand(20, 40) }}</div>
                            <div class="text-sm text-purple-600">Study Hours</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Course Content</h3>
                    
                    <!-- Mock course modules -->
                    <div class="space-y-3">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $moduleProgress = $i <= 3 ? 100 : ($i == 4 ? 60 : 0);
                                $isCompleted = $moduleProgress == 100;
                                $isInProgress = $moduleProgress > 0 && $moduleProgress < 100;
                            @endphp
                            
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        @if($isCompleted)
                                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-white text-sm">check</span>
                                            </div>
                                        @elseif($isInProgress)
                                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-white text-sm">play_arrow</span>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-gray-600 text-sm">play_arrow</span>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">Module {{ $i }}: Introduction to Core Concepts</h4>
                                            <p class="text-xs text-gray-500">{{ rand(3, 8) }} lessons • {{ rand(45, 120) }} minutes</p>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">{{ $moduleProgress }}%</div>
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $moduleProgress }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">About This Course</h3>
                    <div class="text-gray-700 prose max-w-none">
                        @if($enrollment->course->description)
                            {!! nl2br(e($enrollment->course->description)) !!}
                        @else
                            <p class="text-gray-500 italic">No description available for this course.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($enrollment->status === 'active')
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons mr-2 text-sm">play_arrow</span>
                                Continue Learning
                            </button>
                            
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <span class="material-icons mr-2 text-sm">download</span>
                                Download Resources
                            </button>
                            
                            <form action="{{ route('student.drop', $enrollment) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to drop this course?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    <span class="material-icons mr-2 text-sm">cancel</span>
                                    Drop Course
                                </button>
                            </form>
                        @elseif($enrollment->status === 'completed')
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <span class="material-icons mr-2 text-sm">file_download</span>
                                Download Certificate
                            </button>
                            
                            <button class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <span class="material-icons mr-2 text-sm">rate_review</span>
                                Rate Course
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Course Stats -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Course Statistics</h3>
                    <dl class="space-y-3">
                        @if($enrollment->course->credits)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Credits</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->course->credits }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Enrolled Students</dt>
                            <dd class="text-sm text-gray-900">{{ $enrollment->course->enrollments_count ?? $enrollment->course->enrollments()->count() }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Your Enrollment Date</dt>
                            <dd class="text-sm text-gray-900">{{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y') : 'N/A' }}</dd>
                        </div>
                        
                        @if($enrollment->completion_date)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Completion Date</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->completion_date->format('M j, Y') }}</dd>
                            </div>
                        @endif
                        
                        @if($enrollment->dropped_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Drop Date</dt>
                                <dd class="text-sm text-gray-900">{{ $enrollment->dropped_at->format('M j, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Instructor -->
            @if($enrollment->course->instructor)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-5">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Instructor</h3>
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-lg font-medium text-gray-600">
                                        {{ substr($enrollment->course->instructor, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->instructor }}</div>
                                <div class="text-sm text-gray-500">Course Instructor</div>
                            </div>
                        </div>
                        <button class="mt-4 w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">mail</span>
                            Send Message
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection