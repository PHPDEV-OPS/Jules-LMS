@extends('layouts.admin')

@section('title', 'Forum Categories')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Forum Categories
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Manage and organize forum discussion categories
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('admin.forums.categories.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                <i class="material-icons text-sm mr-2">add</i>
                New Category
            </a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">All Categories</h3>
                <span class="text-sm text-gray-500">{{ $categories->count() }} {{ Str::plural('category', $categories->count()) }}</span>
            </div>
        </div>

        @if($categories->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($categories as $category)
                    <div class="px-6 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded" style="background-color: {{ $category->color }}"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $category->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $category->description }}</p>
                                    <div class="flex items-center mt-2 space-x-4 text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">topic</i>
                                            {{ $category->topics_count }} {{ Str::plural('topic', $category->topics_count) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">chat</i>
                                            {{ $category->posts_count }} {{ Str::plural('post', $category->posts_count) }}
                                        </div>
                                        <div class="flex items-center">
                                            <i class="material-icons text-xs mr-1">sort</i>
                                            Order: {{ $category->sort_order }}
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.forums.categories.edit', $category) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <i class="material-icons text-sm mr-1">edit</i>
                                    Edit
                                </a>
                                
                                @if($category->topics_count == 0)
                                    <form action="{{ route('admin.forums.categories.destroy', $category) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this category?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                            <i class="material-icons text-sm mr-1">delete</i>
                                            Delete
                                        </button>
                                    </form>
                                @else
                                    <button type="button" 
                                            disabled
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                                        <i class="material-icons text-sm mr-1">delete</i>
                                        Cannot Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="material-icons text-gray-400 text-6xl mb-4">category</i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Categories</h3>
                <p class="text-gray-500 mb-4">Get started by creating your first forum category.</p>
                <a href="{{ route('admin.forums.categories.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="material-icons text-sm mr-2">add</i>
                    Create Category
                </a>
            </div>
        @endif
    </div>
</div>
@endsection