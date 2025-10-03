@extends('layouts.dashboard')

@section('title', 'Discussion Forums')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Discussion Forums
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Connect with classmates, ask questions, and share knowledge
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('student.forums.create-topic') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="material-icons text-sm mr-2">add</i>
                New Topic
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Forum Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Forum Categories -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Forum Categories</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-4 h-4 rounded" style="background-color: {{ $category->color }}"></div>
                                    <div>
                                        <a href="{{ route('student.forums.category', $category) }}" 
                                           class="text-lg font-medium text-gray-900 hover:text-blue-600">
                                            {{ $category->name }}
                                        </a>
                                        <p class="text-sm text-gray-600">{{ $category->description }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ $category->topics_count }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::plural('topic', $category->topics_count) }}</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <i class="material-icons text-gray-400 text-4xl mb-2">forum</i>
                            <p class="text-gray-500">No forum categories available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Topics -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Recent Discussions</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($recentTopics as $topic)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-2">
                                        @if($topic->is_pinned)
                                            <i class="material-icons text-yellow-500 text-sm">push_pin</i>
                                        @endif
                                        @if($topic->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                                            {{ $topic->category->name }}
                                        </span>
                                        @endif
                                        @if($topic->course)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $topic->course->title }}
                                            </span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-medium text-gray-900 truncate">
                                        <a href="{{ route('student.forums.topic', $topic) }}" class="hover:text-blue-600">
                                            {{ $topic->title }}
                                        </a>
                                    </h4>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 space-x-4">
                                        @if($topic->student)
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">person</i>
                                            {{ $topic->student->first_name }} {{ $topic->student->last_name }}
                                        </div>
                                        @endif
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">chat</i>
                                            {{ $topic->replies_count }} {{ Str::plural('reply', $topic->replies_count) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">schedule</i>
                                            {{ $topic->last_activity_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                @if($topic->lastPost && $topic->lastPost->student)
                                    <div class="flex-shrink-0 ml-4 text-right">
                                        <div class="text-xs text-gray-500">Last reply by</div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $topic->lastPost->student->first_name }} {{ $topic->lastPost->student->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $topic->last_activity_at->format('M j, Y') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <i class="material-icons text-gray-400 text-4xl mb-2">chat</i>
                            <p class="text-gray-500">No recent discussions yet. Be the first to start a conversation!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Forum Statistics -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Forum Activity</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Topics</span>
                        <span class="text-lg font-bold text-gray-900">{{ $recentTopics->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Categories</span>
                        <span class="text-lg font-bold text-gray-900">{{ $categories->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Your Topics</span>
                        <span class="text-lg font-bold text-blue-600">
                            {{ $recentTopics->where('student_id', auth('student')->id())->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Popular Topics -->
            @if($popularTopics->isNotEmpty())
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Popular Topics</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($popularTopics as $topic)
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold text-blue-600">{{ $loop->iteration }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <a href="{{ route('student.forums.topic', $topic) }}" class="hover:text-blue-600">
                                                {{ $topic->title }}
                                            </a>
                                        </p>
                                        <div class="flex items-center text-xs text-gray-500 mt-1">
                                            @if($topic->category)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                                                  style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                                                {{ $topic->category->name }}
                                            </span>
                                            @endif
                                            <span class="ml-2">{{ $topic->replies_count }} replies</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- My Courses -->
            @if($enrolledCourses->isNotEmpty())
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">My Courses</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($enrolledCourses as $enrollment)
                                @if($enrollment->course)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            @if($enrollment->course->image)
                                                <img src="{{ $enrollment->course->image }}" alt="{{ $enrollment->course->title }}" class="w-8 h-8 rounded-lg object-cover">
                                            @else
                                                <div class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="material-icons text-white text-xs">{{ $enrollment->course->fallback_icon }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="ml-2 text-sm text-gray-900">{{ $enrollment->course->title }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $enrollment->course->category->name ?? 'General' }}</span>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection