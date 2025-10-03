@extends('layouts.admin')

@section('title', 'Grade Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Grade Details</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $grading->student->name ?? 'Student' }} - {{ $grading->assessment->title ?? 'Assessment' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.gradings.edit', $grading) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Grade
            </a>
            <a href="{{ route('admin.gradings.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Grade Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Submission Details</h2>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Student</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $grading->student->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Assessment</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $grading->assessment->title ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Course</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $grading->assessment->course->title ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Submitted</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $grading->submitted_at ? $grading->submitted_at->format('M d, Y \\\\a\\\\t g:i A') : 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Attempt</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $grading->attempt_number ?? 1 }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $grading->status === 'graded' ? 'bg-green-100 text-green-800' : 
                                       ($grading->status === 'submitted' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($grading->status ?? 'submitted') }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Grade Summary -->
        <div class="space-y-6">
            <!-- Grade Card -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Grade Summary</h3>
                <div class="text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">
                        {{ $grading->marks ?? '0' }}/{{ $grading->assessment->total_marks ?? '100' }}
                    </div>
                    <div class="text-2xl font-bold mb-4
                        {{ ($grading->percentage ?? 0) >= 80 ? 'text-green-600' : 
                           (($grading->percentage ?? 0) >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ round($grading->percentage ?? 0) }}%
                    </div>
                    <div class="text-sm text-gray-500">
                        @if(($grading->percentage ?? 0) >= 90)
                            Excellent Performance
                        @elseif(($grading->percentage ?? 0) >= 80)
                            Good Performance
                        @elseif(($grading->percentage ?? 0) >= 70)
                            Satisfactory Performance
                        @elseif(($grading->percentage ?? 0) >= 60)
                            Needs Improvement
                        @else
                            Poor Performance
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.gradings.edit', $grading) }}" class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">edit</span>
                        Edit Grade
                    </a>
                    <button class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">email</span>
                        Send to Student
                    </button>
                    <button class="w-full flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <span class="material-icons text-sm mr-3">print</span>
                        Print Grade Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    @if($grading->feedback)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Feedback</h2>
        </div>
        <div class="px-6 py-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $grading->feedback }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Submission Data -->
    @if($grading->submission_data)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Student Submission</h2>
        </div>
        <div class="px-6 py-6">
            <div class="bg-gray-900 text-green-400 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                <pre>{{ $grading->submission_data }}</pre>
            </div>
        </div>
    </div>
    @endif

    <!-- Assessment Questions (if available) -->
    @if($grading->assessment && $grading->assessment->questions->count() > 0)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Assessment Questions</h2>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                @foreach($grading->assessment->questions->sortBy('order') as $index => $question)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-start">
                        <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mr-3 mt-1">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 mb-2">{{ $question->question_text }}</p>
                            <div class="text-xs text-gray-500">
                                Type: {{ ucfirst(str_replace('_', ' ', $question->question_type)) }} • 
                                Points: {{ $question->points }}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Grade History -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Grade History</h2>
        </div>
        <div class="px-6 py-6">
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-blue-600 text-sm">assignment_turned_in</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Submission recorded</p>
                        <p class="text-sm text-gray-500">{{ $grading->submitted_at ? $grading->submitted_at->format('M d, Y \\\\a\\\\t g:i A') : $grading->created_at->format('M d, Y \\\\a\\\\t g:i A') }}</p>
                    </div>
                </div>

                @if($grading->status === 'graded' && $grading->updated_at != $grading->created_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="material-icons text-green-600 text-sm">grading</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Grade assigned</p>
                        <p class="text-sm text-gray-500">{{ $grading->updated_at->format('M d, Y \\\\a\\\\t g:i A') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection