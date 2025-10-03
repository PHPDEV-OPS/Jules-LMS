@extends('layouts.admin')

@section('title', 'Student Management')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Student Management
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manage student accounts and track their enrollment progress.
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <a href="#" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">download</span>
                Export
            </a>
            <a href="{{ route('students.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons mr-2 text-sm">person_add</span>
                Add Student
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
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
                            <dd class="text-lg font-medium text-gray-900">{{ $students->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Enrollments</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $students->sum(function($student) { return $student->enrollments->where('status', 'active')->count(); }) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed Courses</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $students->sum(function($student) { return $student->enrollments->where('status', 'completed')->count(); }) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white text-sm">person_add</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">New This Month</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $students->where('created_at', '>=', now()->startOfMonth())->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white p-4 shadow rounded-lg">
        <form method="GET" action="{{ route('students.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-0">
                <label for="search" class="block text-sm font-medium text-gray-700">Search Students</label>
                <div class="mt-1 relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500" 
                           placeholder="Search by name or email...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-icons text-gray-400">search</span>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons mr-2 text-sm">search</span>
                Search
            </button>
            
            @if(request('search'))
                <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Enrollments
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Joined Date
                            </th>
                            <th class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                            @if($student->student_id)
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $student->student_id }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $student->email }}</div>
                                    @if($student->phone_number)
                                        <div class="text-sm text-gray-500">{{ $student->phone_number }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        @php
                                            $active = $student->enrollments->where('status', 'active')->count();
                                            $completed = $student->enrollments->where('status', 'completed')->count();
                                            $total = $student->enrollments->count();
                                        @endphp
                                        
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                {{ $active }} Active
                                            </span>
                                            @if($completed > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $completed }} Completed
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $total }} total enrollments</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $student->created_at->format('M j, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-900">
                                            <span class="material-icons text-sm">visibility</span>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}" class="text-red-600 hover:text-red-900">
                                            <span class="material-icons text-sm">edit</span>
                                        </a>
                                        <form action="{{ route('students.destroy', $student) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this student?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <span class="material-icons text-sm">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="material-icons text-4xl text-gray-400 mb-2">people</span>
                                        <span>No students found</span>
                                        @if(request('search'))
                                            <a href="{{ route('students.index') }}" class="mt-2 text-red-600 hover:text-red-900">
                                                Clear search to see all students
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($students->hasPages())
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection