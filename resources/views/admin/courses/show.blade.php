@extends('layouts.admin')

@section('title', $course->course_name)

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-red-100 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <a href="{{ route('admin.courses.index') }}" class="text-red-600 hover:text-red-700 transition-colors">
                        <span class="material-icons text-3xl">arrow_back</span>
                    </a>
                    {{ $course->course_name }}
                </h1>
                <div class="flex items-center gap-4 mt-1">
                    <p class="text-gray-600">{{ $course->course_code }}</p>
                    @if($course->status === 'active')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    @elseif($course->status === 'inactive')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            Inactive
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Draft
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.courses.edit', $course) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <span class="material-icons text-sm">edit</span>
                    Edit Course
                </a>
                <form method="POST" action="{{ route('admin.courses.duplicate', $course) }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">content_copy</span>
                        Duplicate
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.courses.toggle-status', $course) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors flex items-center gap-2">
                        <span class="material-icons text-sm">{{ $course->status === 'active' ? 'pause' : 'play_arrow' }}</span>
                        {{ $course->status === 'active' ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="px-6 py-4 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Enrolled</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_enrolled'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-blue-600">group</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Students</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['active_enrolled'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-green-600">school</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['completed'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-purple-600">check_circle</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dropped</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['dropped'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-red-600">cancel</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Available Slots</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $stats['available_slots'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-indigo-600">event_seat</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['completion_rate'] }}%</p>
                    </div>
                    <span class="material-icons text-3xl text-orange-600">trending_up</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Course Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Course Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Course Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Course Code</label>
                                    <p class="text-gray-900">{{ $course->course_code }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Instructor</label>
                                    <p class="text-gray-900">{{ $course->instructor ?? 'Not assigned' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Credits</label>
                                    <p class="text-gray-900">{{ $course->credits }} Credit{{ $course->credits > 1 ? 's' : '' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                                    <p class="text-gray-900">{{ $course->category ?? 'Uncategorized' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Price</label>
                                    <p class="text-gray-900 font-semibold">{{ $course->formatted_price ?? 'Free' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Max Students</label>
                                    <p class="text-gray-900">{{ $course->max_students ?? 'Unlimited' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Duration</label>
                                    <p class="text-gray-900">{{ $course->duration }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Schedule</label>
                                    @if($course->start_date && $course->end_date)
                                        <p class="text-gray-900">
                                            {{ $course->start_date->format('M j, Y') }} - {{ $course->end_date->format('M j, Y') }}
                                        </p>
                                    @else
                                        <p class="text-gray-500">Not scheduled</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Description</label>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-900 leading-relaxed">{{ $course->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-800">Recent Enrollments</h3>
                        <a href="{{ route('admin.enrollments.index', ['course_id' => $course->id]) }}" 
                           class="text-red-600 hover:text-red-700 text-sm font-medium">View All</a>
                    </div>
                    
                    @if($course->enrollments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enrolled Date</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($course->enrollments->take(10) as $enrollment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-xs font-medium text-blue-600">
                                                        {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $enrollment->student->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ $enrollment->enrolled_on->format('M j, Y') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if($enrollment->status === 'enrolled')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Enrolled
                                                </span>
                                            @elseif($enrollment->status === 'completed')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                            @elseif($enrollment->status === 'dropped')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Dropped
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[80px]">
                                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $enrollment->progress_percentage }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600">{{ $enrollment->progress_percentage }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <span class="material-icons text-4xl text-gray-300 mb-2">group_off</span>
                            <p class="text-gray-500">No enrollments yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Course Image -->
                @if($course->image_url)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <img src="{{ Storage::url($course->image_url) }}" alt="{{ $course->course_name }}" 
                             class="w-full h-48 object-cover rounded-lg">
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">Quick Actions</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <a href="{{ route('admin.enrollments.create') }}?course_id={{ $course->id }}" 
                           class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 justify-center">
                            <span class="material-icons text-sm">person_add</span>
                            Enroll Student
                        </a>
                        <a href="{{ route('admin.enrollments.index', ['course_id' => $course->id]) }}" 
                           class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 justify-center">
                            <span class="material-icons text-sm">list</span>
                            View All Enrollments
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" 
                           class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2 justify-center">
                            <span class="material-icons text-sm">edit</span>
                            Edit Course
                        </a>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">Enrollment Overview</h3>
                    </div>
                    <div class="p-4">
                        @if($course->max_students)
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span>Capacity</span>
                                    <span>{{ $stats['total_enrolled'] }} / {{ $course->max_students }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-600 h-2 rounded-full" 
                                         style="width: {{ $course->max_students > 0 ? ($stats['total_enrolled'] / $course->max_students) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Active Students</span>
                                <span class="text-sm font-medium text-green-600">{{ $stats['active_enrolled'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Completed</span>
                                <span class="text-sm font-medium text-purple-600">{{ $stats['completed'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Dropped</span>
                                <span class="text-sm font-medium text-red-600">{{ $stats['dropped'] }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-sm font-medium text-gray-800">Completion Rate</span>
                                <span class="text-sm font-bold text-orange-600">{{ $stats['completion_rate'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif
@endsection