@extends('layouts.admin')

@section('title', 'Student Details - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Student Details
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                View detailed information about {{ $student->first_name }} {{ $student->last_name }}.
            </p>
        </div>
        <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
            <a href="{{ route('students.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">arrow_back</span>
                Back to Students
            </a>
            <a href="{{ route('students.edit', $student) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons mr-2 text-sm">edit</span>
                Edit Student
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Student Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="flex-shrink-0 h-20 w-20">
                            <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center">
                                <span class="text-2xl font-medium text-white">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h3>
                            <p class="text-sm text-gray-500">{{ $student->email }}</p>
                            @if($student->student_id)
                                <p class="text-sm text-gray-500">Student ID: {{ $student->student_id }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Phone Number</div>
                            <div class="text-sm font-medium text-gray-900">{{ $student->phone_number ?? 'Not provided' }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Date of Birth</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $student->date_of_birth ? $student->date_of_birth->format('M j, Y') : 'Not provided' }}
                            </div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Registration Date</div>
                            <div class="text-sm font-medium text-gray-900">{{ $student->created_at->format('M j, Y g:i A') }}</div>
                        </div>
                        <div class="space-y-1">
                            <div class="text-sm text-gray-500">Last Updated</div>
                            <div class="text-sm font-medium text-gray-900">{{ $student->updated_at->format('M j, Y g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrollment History -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Enrollment History</h3>
                    
                    @if($student->enrollments->count() > 0)
                        <div class="space-y-4">
                            @foreach($student->enrollments->sortByDesc('enrolled_on') as $enrollment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $enrollment->course->title }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $enrollment->course->course_code }}</p>
                                            <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                                <span>Enrolled: {{ $enrollment->enrolled_on ? $enrollment->enrolled_on->format('M j, Y') : 'N/A' }}</span>
                                                @if($enrollment->completion_date)
                                                    <span>Completed: {{ $enrollment->completion_date->format('M j, Y') }}</span>
                                                @endif
                                                @if($enrollment->status === 'dropped')
                                                    <span>Status: Dropped</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            @if($enrollment->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @elseif($enrollment->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Completed
                                                </span>
                                            @elseif($enrollment->status === 'dropped')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Dropped
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($enrollment->notes)
                                        <div class="mt-2 text-sm text-gray-600">
                                            <strong>Notes:</strong> {{ $enrollment->notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <span class="material-icons text-4xl text-gray-400 mb-2">school</span>
                            <p class="text-gray-500">No enrollments found for this student.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Stats</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Enrollments</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $student->enrollments->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Active Courses</dt>
                            <dd class="text-2xl font-bold text-green-600">{{ $student->enrollments->where('status', 'active')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Completed Courses</dt>
                            <dd class="text-2xl font-bold text-blue-600">{{ $student->enrollments->where('status', 'completed')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Completion Rate</dt>
                            @php
                                $total = $student->enrollments->count();
                                $completed = $student->enrollments->where('status', 'completed')->count();
                                $rate = $total > 0 ? round(($completed / $total) * 100, 1) : 0;
                            @endphp
                            <dd class="text-2xl font-bold text-purple-600">{{ $rate }}%</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('enrollments.create') }}?student_id={{ $student->id }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-2 text-sm">person_add</span>
                            Enroll in Course
                        </a>
                        
                        <a href="mailto:{{ $student->email }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">mail</span>
                            Send Email
                        </a>
                        
                        <button onclick="generateReport()" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <span class="material-icons mr-2 text-sm">assessment</span>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>

            <!-- Account Status -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Account Status</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Account Type</span>
                            <span class="text-sm font-medium text-gray-900">Student</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Last Login</span>
                            <span class="text-sm text-gray-500">{{ $student->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateReport() {
    alert('Report generation feature coming soon!');
}
</script>
@endsection