@extends('layouts.dashboard')

@section('title', $category->name . ' - Forums')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('student.forums.index') }}" class="text-gray-400 hover:text-gray-500">
                        <i class="material-icons text-sm">forum</i>
                        <span class="sr-only">Forums</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="material-icons text-gray-400 text-sm mx-2">chevron_right</i>
                        <span class="text-sm font-medium text-gray-500">{{ $category->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Category Header -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-6 h-6 rounded" style="background-color: {{ $category->color }}"></div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                        <p class="text-gray-600">{{ $category->description }}</p>
                    </div>
                </div>
                <a href="{{ route('student.forums.create-topic') }}?category={{ $category->id }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">add</i>
                    New Topic
                </a>
            </div>
        </div>
    </div>

    <!-- Topics List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Topics</h2>
                <div class="text-sm text-gray-500">
                    {{ $topics->total() }} {{ Str::plural('topic', $topics->total()) }}
                </div>
            </div>
        </div>

        @if($topics->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($topics as $topic)
                    <div class="px-6 py-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    @if($topic->is_pinned)
                                        <i class="material-icons text-yellow-500 text-sm">push_pin</i>
                                    @endif
                                    @if($topic->course)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="material-icons text-xs mr-1">school</i>
                                            {{ $topic->course->title }}
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="text-lg font-medium text-gray-900 mb-2">
                                    <a href="{{ route('student.forums.topic', $topic) }}" class="hover:text-blue-600">
                                        {{ $topic->title }}
                                    </a>
                                </h3>
                                
                                <div class="text-sm text-gray-700 mb-3 line-clamp-2">
                                    {{ Str::limit(strip_tags($topic->content), 200) }}
                                </div>
                                
                                <div class="flex items-center text-sm text-gray-500 space-x-4">
                                    <div class="flex items-center">
                                        <i class="material-icons text-xs mr-1">person</i>
                                        {{ $topic->student->first_name }} {{ $topic->student->last_name }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="material-icons text-xs mr-1">chat</i>
                                        {{ $topic->replies_count }} {{ Str::plural('reply', $topic->replies_count) }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="material-icons text-xs mr-1">visibility</i>
                                        {{ $topic->views_count ?? 0 }} {{ Str::plural('view', $topic->views_count ?? 0) }}
                                    </div>
                                    <div class="flex items-center">
                                        <i class="material-icons text-xs mr-1">schedule</i>
                                        {{ $topic->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($topic->lastPost && $topic->lastPost->student)
                                <div class="flex-shrink-0 ml-6 text-right">
                                    <div class="text-xs text-gray-500">Last reply</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $topic->lastPost->student->first_name }} {{ $topic->lastPost->student->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $topic->last_activity_at->format('M j, Y g:i A') }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($topics->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $topics->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">chat</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No topics yet</h3>
                <p class="text-gray-500 mb-4">Be the first to start a discussion in this category.</p>
                <a href="{{ route('student.forums.create-topic') }}?category={{ $category->id }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">add</i>
                    Start New Topic
                </a>
            </div>
        @endif
    </div>
</div>
@endsection