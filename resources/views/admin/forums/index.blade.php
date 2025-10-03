@extends('layouts.admin')

@section('title', 'Forum Management')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Forum Management
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manage forum categories, topics, and posts
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('admin.forums.statistics') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="material-icons text-sm mr-2">analytics</i>
                Statistics
            </a>
            <a href="{{ route('admin.forums.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="material-icons text-sm mr-2">add</i>
                New Category
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-blue-600 text-2xl">category</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalCategories }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-green-600 text-2xl">topic</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Topics</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalTopics }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-purple-600 text-2xl">chat</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Posts</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalPosts }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-orange-600 text-2xl">trending_up</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Activity Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $totalTopics > 0 ? number_format($totalPosts / $totalTopics, 1) : '0' }}
                                <span class="text-sm text-gray-500">posts/topic</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Categories Overview -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Forum Categories</h3>
                <a href="{{ route('admin.forums.categories.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                    Manage all categories
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($categories as $category)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 rounded" style="background-color: {{ $category->color }}"></div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $category->name }}</h4>
                                <p class="text-xs text-gray-500">{{ Str::limit($category->description, 50) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $category->topics_count }}</div>
                            <div class="text-xs text-gray-500">{{ Str::plural('topic', $category->topics_count) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        No categories created yet.
                    </div>
                @endforelse
            </div>
            @if($categories->count() > 0)
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('admin.forums.categories.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                        View all {{ $categories->count() }} {{ Str::plural('category', $categories->count()) }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Topics -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Recent Topics</h3>
                <a href="{{ route('admin.forums.topics.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                    Manage all topics
                </a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentTopics as $topic)
                    <div class="px-6 py-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    @if($topic->is_pinned)
                                        <i class="material-icons text-yellow-500 text-sm">push_pin</i>
                                    @endif
                                    @if($topic->is_locked)
                                        <i class="material-icons text-red-500 text-sm">lock</i>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium" 
                                          style="background-color: {{ $topic->category->color }}20; color: {{ $topic->category->color }}">
                                        {{ $topic->category->name }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                    <a href="{{ route('admin.forums.topics.show', $topic) }}" class="hover:text-blue-600">
                                        {{ $topic->title }}
                                    </a>
                                </h4>
                                <div class="mt-1 text-xs text-gray-500 space-x-2">
                                    <span>by {{ $topic->student->first_name }} {{ $topic->student->last_name }}</span>
                                    <span>•</span>
                                    <span>{{ $topic->replies_count }} {{ Str::plural('reply', $topic->replies_count) }}</span>
                                    <span>•</span>
                                    <span>{{ $topic->last_activity_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        No topics created yet.
                    </div>
                @endforelse
            </div>
            @if($recentTopics->count() > 0)
                <div class="px-6 py-3 bg-gray-50 text-center">
                    <a href="{{ route('admin.forums.topics.index') }}" class="text-sm text-blue-600 hover:text-blue-900">
                        View all topics
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.forums.categories.index') }}" 
                   class="group relative bg-white p-6 rounded-lg border-2 border-gray-300 hover:border-blue-500 transition-colors">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-icons text-blue-600 text-2xl">category</i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-blue-600">Manage Categories</h4>
                            <p class="text-sm text-gray-500">Create and organize forum categories</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.forums.topics.index') }}" 
                   class="group relative bg-white p-6 rounded-lg border-2 border-gray-300 hover:border-green-500 transition-colors">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-icons text-green-600 text-2xl">topic</i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-green-600">Moderate Topics</h4>
                            <p class="text-sm text-gray-500">Pin, lock, or delete topics</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.forums.posts.index') }}" 
                   class="group relative bg-white p-6 rounded-lg border-2 border-gray-300 hover:border-purple-500 transition-colors">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="material-icons text-purple-600 text-2xl">chat</i>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900 group-hover:text-purple-600">Manage Posts</h4>
                            <p class="text-sm text-gray-500">Review and moderate post content</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection