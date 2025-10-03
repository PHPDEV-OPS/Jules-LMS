@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Admin Dashboard
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Welcome back, {{ $user->name }}! Here's what's happening in your LMS.
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <button type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <span class="material-icons mr-2 text-sm">download</span>
                Export Report
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Students -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">people</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_students']) }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +12% from last month
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('students.index') }}" class="font-medium text-blue-700 hover:text-blue-900">
                        View all students
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Courses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">school</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Courses</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_courses']) }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +8% from last month
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('admin.courses.index') }}" class="font-medium text-green-700 hover:text-green-900">
                        View all courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Enrollments -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">assignment</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Enrollments</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['active_enrollments']) }}</div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +15% from last month
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <a href="{{ route('enrollments.index') }}" class="font-medium text-purple-700 hover:text-purple-900">
                        View all enrollments
                    </a>
                </div>
            </div>
        </div>

        <!-- Completion Rate -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completion Rate</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ $stats['total_enrollments'] > 0 ? round(($stats['completed_enrollments'] / $stats['total_enrollments']) * 100, 1) : 0 }}%
                                </div>
                                <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                    <span class="material-icons text-xs">trending_up</span>
                                    <span class="sr-only"> Increased by </span>
                                    +3% from last month
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-5 py-3">
                <div class="text-sm">
                    <span class="font-medium text-orange-700">{{ $stats['completed_enrollments'] }} completed courses</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <!-- Left Column - Charts and Recent Activity -->
        <div class="lg:col-span-8">
            <!-- Recent Enrollments Table -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Recent Enrollments</h3>
                    <div class="flow-root">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($recentEnrollments as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                                        <span class="text-xs font-medium text-white">{{ substr($enrollment->student->first_name, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $enrollment->student->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $enrollment->course->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($enrollment->course->description, 50) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($enrollment->status === 'active')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                            @elseif($enrollment->status === 'completed')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ ucfirst($enrollment->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('enrollments.show', $enrollment) }}" class="text-red-600 hover:text-red-900">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No recent enrollments found.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('enrollments.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Enrollments
                        </a>
                    </div>
                </div>
            </div>

            <!-- Chart Placeholder -->
            <div class="mt-6 bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Enrollment Trends</h3>
                    <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-500">
                            <span class="material-icons text-4xl mb-2">trending_up</span>
                            <p class="text-sm">Chart visualization would go here</p>
                            <p class="text-xs text-gray-400">Monthly enrollment and completion trends</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Quick Stats and Actions -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Popular Courses -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Popular Courses</h3>
                    <div class="space-y-3">
                        @forelse($popularCourses as $course)
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $course->title }}</p>
                                <p class="text-xs text-gray-500">{{ $course->enrollments_count }} enrollments</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <span class="text-sm font-medium text-gray-900">{{ $course->enrollments_count }}</span>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">No courses available.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.courses.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Courses
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">New Students</h3>
                    <div class="space-y-3">
                        @forelse($recentStudents as $student)
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-medium">{{ substr($student->first_name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $student->first_name }} {{ $student->last_name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">No recent students.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('students.index') }}" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Students
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('students.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-2 text-sm">person_add</span>
                            Add New Student
                        </a>
                        <a href="{{ route('admin.courses.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            <span class="material-icons mr-2 text-sm">add</span>
                            Create New Course
                        </a>
                        <a href="{{ route('enrollments.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                            <span class="material-icons mr-2 text-sm">assignment</span>
                            Manual Enrollment
                        </a>
                        <a href="{{ route('enrollments.export') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">download</span>
                            Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add any JavaScript for admin dashboard functionality
    console.log('Admin Dashboard Loaded');
    console.log('Admin Stats:', @json($stats));
</script>
@endpush
@endsection