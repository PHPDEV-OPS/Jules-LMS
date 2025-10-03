@extends('layouts.dashboard')

@section('title', 'Student Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Welcome back, {{ $student->name }}!</h1>
                <p class="mt-2 text-blue-100">Continue your learning journey and achieve your goals</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold">{{ $totalEnrollments }}</div>
                <div class="text-blue-100 text-sm">Course{{ $totalEnrollments !== 1 ? 's' : '' }} Enrolled</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Active Courses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">school</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Courses</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $activeEnrollments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('student.courses.index') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        View all courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $completedEnrollments }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('student.certificates.index') }}" class="font-medium text-green-600 hover:text-green-500">
                        View certificates
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Assessments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">assignment</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $pendingAssessments ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('student.assessments.index') }}" class="font-medium text-yellow-600 hover:text-yellow-500">
                        View assessments
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">notifications</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New Notifications</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $unreadNotifications ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('student.notifications.index') }}" class="font-medium text-purple-600 hover:text-purple-500">
                        View notifications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Enrollments -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">My Recent Courses</h2>
                    <a href="{{ route('student.courses.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        View All
                    </a>
                </div>
            </div>
            <div class="px-6 py-6">
                @forelse($recentEnrollments->take(3) as $enrollment)
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex-shrink-0">
                        @if($enrollment->status === 'active')
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-blue-600">play_circle_filled</span>
                            </div>
                        @elseif($enrollment->status === 'completed')
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-green-600">verified</span>
                            </div>
                        @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-gray-600">school</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $enrollment->course->title }}
                        </p>
                        <p class="text-xs text-gray-500">
                            Enrolled {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : 'recently' }}
                        </p>
                        @if($enrollment->status === 'active')
                            <div class="flex items-center mt-1">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                    @php $progress = rand(20, 80); @endphp
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="ml-2 text-xs text-gray-500">{{ $progress }}%</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                            View
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <span class="material-icons text-4xl text-gray-400">school</span>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No enrollments</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by enrolling in a course.</p>
                    <div class="mt-6">
                        <a href="{{ route('courses.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-2 text-sm">explore</span>
                            Browse Courses
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activity & Announcements -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Recent Announcements</h2>
                    <a href="{{ route('student.announcements.index') }}" class="text-sm text-purple-600 hover:text-purple-500">
                        View All
                    </a>
                </div>
            </div>
            <div class="px-6 py-6">
                @php
                    $announcements = \App\Models\Announcement::where('is_active', true)
                        ->latest()
                        ->take(3)
                        ->get();
                @endphp
                
                @forelse($announcements as $announcement)
                <div class="flex items-start space-x-3 mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-{{ $announcement->priority === 'high' ? 'red' : ($announcement->priority === 'normal' ? 'blue' : 'gray') }}-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-{{ $announcement->priority === 'high' ? 'red' : ($announcement->priority === 'normal' ? 'blue' : 'gray') }}-600 text-sm">
                                {{ $announcement->type === 'announcement' ? 'campaign' : 'info' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 mb-1">
                            {{ $announcement->title }}
                        </p>
                        <p class="text-xs text-gray-500 line-clamp-2">
                            {{ Str::limit($announcement->content, 100) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $announcement->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <span class="material-icons text-4xl text-gray-400">campaign</span>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                    <p class="mt-1 text-sm text-gray-500">All caught up! Check back later.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upcoming Assessments & Available Courses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Assessments -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Upcoming Assessments</h2>
                    <a href="{{ route('student.assessments.index') }}" class="text-sm text-yellow-600 hover:text-yellow-500">
                        View All
                    </a>
                </div>
            </div>
            <div class="px-6 py-6">
                @php
                    // Mock upcoming assessments - replace with actual data
                    $upcomingAssessments = collect([
                        (object)[
                            'id' => 1,
                            'title' => 'Web Development Quiz',
                            'course' => 'Introduction to Web Development',
                            'due_date' => now()->addDays(3),
                            'type' => 'quiz'
                        ],
                        (object)[
                            'id' => 2,
                            'title' => 'Final Project Submission',
                            'course' => 'Advanced JavaScript',
                            'due_date' => now()->addWeek(),
                            'type' => 'assignment'
                        ]
                    ]);
                @endphp
                
                @forelse($upcomingAssessments as $assessment)
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="material-icons text-yellow-600 text-sm">
                                    {{ $assessment->type === 'quiz' ? 'quiz' : 'assignment' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $assessment->title }}</p>
                            <p class="text-xs text-gray-500">{{ $assessment->course }}</p>
                            <p class="text-xs text-yellow-600">Due {{ $assessment->due_date->format('M j, g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('student.assessments.show', $assessment->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-yellow-600 hover:bg-yellow-700">
                            Start
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <span class="material-icons text-4xl text-gray-400">assignment</span>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No upcoming assessments</h3>
                    <p class="mt-1 text-sm text-gray-500">You're all caught up!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Available Courses -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-gray-900">Available Courses</h2>
                    <a href="{{ route('courses.index') }}" class="text-sm text-green-600 hover:text-green-500">
                        Browse All
                    </a>
                </div>
            </div>
            <div class="px-6 py-6">
                @forelse($popularCourses->take(3) as $course)
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="w-12 h-12 rounded-lg object-cover" 
                                 src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=100&h=100&fit=crop&crop=center" 
                                 alt="Course thumbnail">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $course->title }}</p>
                            <p class="text-xs text-gray-500">{{ $course->enrollments_count ?? 0 }} students</p>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-icons text-xs text-yellow-400">star</span>
                                @endfor
                                <span class="ml-1 text-xs text-gray-500">4.8</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <form method="POST" action="{{ route('student.enroll') }}" class="inline">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                Enroll
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <span class="material-icons text-4xl text-gray-400">library_books</span>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No courses available</h3>
                    <p class="mt-1 text-sm text-gray-500">Check back later for new courses.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection