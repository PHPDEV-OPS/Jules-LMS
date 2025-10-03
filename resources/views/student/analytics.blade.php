@extends('layouts.dashboard')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Analytics Dashboard</h1>
        <p class="mt-1 text-sm text-gray-600">Welcome back, {{ $student->first_name }}! Here's what's happening with your learning journey.</p>
    </div>

    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Enrollments Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">school</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Enrollments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($totalEnrollments) }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +55% than last week
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Courses Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">play_circle_filled</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Today's Active</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($activeEnrollments) }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +2% than last month
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Card (Placeholder) -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">attach_money</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Learning Hours</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ $completedEnrollments * 15 }}h</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +1% than yesterday
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Followers Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">people</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Certificates</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">+{{ $completedEnrollments }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    Just updated
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left Column - Charts and Stats -->
        <div class="lg:col-span-8">
            <!-- Sales by Country Table -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Learning Progress by Category</h3>
                    <div class="flow-root">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollments</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($analyticsData['sales_by_country'] as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-6 w-6">
                                                    @if($loop->index == 0)
                                                        <span class="material-icons text-green-500">verified</span>
                                                    @elseif($loop->index == 1)
                                                        <span class="material-icons text-blue-500">play_circle_filled</span>
                                                    @elseif($loop->index == 2)
                                                        <span class="material-icons text-purple-500">library_books</span>
                                                    @else
                                                        <span class="material-icons text-gray-500">assessment</span>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item['country'] }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($item['sales']) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['value'] ?: ($item['sales'] * 15) . 'h' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['bounce'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Placeholder -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Learning Activity</h3>
                    <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <span class="material-icons text-4xl mb-2">bar_chart</span>
                            <p class="text-sm">Chart visualization would go here</p>
                            <p class="text-xs text-gray-400">Integration with Chart.js or similar library</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Course Cards -->
        <div class="lg:col-span-4">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Available Courses</h3>
                    <div class="space-y-4">
                        @forelse($popularCourses->take(3) as $course)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80" alt="Course">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">{{ $course->title }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($course->description, 60) }}</p>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="text-xs text-green-600 font-medium">Available</span>
                                        <form method="POST" action="{{ route('student.enroll') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-500 font-medium">
                                                Enroll Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            <span class="material-icons text-2xl mb-2">school</span>
                            <p class="text-sm">No courses available</p>
                        </div>
                        @endforelse
                    </div>
                    
                    @if($popularCourses->count() > 3)
                    <div class="mt-4">
                        <a href="{{ route('courses.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Courses
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- My Enrollments -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">My Enrollments</h3>
                        <a href="{{ route('courses.index') }}" class="text-sm text-blue-600 hover:text-blue-500">View All Courses</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($recentEnrollments as $enrollment)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($enrollment->status === 'active')
                                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-white text-sm">play_circle_filled</span>
                                            </div>
                                        @elseif($enrollment->status === 'completed')
                                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-white text-sm">verified</span>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-400 rounded-full flex items-center justify-center">
                                                <span class="material-icons text-white text-sm">school</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $enrollment->course->title }}</p>
                                        <p class="text-xs text-gray-500">{{ $enrollment->course->course_code }} • Enrolled {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y') : 'N/A' }}</p>
                                        @if($enrollment->course->instructor)
                                            <p class="text-xs text-gray-400">Instructor: {{ $enrollment->course->instructor }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-3">
                                    <div class="text-right">
                                        @if($enrollment->status === 'active')
                                            @php
                                                $progress = rand(25, 85); // Mock progress
                                            @endphp
                                            <div class="text-xs text-gray-500 mb-1">Progress</div>
                                            <div class="flex items-center space-x-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium text-gray-900">{{ $progress }}%</span>
                                            </div>
                                        @elseif($enrollment->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <span class="material-icons text-xs mr-1">verified</span>
                                                Completed
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50">
                                            <span class="material-icons text-xs mr-1">visibility</span>
                                            View
                                        </a>
                                        
                                        @if($enrollment->status === 'active')
                                            <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                                <span class="material-icons text-xs mr-1">play_arrow</span>
                                                Continue
                                            </a>
                                        @elseif($enrollment->status === 'completed')
                                            <a href="{{ route('student.enrollment.details', $enrollment) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent shadow-sm text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700">
                                                <span class="material-icons text-xs mr-1">file_download</span>
                                                Certificate
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-8">
                            <span class="material-icons text-4xl mb-3 text-gray-400">school</span>
                            <h4 class="text-sm font-medium text-gray-900 mb-1">No enrollments yet</h4>
                            <p class="text-xs text-gray-500 mb-4">Start your learning journey by enrolling in a course</p>
                            <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <span class="material-icons mr-2 text-sm">explore</span>
                                Browse Courses
                            </a>
                        </div>
                        @endforelse
                    </div>
                    
                    @if($recentEnrollments->count() >= 5)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('courses.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">list</span>
                            View All My Courses
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any JavaScript for charts or interactive elements here
    console.log('Analytics Dashboard Loaded');
    console.log('Student Data:', {
        totalEnrollments: {{ $totalEnrollments }},
        activeEnrollments: {{ $activeEnrollments }},
        completedEnrollments: {{ $completedEnrollments }},
        availableCourses: {{ $availableCourses }}
    });
</script>
@endpush
@endsection