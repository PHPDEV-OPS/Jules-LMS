@extends('layouts.dashboard')

@section('title', $course->title)

@section('content')
<div class="space-y-6">
    <!-- Course Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Course Banner -->
        <div class="h-64 bg-gradient-to-r from-blue-600 to-purple-600 relative">
            <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                <div class="text-center text-white">
                    <span class="material-icons text-6xl mb-4">school</span>
                    <h1 class="text-3xl font-bold">{{ $course->title }}</h1>
                </div>
            </div>
            @if($enrollment)
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="material-icons mr-1 text-sm">verified</span>
                        Enrolled
                    </span>
                </div>
            @endif
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Course Information -->
                <div class="lg:col-span-2">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">About This Course</h2>
                        <p class="text-gray-700 leading-relaxed">{{ $course->description }}</p>
                    </div>

                    @if($enrollment)
                    <!-- Progress Section -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Your Progress</h3>
                        @php $progress = rand(20, 90); @endphp
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Course Completion</span>
                                <span class="text-sm text-gray-500">{{ $progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-blue-600 h-3 rounded-full" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                {{ rand(5, 15) }} of {{ rand(15, 25) }} lessons completed
                            </div>
                        </div>
                    </div>

                    <!-- Course Content -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Course Content</h3>
                        <div class="space-y-3">
                            @for($i = 1; $i <= 8; $i++)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $i <= 3 ? 'bg-green-50 border-green-200' : 'bg-gray-50' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($i <= 3)
                                            <span class="material-icons text-green-600 mr-3">check_circle</span>
                                        @elseif($i == 4)
                                            <span class="material-icons text-blue-600 mr-3">play_circle</span>
                                        @else
                                            <span class="material-icons text-gray-400 mr-3">lock</span>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900">Lesson {{ $i }}: {{ ['Introduction', 'Fundamentals', 'Intermediate Concepts', 'Advanced Topics', 'Practical Applications', 'Case Studies', 'Best Practices', 'Final Project'][rand(0, 7)] }}</h4>
                                            <p class="text-sm text-gray-500">{{ rand(10, 45) }} minutes</p>
                                        </div>
                                    </div>
                                    @if($i <= 4)
                                    <button class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-white bg-blue-600 hover:bg-blue-700">
                                        {{ $i <= 3 ? 'Review' : 'Start' }}
                                    </button>
                                    @endif
                                </div>
                            </div>
                            @endfor
                        </div>
                    </div>
                    @else
                    <!-- Course Curriculum Preview -->
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">What You'll Learn</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <ul class="space-y-3">
                                @for($i = 1; $i <= 6; $i++)
                                <li class="flex items-start">
                                    <span class="material-icons text-green-600 mr-3 mt-0.5">check</span>
                                    <span class="text-gray-700">{{ ['Master the fundamentals and core concepts', 'Build real-world projects from scratch', 'Understand advanced techniques and best practices', 'Develop problem-solving skills', 'Learn industry-standard tools and workflows', 'Get hands-on experience with practical exercises'][rand(0, 5)] }}</span>
                                </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                        <!-- Course Stats -->
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Students Enrolled</span>
                                <span class="font-medium">{{ $course->enrollments_count }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Course Rating</span>
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="material-icons text-sm text-yellow-400">star</span>
                                    @endfor
                                    <span class="ml-1 font-medium">4.8</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Last Updated</span>
                                <span class="font-medium">{{ $course->updated_at->format('M Y') }}</span>
                            </div>
                            @if($course->category)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Category</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $course->category->name }}
                                </span>
                            </div>
                            @endif
                        </div>

                        @if($enrollment)
                            <!-- Enrolled Actions -->
                            <div class="space-y-3">
                                <button class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    <span class="material-icons mr-2">play_circle_filled</span>
                                    Continue Learning
                                </button>
                                
                                <a href="{{ route('student.assessments.index') }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <span class="material-icons mr-2 text-sm">assignment</span>
                                    View Assessments
                                </a>

                                <form method="POST" action="{{ route('student.courses.drop', $course) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to drop this course? Your progress will be saved.')"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50">
                                        <span class="material-icons mr-2 text-sm">close</span>
                                        Drop Course
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Enrollment Action -->
                            <form method="POST" action="{{ route('student.courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-lg font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                    <span class="material-icons mr-2">add</span>
                                    Enroll Now
                                </button>
                            </form>
                            <p class="mt-2 text-xs text-center text-gray-500">Join {{ $course->enrollments_count }} other students</p>
                        @endif

                        <!-- Additional Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-3">Course Features</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-center">
                                    <span class="material-icons text-green-600 mr-2 text-sm">play_circle</span>
                                    Interactive video lessons
                                </li>
                                <li class="flex items-center">
                                    <span class="material-icons text-green-600 mr-2 text-sm">assignment</span>
                                    Hands-on assignments
                                </li>
                                <li class="flex items-center">
                                    <span class="material-icons text-green-600 mr-2 text-sm">verified</span>
                                    Certificate of completion
                                </li>
                                <li class="flex items-center">
                                    <span class="material-icons text-green-600 mr-2 text-sm">schedule</span>
                                    Learn at your own pace
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($enrollment)
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('student.assessments.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-yellow-600">assignment</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Assessments</h3>
                    <p class="text-sm text-gray-500">Take quizzes and exams</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.certificates.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-purple-600">workspace_premium</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Certificates</h3>
                    <p class="text-sm text-gray-500">View your achievements</p>
                </div>
            </div>
        </a>

        <a href="{{ route('student.announcements.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="material-icons text-blue-600">campaign</span>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Announcements</h3>
                    <p class="text-sm text-gray-500">Course updates and news</p>
                </div>
            </div>
        </a>
    </div>
    @endif
</div>
@endsection