@extends('layouts.admin')

@section('title', 'Enrollment Details')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Enrollment Details
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                View detailed information about this enrollment.
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <a href="{{ route('enrollments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">arrow_back</span>
                Back to Enrollments
            </a>
            <a href="{{ route('enrollments.edit', $enrollment) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons mr-2 text-sm">edit</span>
                Edit Enrollment
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Student Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 h-12 w-12">
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-lg font-medium text-white">
                                        {{ substr($enrollment->student->first_name, 0, 1) }}{{ substr($enrollment->student->last_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="text-lg font-medium text-gray-900">
                                    {{ $enrollment->student->first_name }} {{ $enrollment->student->last_name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $enrollment->student->email }}</div>
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Student ID</div>
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->student_id ?? 'N/A' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Phone Number</div>
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->student->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Date of Birth</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->student->date_of_birth ? $enrollment->student->date_of_birth->format('M j, Y') : 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Course Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Course Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Course Title</div>
                            <div class="text-lg font-medium text-gray-900">{{ $enrollment->course->title }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Course Code</div>
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->course_code }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Credits</div>
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->credits ?? 'N/A' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Instructor</div>
                            <div class="text-sm font-medium text-gray-900">{{ $enrollment->course->instructor ?? 'TBA' }}</div>
                        </div>
                        <div class="sm:col-span-2 space-y-1">
                            <div class="text-sm text-gray-500">Description</div>
                            <div class="text-sm text-gray-900">{{ $enrollment->course->description ?? 'No description available.' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment Activity -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Enrollment Activity</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <span class="material-icons text-white text-sm">person_add</span>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Student enrolled in course</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y g:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>

                            @if($enrollment->completion_date)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <span class="material-icons text-white text-sm">verified</span>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Course completed</p>
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $enrollment->completion_date->format('M j, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif

                            @if($enrollment->dropped_at)
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <span class="material-icons text-white text-sm">cancel</span>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-500">Student dropped course</p>
                                                    @if($enrollment->drop_reason)
                                                        <p class="text-xs text-gray-400 mt-1">Reason: {{ $enrollment->drop_reason }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $enrollment->dropped_at->format('M j, Y g:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Status</h3>
                    <div class="space-y-3">
                        <div>
                            @if($enrollment->status === 'active')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    <span class="material-icons mr-1 text-sm">play_circle_filled</span>
                                    Active
                                </span>
                            @elseif($enrollment->status === 'completed')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <span class="material-icons mr-1 text-sm">verified</span>
                                    Completed
                                </span>
                            @elseif($enrollment->status === 'dropped')
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    <span class="material-icons mr-1 text-sm">cancel</span>
                                    Dropped
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($enrollment->status) }}
                                </span>
                            @endif
                        </div>
                        
                        <!-- Progress Bar -->
                        @php
                            $progress = $enrollment->status === 'completed' ? 100 : 
                                       ($enrollment->status === 'active' ? rand(25, 85) : 0);
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progress</span>
                                <span>{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Quick Actions</h3>
                    <div class="space-y-3">
                        @if($enrollment->status === 'active')
                            <form action="{{ route('enrollments.complete', $enrollment) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <span class="material-icons mr-2 text-sm">verified</span>
                                    Mark as Completed
                                </button>
                            </form>
                            
                            <form action="{{ route('enrollments.suspend', $enrollment) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to suspend this enrollment?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <span class="material-icons mr-2 text-sm">pause</span>
                                    Suspend
                                </button>
                            </form>
                        @elseif($enrollment->status === 'suspended')
                            <form action="{{ route('enrollments.reactivate', $enrollment) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <span class="material-icons mr-2 text-sm">play_arrow</span>
                                    Reactivate
                                </button>
                            </form>
                        @endif
                        
                        @if(!in_array($enrollment->status, ['completed', 'dropped']))
                            <form action="{{ route('enrollments.drop', $enrollment) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to drop this student from the course? This action cannot be undone.')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    <span class="material-icons mr-2 text-sm">person_remove</span>
                                    Drop Student
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($enrollment->notes)
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Notes</h3>
                        <p class="text-sm text-gray-600">{{ $enrollment->notes }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection