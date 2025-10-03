@extends('layouts.admin')

@section('title', 'Assessment Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $assessment->course->title ?? 'No Course' }} • {{ ucfirst($assessment->type) }}</p>
        </div>
        <div class="flex space-x-3">
            @if($assessment->is_active)
                <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                    Active
                </span>
            @else
                <span class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                    <span class="w-2 h-2 bg-gray-600 rounded-full mr-2"></span>
                    Inactive
                </span>
            @endif
            <a href="{{ route('admin.assessments.edit', $assessment) }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                <span class="material-icons text-sm mr-2">edit</span>
                Edit Assessment
            </a>
            <a href="{{ route('admin.assessments.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <span class="material-icons text-sm mr-2">arrow_back</span>
                Back to List
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-blue-600 text-2xl">assignment_turned_in</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Submissions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $assessment->submissions->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-green-600 text-2xl">quiz</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Questions</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $assessment->questions->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-purple-600 text-2xl">grade</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Marks</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $assessment->total_marks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="material-icons text-orange-600 text-2xl">schedule</span>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Duration</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $assessment->formatted_duration }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Assessment Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete details about this assessment.</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Course</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $assessment->course->title ?? 'No Course Assigned' }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($assessment->type === 'quiz') bg-blue-100 text-blue-800
                            @elseif($assessment->type === 'exam') bg-red-100 text-red-800
                            @elseif($assessment->type === 'assignment') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($assessment->type) }}
                        </span>
                    </dd>
                </div>
                @if($assessment->description)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $assessment->description }}</dd>
                </div>
                @endif
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Marks & Passing</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $assessment->total_marks }} total marks • {{ $assessment->passing_marks }} passing ({{ $assessment->passing_percentage }}%)
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Attempts Allowed</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $assessment->attempts_allowed ?? 'Unlimited' }}</dd>
                </div>
                @if($assessment->start_date || $assessment->end_date)
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Availability</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        @if($assessment->start_date && $assessment->end_date)
                            {{ $assessment->start_date->format('M d, Y H:i') }} - {{ $assessment->end_date->format('M d, Y H:i') }}
                        @elseif($assessment->start_date)
                            Available from {{ $assessment->start_date->format('M d, Y H:i') }}
                        @elseif($assessment->end_date)
                            Available until {{ $assessment->end_date->format('M d, Y H:i') }}
                        @endif
                    </dd>
                </div>
                @endif
                @if($assessment->instructions)
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Instructions</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ $assessment->instructions }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Questions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Questions ({{ $assessment->questions->count() }})</h2>
            <a href="{{ route('admin.assessments.edit', $assessment) }}" 
               class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                <span class="material-icons text-sm mr-2">add</span>
                Manage Questions
            </a>
        </div>
        <div class="px-6 py-6">
            @if($assessment->questions->count() > 0)
                <div class="space-y-4">
                    @foreach($assessment->questions->sortBy('order') as $index => $question)
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                    </span>
                                    <span class="ml-2 text-sm text-gray-500">{{ $question->points }} points</span>
                                </div>
                                <p class="text-sm text-gray-900 mb-3">{{ $question->question_text }}</p>
                                
                                @if($question->options && count($question->options) > 0)
                                    <div class="ml-9">
                                        <p class="text-xs font-medium text-gray-700 mb-1">Options:</p>
                                        <ul class="text-xs text-gray-600 space-y-1">
                                            @foreach($question->options as $option)
                                                <li class="flex items-center">
                                                    <span class="w-4 h-4 border border-gray-300 rounded-full mr-2 flex items-center justify-center
                                                        {{ $option === $question->correct_answer ? 'bg-green-100 border-green-500' : '' }}">
                                                        @if($option === $question->correct_answer)
                                                            <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                                        @endif
                                                    </span>
                                                    {{ $option }}
                                                    @if($option === $question->correct_answer)
                                                        <span class="ml-2 text-green-600 font-medium">(Correct)</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @elseif($question->correct_answer)
                                    <div class="ml-9">
                                        <p class="text-xs font-medium text-gray-700">Correct Answer:</p>
                                        <p class="text-xs text-green-600">{{ $question->correct_answer }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-icons text-4xl text-gray-300 mb-2 block">quiz</span>
                    <p class="text-lg font-medium text-gray-900">No questions added yet</p>
                    <p class="text-sm text-gray-500 mb-4">Add questions to make this assessment functional.</p>
                    <a href="{{ route('admin.assessments.edit', $assessment) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                        <span class="material-icons text-sm mr-2">add</span>
                        Add Questions
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Submissions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Recent Submissions ({{ $assessment->submissions->count() }})</h2>
            @if($assessment->submissions->count() > 0)
                <a href="#" class="text-red-600 hover:text-red-900 text-sm font-medium">View All</a>
            @endif
        </div>
        <div class="px-6 py-6">
            @if($assessment->submissions->count() > 0)
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempt</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($assessment->submissions->take(5) as $submission)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $submission->student->name ?? 'Unknown Student' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $submission->submitted_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($submission->marks !== null)
                                        {{ $submission->marks }}/{{ $assessment->total_marks }}
                                    @else
                                        <span class="text-gray-500">Not graded</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($submission->status === 'graded') bg-green-100 text-green-800
                                        @elseif($submission->status === 'submitted') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $submission->attempt_number }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <span class="material-icons text-4xl text-gray-300 mb-2 block">assignment_turned_in</span>
                    <p class="text-lg font-medium text-gray-900">No submissions yet</p>
                    <p class="text-sm text-gray-500">Students haven't submitted this assessment yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection