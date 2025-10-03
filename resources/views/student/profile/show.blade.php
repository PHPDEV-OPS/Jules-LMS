@extends('layouts.dashboard')

@section('title', 'My Profile')

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <!-- Cover Photo -->
        <div class="h-32 bg-gradient-to-r from-blue-600 to-purple-600"></div>
        
        <!-- Profile Info -->
        <div class="px-6 py-4">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div class="sm:flex sm:space-x-5">
                    <!-- Avatar -->
                    <div class="relative -mt-12 sm:-mt-16">
                        @if($student->avatar)
                            <img class="h-24 w-24 rounded-full ring-4 ring-white object-cover" 
                                 src="{{ Storage::url($student->avatar) }}" 
                                 alt="Profile Avatar">
                        @else
                            <div class="h-24 w-24 rounded-full ring-4 ring-white bg-blue-500 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Online Status -->
                        <div class="absolute bottom-0 right-0 h-6 w-6 bg-green-400 rounded-full ring-2 ring-white"></div>
                    </div>
                    
                    <!-- Basic Info -->
                    <div class="mt-4 sm:mt-0 sm:pt-1 sm:pb-1">
                        <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h1>
                        <p class="text-sm font-medium text-gray-500">
                            Student ID: {{ $student->student_id ?? 'STU' . str_pad($student->id, 6, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $student->email }}</p>
                        @if($student->bio)
                        <p class="mt-2 text-sm text-gray-700">{{ $student->bio }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-5 flex space-x-3 sm:mt-0">
                    <a href="{{ route('student.profile.edit') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons mr-2 text-sm">edit</span>
                        Edit Profile
                    </a>
                    <a href="{{ route('student.settings') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <span class="material-icons mr-2 text-sm">settings</span>
                        Settings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Courses -->
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Courses</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $enrollmentStats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Courses -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">play_circle_filled</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $enrollmentStats['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">verified</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Completed</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $enrollmentStats['completed'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificates -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                            <span class="material-icons text-white">workspace_premium</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Certificates</dt>
                            <dd class="text-2xl font-semibold text-gray-900">{{ $enrollmentStats['certificates'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">First Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->first_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->last_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->phone ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $student->date_of_birth ? $student->date_of_birth->format('F j, Y') : 'Not provided' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->created_at ? $student->created_at->format('F Y') : 'N/A' }}</dd>
                        </div>
                    </dl>
                    
                    @if($student->bio)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <dt class="text-sm font-medium text-gray-500">About</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $student->bio }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Content -->
        <div class="space-y-6">
            <!-- Recent Activity -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                </div>
                <div class="px-6 py-6">
                    @forelse($recentEnrollments as $enrollment)
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="material-icons text-blue-600 text-sm">school</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $enrollment->course->title }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Enrolled {{ $enrollment->enrolled_at ? $enrollment->enrolled_at->diffForHumans() : 'N/A' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $enrollment->status === 'active' ? 'bg-green-100 text-green-800' : 
                                   ($enrollment->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($enrollment->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No recent activity</p>
                    @endforelse
                    
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('student.courses.index') }}" class="text-sm text-blue-600 hover:text-blue-500">
                            View all courses →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    <a href="{{ route('student.profile.password') }}" 
                       class="flex items-center p-3 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
                        <span class="material-icons mr-3 text-gray-400">lock</span>
                        Change Password
                    </a>
                    <a href="{{ route('student.certificates.index') }}" 
                       class="flex items-center p-3 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
                        <span class="material-icons mr-3 text-gray-400">workspace_premium</span>
                        View Certificates
                    </a>
                    <a href="{{ route('student.grades.index') }}" 
                       class="flex items-center p-3 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
                        <span class="material-icons mr-3 text-gray-400">grade</span>
                        View Grades
                    </a>
                    <a href="{{ route('student.notifications.index') }}" 
                       class="flex items-center p-3 text-sm text-gray-700 rounded-lg hover:bg-gray-50">
                        <span class="material-icons mr-3 text-gray-400">notifications</span>
                        Manage Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection