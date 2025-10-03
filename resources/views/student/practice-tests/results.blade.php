@extends('layouts.dashboard')

@section('title', 'Practice Test Results')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Header -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="px-6 py-6">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full {{ $score >= 70 ? 'bg-green-100' : 'bg-orange-100' }} mb-4">
                        <i class="material-icons text-2xl {{ $score >= 70 ? 'text-green-600' : 'text-orange-600' }}">
                            {{ $score >= 70 ? 'check_circle' : 'info' }}
                        </i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Practice Test Completed!</h1>
                    <p class="text-gray-600">{{ $assessment->title }} - {{ $assessment->course->title }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                    <div class="text-center">
                        <div class="text-3xl font-bold {{ $score >= 70 ? 'text-green-600' : 'text-orange-600' }}">{{ $score }}%</div>
                        <div class="text-sm text-gray-500">Your Score</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900">{{ $correctAnswers }}/{{ $totalQuestions }}</div>
                        <div class="text-sm text-gray-500">Correct Answers</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $timeSpent }}</div>
                        <div class="text-sm text-gray-500">Minutes Spent</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            @if($score >= 90)
                                A
                            @elseif($score >= 80)
                                B
                            @elseif($score >= 70)
                                C
                            @elseif($score >= 60)
                                D
                            @else
                                F
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">Grade</div>
                    </div>
                </div>

                <div class="mt-6 flex justify-center space-x-4">
                    <a href="{{ route('student.practice-tests.restart', $assessment) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="material-icons text-sm mr-2">refresh</i>
                        Try Again
                    </a>
                    <a href="{{ route('student.practice-tests.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        <i class="material-icons text-sm mr-2">arrow_back</i>
                        Back to Practice Tests
                    </a>
                </div>
            </div>
        </div>

        <!-- Detailed Results -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Detailed Results</h2>
                <p class="text-sm text-gray-500">Review your answers and see the correct solutions</p>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($results as $index => $result)
                    <div class="px-6 py-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 mb-2">
                                    Question {{ $index + 1 }}
                                </h3>
                                <p class="text-gray-700 mb-4">{{ $result['question']->question_text }}</p>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $result['is_correct'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="material-icons text-xs mr-1">{{ $result['is_correct'] ? 'check' : 'close' }}</i>
                                    {{ $result['is_correct'] ? 'Correct' : 'Incorrect' }}
                                </span>
                            </div>
                        </div>

                        @if($result['question']->question_type === 'multiple_choice')
                            @php
                                $options = is_array($result['question']->options) ? $result['question']->options : json_decode($result['question']->options, true) ?? [];
                            @endphp
                            <div class="space-y-2">
                                @foreach($options as $key => $option)
                                    <div class="flex items-center p-3 rounded-lg {{ 
                                        $key === $result['question']->correct_answer ? 'bg-green-50 border border-green-200' : 
                                        ($key === $result['selected_answer'] && !$result['is_correct'] ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200') 
                                    }}">
                                        <div class="flex items-center min-w-0 flex-1">
                                            @if($key === $result['question']->correct_answer)
                                                <i class="material-icons text-green-600 mr-2">check_circle</i>
                                            @elseif($key === $result['selected_answer'] && !$result['is_correct'])
                                                <i class="material-icons text-red-600 mr-2">cancel</i>
                                            @else
                                                <div class="w-6 h-6 mr-2"></div>
                                            @endif
                                            <span class="text-gray-900">
                                                <span class="font-medium">{{ strtoupper($key) }}.</span> {{ $option }}
                                            </span>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            @if($key === $result['question']->correct_answer)
                                                <span class="text-xs text-green-600 font-medium">Correct Answer</span>
                                            @elseif($key === $result['selected_answer'])
                                                <span class="text-xs text-red-600 font-medium">Your Answer</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($result['question']->question_type === 'true_false')
                            <div class="space-y-2">
                                <div class="flex items-center p-3 rounded-lg {{ 
                                    $result['question']->correct_answer === 'true' ? 'bg-green-50 border border-green-200' : 
                                    ($result['selected_answer'] === 'true' && !$result['is_correct'] ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200') 
                                }}">
                                    @if($result['question']->correct_answer === 'true')
                                        <i class="material-icons text-green-600 mr-2">check_circle</i>
                                    @elseif($result['selected_answer'] === 'true' && !$result['is_correct'])
                                        <i class="material-icons text-red-600 mr-2">cancel</i>
                                    @else
                                        <div class="w-6 h-6 mr-2"></div>
                                    @endif
                                    <span class="font-medium">True</span>
                                    @if($result['question']->correct_answer === 'true')
                                        <span class="ml-auto text-xs text-green-600 font-medium">Correct Answer</span>
                                    @elseif($result['selected_answer'] === 'true')
                                        <span class="ml-auto text-xs text-red-600 font-medium">Your Answer</span>
                                    @endif
                                </div>
                                <div class="flex items-center p-3 rounded-lg {{ 
                                    $result['question']->correct_answer === 'false' ? 'bg-green-50 border border-green-200' : 
                                    ($result['selected_answer'] === 'false' && !$result['is_correct'] ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200') 
                                }}">
                                    @if($result['question']->correct_answer === 'false')
                                        <i class="material-icons text-green-600 mr-2">check_circle</i>
                                    @elseif($result['selected_answer'] === 'false' && !$result['is_correct'])
                                        <i class="material-icons text-red-600 mr-2">cancel</i>
                                    @else
                                        <div class="w-6 h-6 mr-2"></div>
                                    @endif
                                    <span class="font-medium">False</span>
                                    @if($result['question']->correct_answer === 'false')
                                        <span class="ml-auto text-xs text-green-600 font-medium">Correct Answer</span>
                                    @elseif($result['selected_answer'] === 'false')
                                        <span class="ml-auto text-xs text-red-600 font-medium">Your Answer</span>
                                    @endif
                                </div>
                            </div>
                        @elseif($result['question']->question_type === 'short_answer')
                            <div class="space-y-3">
                                <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                                    <p class="text-sm font-medium text-gray-900 mb-1">Your Answer:</p>
                                    <p class="text-gray-700">{{ $result['selected_answer'] ?: 'No answer provided' }}</p>
                                </div>
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm font-medium text-green-900 mb-1">Sample Answer:</p>
                                    <p class="text-green-700">{{ $result['question']->correct_answer }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Explanation feature not available in current model -->
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection