@extends('layouts.admin')

@section('title', 'Course Management')

@section('content')
<div class="flex-1 flex flex-col overflow-hidden">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-red-100 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="material-icons text-red-600 text-3xl">school</span>
                    Course Management
                </h1>
                <p class="text-gray-600 mt-1">Manage all courses in the learning management system</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" 
               class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2 font-medium">
                <span class="material-icons text-sm">add</span>
                Create Course
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="px-6 py-4 bg-gray-50">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Courses</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_courses'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-red-600">library_books</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active Courses</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['active_courses'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-green-600">play_circle_filled</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Enrollments</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_enrollments'] }}</p>
                    </div>
                    <span class="material-icons text-3xl text-blue-600">group</span>
                </div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Completion Rate</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['completion_rate'] }}%</p>
                    </div>
                    <span class="material-icons text-3xl text-purple-600">trending_up</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto px-6 py-4">
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Filters</h3>
            </div>
            <div class="p-4">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Courses</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Search by course name or code..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    <div class="min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center gap-2">
                            <span class="material-icons text-sm">search</span>
                            Search
                        </button>
                        <a href="{{ route('admin.courses.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2">
                            <span class="material-icons text-sm">clear</span>
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Course Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Courses ({{ $courses->total() }})</h3>
            </div>
            
            @if($courses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollments</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        @if($course->image)
                                            <img src="{{ $course->image }}" alt="{{ $course->course_name }}" 
                                                 class="w-12 h-12 object-cover rounded-lg">
                                        @else
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="material-icons text-blue-600">{{ $course->fallback_icon }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $course->course_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $course->course_code }}</p>
                                            <p class="text-xs text-gray-400">{{ $course->credits }} Credits</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm text-gray-900">{{ $course->instructor ?? 'Not assigned' }}</p>
                                    <p class="text-xs text-gray-500">{{ $course->category ?? 'Uncategorized' }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($course->start_date && $course->end_date)
                                        <p class="text-sm text-gray-900">{{ $course->start_date->format('M j, Y') }}</p>
                                        <p class="text-xs text-gray-500">to {{ $course->end_date->format('M j, Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $course->duration }}</p>
                                    @else
                                        <p class="text-sm text-gray-500">Not scheduled</p>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $course->enrollments_count }}</span>
                                        @if($course->max_students)
                                            <span class="text-xs text-gray-500">/ {{ $course->max_students }}</span>
                                        @endif
                                    </div>
                                    @if($course->max_students)
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-red-600 h-2 rounded-full" 
                                                 style="width: {{ $course->max_students > 0 ? ($course->enrollments_count / $course->max_students) * 100 : 0 }}%"></div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
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
                                </td>
                                <td class="px-4 py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $course->formatted_price ?? 'Free' }}</p>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.courses.show', $course) }}" 
                                           class="text-gray-600 hover:text-gray-900 transition-colors" title="View">
                                            <span class="material-icons text-sm">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.courses.edit', $course) }}" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                                            <span class="material-icons text-sm">edit</span>
                                        </a>
                                        <form method="POST" action="{{ route('admin.courses.toggle-status', $course) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="text-yellow-600 hover:text-yellow-900 transition-colors" 
                                                    title="{{ $course->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-icons text-sm">
                                                    {{ $course->status === 'active' ? 'pause' : 'play_arrow' }}
                                                </span>
                                            </button>
                                        </form>
                                        @if($course->enrollments_count === 0)
                                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}" 
                                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 transition-colors" title="Delete">
                                                    <span class="material-icons text-sm">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $courses->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-icons text-6xl text-gray-300 mb-4">school</span>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No courses found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first course.</p>
                    <a href="{{ route('admin.courses.create') }}" 
                       class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 transition-colors inline-flex items-center gap-2">
                        <span class="material-icons text-sm">add</span>
                        Create Course
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        {{ session('error') }}
    </div>
@endif
@endsection