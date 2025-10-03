@extends('layouts.admin')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Analytics Dashboard
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Comprehensive analytics and insights for your learning management system.
            </p>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-2xl text-blue-500">people</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['overview']['total_students'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-2xl text-green-500">school</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Courses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['overview']['active_courses'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-2xl text-purple-500">assignment</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Enrollments</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['overview']['total_enrollments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-2xl text-orange-500">trending_up</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $analytics['overview']['completion_rate'] }}%</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Trends Chart -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Monthly Trends</h3>
                <div class="h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <span class="material-icons text-4xl mb-2">insert_chart</span>
                        <p class="text-sm">Enrollment & completion trends chart would be here</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Top Performing Courses</h3>
                <div class="space-y-3">
                    @foreach($analytics['performance']['top_courses']->take(5) as $course)
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $course->course_name }}</p>
                                <p class="text-xs text-gray-500">{{ $course->enrollments_count }} enrollments</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ min($course->enrollments_count * 10, 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Analytics Actions</h3>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <a href="{{ route('admin.reports', ['type' => 'overview']) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">assessment</span>
                    Overview Report
                </a>
                <a href="{{ route('admin.reports', ['type' => 'enrollments']) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">assignment</span>
                    Enrollment Report
                </a>
                <a href="{{ route('admin.reports', ['type' => 'performance']) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">trending_up</span>
                    Performance Report
                </a>
                <a href="{{ route('enrollments.export') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm bg-red-600 text-sm font-medium text-white hover:bg-red-700">
                    <span class="material-icons mr-2 text-sm">download</span>
                    Export Data
                </a>
            </div>
        </div>
    </div>
</div>
@endsection