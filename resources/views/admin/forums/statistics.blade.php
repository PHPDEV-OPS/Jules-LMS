@extends('layouts.admin')

@section('title', 'Forum Statistics')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Forum Statistics
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Analytics and insights for forum activity
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <div class="flex items-center space-x-3">
                <select class="text-sm border border-gray-300 rounded-md px-3 py-2" onchange="changeDateRange(this.value)">
                    <option value="7">Last 7 days</option>
                    <option value="30" {{ request('days', 30) == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ request('days') == 90 ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ request('days') == 365 ? 'selected' : '' }}>Last year</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Categories -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-blue-600 text-2xl">category</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['categories_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Topics -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-green-600 text-2xl">topic</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Topics</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['topics_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Posts -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-purple-600 text-2xl">chat</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Posts</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['posts_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="material-icons text-orange-600 text-2xl">people</i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active_users_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activity -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
            <div class="space-y-4">
                @foreach($stats['recent_activity'] as $day => $count)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $day }}</span>
                        <div class="flex items-center space-x-2">
                            <div class="w-32 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['recent_activity']->max() > 0 ? ($count / $stats['recent_activity']->max()) * 100 : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900 w-8 text-right">{{ $count }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Most Active Categories</h3>
            <div class="space-y-4">
                @foreach($stats['top_categories'] as $category)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></div>
                            <span class="text-sm text-gray-900">{{ $category->name }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500">{{ $category->topics_count }} topics</span>
                            <span class="text-sm font-medium text-gray-900">{{ $category->posts_count }} posts</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Detailed Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Contributors -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Contributors</h3>
            <div class="space-y-4">
                @foreach($stats['top_contributors'] as $contributor)
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            {{ strtoupper(substr($contributor->first_name, 0, 1)) }}{{ strtoupper(substr($contributor->last_name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $contributor->first_name }} {{ $contributor->last_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $contributor->posts_count }} {{ Str::plural('post', $contributor->posts_count) }}
                            </p>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $contributor->topics_count }} {{ Str::plural('topic', $contributor->topics_count) }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Most Popular Topics -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Most Popular Topics</h3>
            <div class="space-y-4">
                @foreach($stats['popular_topics'] as $topic)
                    <div class="border-l-4 pl-3" style="border-color: {{ $topic->category->color }}">
                        <p class="text-sm font-medium text-gray-900 line-clamp-2">
                            <a href="{{ route('admin.forums.topics.show', $topic) }}" class="hover:text-blue-600">
                                {{ $topic->title }}
                            </a>
                        </p>
                        <div class="flex items-center space-x-3 mt-1 text-xs text-gray-500">
                            <span>{{ $topic->posts_count }} replies</span>
                            <span>{{ $topic->likes_count }} likes</span>
                            <span>{{ $topic->views_count ?? 0 }} views</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Forum Health -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Forum Health</h3>
            <div class="space-y-4">
                <!-- Response Rate -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Topics with Replies</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $stats['response_rate'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['response_rate'] }}%</span>
                    </div>
                </div>

                <!-- Average Response Time -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Avg Response Time</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['avg_response_time'] ?? 'N/A' }}</span>
                </div>

                <!-- Helpful Posts Ratio -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Helpful Posts</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-20 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['helpful_posts_ratio'] }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $stats['helpful_posts_ratio'] }}%</span>
                    </div>
                </div>

                <!-- Active Topics -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Active Topics (7 days)</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['active_topics_count'] }}</span>
                </div>

                <!-- Locked Topics -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Locked Topics</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['locked_topics_count'] }}</span>
                </div>

                <!-- Pinned Topics -->
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Pinned Topics</span>
                    <span class="text-sm font-medium text-gray-900">{{ $stats['pinned_topics_count'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.forums.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="material-icons text-sm mr-2">add</i>
                Add Category
            </a>
            
            <a href="{{ route('admin.forums.topics.index', ['sort_by' => 'created_at', 'days' => 1]) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="material-icons text-sm mr-2">today</i>
                Today's Topics
            </a>
            
            <a href="{{ route('admin.forums.posts.index', ['status' => 'flagged']) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="material-icons text-sm mr-2">flag</i>
                Flagged Posts
            </a>
        </div>
    </div>
</div>

<script>
function changeDateRange(days) {
    const url = new URL(window.location);
    url.searchParams.set('days', days);
    window.location.href = url.toString();
}
</script>
@endsection