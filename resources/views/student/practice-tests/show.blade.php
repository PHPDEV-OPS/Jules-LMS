@extends('layouts.dashboard')

@section('title', 'Practice Test: ' . $assessment->title)

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('student.practice-tests.index') }}" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Practice Tests</span>
                        <i class="material-icons text-sm">quiz</i>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm mx-2">chevron_right</i>
                        <span class="text-sm font-medium text-gray-500 truncate">{{ $assessment->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $assessment->title }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $assessment->course->title }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    <i class="material-icons text-sm mr-1">school</i>
                    Practice Mode
                </span>
            </div>
        </div>

        <div class="px-6 py-6">
            @if($assessment->description)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Description</h3>
                    <p class="text-gray-700">{{ $assessment->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="material-icons text-blue-500 mr-2">quiz</i>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Questions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assessment->questions->count() }}</p>
                        </div>
                    </div>
                </div>

                @if($assessment->duration_minutes)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="material-icons text-orange-500 mr-2">schedule</i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Time Limit</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $assessment->duration_minutes }} <span class="text-sm font-normal text-gray-600">minutes</span></p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i class="material-icons text-green-500 mr-2">all_inclusive</i>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Time Limit</p>
                                <p class="text-lg font-bold text-gray-900">Unlimited</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="material-icons text-blue-500 mr-2">info</i>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 mb-1">Practice Mode Information</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• This is a practice test and will not affect your grades</li>
                            <li>• You can retake this practice test as many times as you want</li>
                            <li>• Questions will be presented in random order</li>
                            <li>• You'll see detailed results and explanations at the end</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if($assessment->questions->isEmpty())
                <div class="text-center py-8">
                    <i class="material-icons text-gray-400 text-4xl mb-3">quiz</i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Questions Available</h3>
                    <p class="text-gray-500">This assessment doesn't have any questions yet.</p>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('student.practice-tests.index') }}" 
                       class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="material-icons text-sm mr-2">arrow_back</i>
                        Back to Practice Tests
                    </a>
                    
                    <a href="{{ route('student.practice-tests.start', $assessment) }}" 
                       class="inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="material-icons text-sm mr-2">play_arrow</i>
                        Start Practice Test
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection