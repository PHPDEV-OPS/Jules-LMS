@extends('layouts.dashboard')

@section('title', 'My Assessments')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">My Assessments</h1>
            <p class="mt-1 text-sm text-gray-500">Complete assessments and track your progress</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('student.courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons mr-2 text-sm">school</span>
                My Courses
            </a>
        </div>
    </div>

    <!-- Assessment Tabs -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('available')" id="available-tab"
                    class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Available Assessments ({{ $availableAssessments->count() }})
            </button>
            <button onclick="showTab('completed')" id="completed-tab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                Completed Assessments ({{ $completedAssessments->count() }})
            </button>
        </nav>
    </div>

    <!-- Available Assessments Tab -->
    <div id="available-content" class="tab-content">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Available Assessments</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($availableAssessments as $assessment)
                @php
                    $submission = $assessment->submissions->first();
                    $isCompleted = $submission !== null;
                @endphp
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ $assessment->title }}</h3>
                                @if($isCompleted)
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <span class="material-icons mr-1" style="font-size: 14px;">verified</span>
                                        Completed
                                    </span>
                                @else
                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <span class="material-icons mr-1" style="font-size: 14px;">pending</span>
                                        Pending
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-3">{{ $assessment->description }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-6 mb-3">
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">school</span>
                                    <span>{{ $assessment->course->title }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">quiz</span>
                                    <span>{{ $assessment->questions->count() }} questions</span>
                                </div>
                                @if($assessment->time_limit)
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">schedule</span>
                                    <span>{{ $assessment->time_limit }} minutes</span>
                                </div>
                                @endif
                                @if($assessment->due_date)
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">event</span>
                                    <span>Due: {{ $assessment->due_date->format('M j, Y g:i A') }}</span>
                                </div>
                                @endif
                            </div>

                            @if($isCompleted)
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500">Score:</span>
                                    <span class="ml-2 font-medium {{ $submission->score >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($submission->score, 1) }}%
                                    </span>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <span class="text-gray-500">Submitted: {{ $submission->submitted_at->format('M j, Y g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2 ml-4">
                            @if($isCompleted)
                                <a href="{{ route('student.assessments.result', $submission) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <span class="material-icons mr-1 text-sm">visibility</span>
                                    View Result
                                </a>
                                @if($assessment->allow_multiple_attempts)
                                <a href="{{ route('student.assessments.take', $assessment) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <span class="material-icons mr-1 text-sm">refresh</span>
                                    Retake
                                </a>
                                @endif
                            @else
                                <a href="{{ route('student.assessments.show', $assessment) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <span class="material-icons mr-1 text-sm">info</span>
                                    Details
                                </a>
                                <a href="{{ route('student.assessments.take', $assessment) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <span class="material-icons mr-1 text-sm">play_arrow</span>
                                    Start Assessment
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <span class="material-icons text-6xl text-gray-400">assignment</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Available Assessments</h3>
                    <p class="mt-2 text-sm text-gray-500">There are no assessments available for your enrolled courses at the moment.</p>
                    <div class="mt-6">
                        <a href="{{ route('student.courses.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <span class="material-icons mr-2 text-sm">school</span>
                            Browse Courses
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            @if($availableAssessments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $availableAssessments->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Completed Assessments Tab -->
    <div id="completed-content" class="tab-content hidden">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Completed Assessments</h2>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($completedAssessments as $submission)
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ $submission->assessment->title }}</h3>
                                <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $submission->score >= 70 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $submission->score >= 70 ? 'Passed' : 'Failed' }}
                                </span>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-3">{{ $submission->assessment->description }}</p>
                            
                            <div class="flex items-center text-sm text-gray-500 space-x-6 mb-3">
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">school</span>
                                    <span>{{ $submission->assessment->course->title }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">grade</span>
                                    <span class="font-medium {{ $submission->score >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($submission->score, 1) }}%
                                    </span>
                                </div>
                                <div class="flex items-center">
                                    <span class="material-icons mr-1 text-sm">event</span>
                                    <span>{{ $submission->submitted_at->format('M j, Y g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2 ml-4">
                            <a href="{{ route('student.assessments.result', $submission) }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <span class="material-icons mr-1 text-sm">visibility</span>
                                View Result
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <span class="material-icons text-6xl text-gray-400">assignment_turned_in</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No Completed Assessments</h3>
                    <p class="mt-2 text-sm text-gray-500">You haven't completed any assessments yet.</p>
                </div>
                @endforelse
            </div>

            @if($completedAssessments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $completedAssessments->appends(['completed' => request('completed')])->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-blue-500', 'text-blue-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
}

// Initialize with available assessments tab active
document.addEventListener('DOMContentLoaded', function() {
    showTab('available');
});
</script>
@endsection