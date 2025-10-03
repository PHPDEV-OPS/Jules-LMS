@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Reports
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Generate and export detailed reports for analysis.
            </p>
        </div>
    </div>

    <!-- Report Generator -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Generate Report</h3>
            <form method="GET" action="{{ route('admin.reports') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Report Type</label>
                        <select name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                            <option value="overview" {{ request('type') === 'overview' ? 'selected' : '' }}>Overview</option>
                            <option value="enrollments" {{ request('type') === 'enrollments' ? 'selected' : '' }}>Enrollments</option>
                            <option value="performance" {{ request('type') === 'performance' ? 'selected' : '' }}>Performance</option>
                            <option value="revenue" {{ request('type') === 'revenue' ? 'selected' : '' }}>Revenue</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date', now()->format('Y-m-d')) }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                        <span class="material-icons mr-2 text-sm">assessment</span>
                        Generate Report
                    </button>
                    <button type="submit" name="format" value="pdf" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons mr-2 text-sm">picture_as_pdf</span>
                        Export as PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(isset($report))
    <!-- Report Results -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ ucfirst($reportType) }} Report
                </h3>
                <span class="text-sm text-gray-500">{{ $report['period'] ?? '' }}</span>
            </div>

            @if($reportType === 'overview')
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-blue-600">New Students</dt>
                        <dd class="text-2xl font-bold text-blue-900">{{ $report['students'] ?? 0 }}</dd>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-green-600">New Courses</dt>
                        <dd class="text-2xl font-bold text-green-900">{{ $report['courses'] ?? 0 }}</dd>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-purple-600">New Enrollments</dt>
                        <dd class="text-2xl font-bold text-purple-900">{{ $report['enrollments'] ?? 0 }}</dd>
                    </div>
                    <div class="bg-orange-50 p-4 rounded-lg">
                        <dt class="text-sm font-medium text-orange-600">Completions</dt>
                        <dd class="text-2xl font-bold text-orange-900">{{ $report['completions'] ?? 0 }}</dd>
                    </div>
                </div>
            @endif

            @if($reportType === 'enrollments' && isset($report['by_course']))
                <div class="mt-6">
                    <h4 class="text-md font-medium text-gray-900 mb-3">Enrollments by Course</h4>
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Enrollments</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($report['by_course'] as $course)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->course_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $course->new_enrollments }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Quick Report Links -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Reports</h3>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <a href="{{ route('enrollments.export') }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">download</span>
                    Export All Enrollments
                </a>
                <a href="{{ route('admin.reports', ['type' => 'overview', 'start_date' => now()->startOfMonth()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">calendar_month</span>
                    This Month's Overview
                </a>
                <a href="{{ route('admin.reports', ['type' => 'performance', 'start_date' => now()->startOfYear()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <span class="material-icons mr-2 text-sm">trending_up</span>
                    Year Performance
                </a>
            </div>
        </div>
    </div>
</div>
@endsection