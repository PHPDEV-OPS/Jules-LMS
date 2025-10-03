@extends('layouts.admin')

@section('title', 'Edit Forum Category')

@section('content')
<div class="space-y-6">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Edit Forum Category
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Update category settings and properties
            </p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.forums.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-6 space-y-6">
                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter category name"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description *
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe what this category is for"
                              required>{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Color *
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', $category->color) }}"
                               class="w-12 h-10 border border-gray-300 rounded-md">
                        <input type="text" 
                               id="color_hex" 
                               value="{{ old('color', $category->color) }}"
                               class="w-24 border border-gray-300 rounded-md shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="#FFFFFF">
                        <span class="text-sm text-gray-500">This color will be used to identify the category</span>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                        Sort Order *
                    </label>
                    <input type="number" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', $category->sort_order) }}"
                           min="0"
                           class="w-32 border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           required>
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-700">
                        Active (users can post in this category)
                    </label>
                </div>

                <!-- Category Statistics -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Category Statistics</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $category->topics_count }}</div>
                            <div class="text-sm text-gray-500">Total Topics</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $category->posts_count }}</div>
                            <div class="text-sm text-gray-500">Total Posts</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('admin.forums.categories.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="material-icons text-sm mr-2">arrow_back</i>
                    Back to Categories
                </a>
                
                <div class="flex space-x-3">
                    @if($category->topics_count == 0)
                        <button type="button" 
                                onclick="deleteCategory()"
                                class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <i class="material-icons text-sm mr-2">delete</i>
                            Delete Category
                        </button>
                    @endif
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="material-icons text-sm mr-2">save</i>
                        Update Category
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form (hidden) -->
@if($category->topics_count == 0)
    <form id="delete-form" action="{{ route('admin.forums.categories.destroy', $category) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('color');
    const colorHex = document.getElementById('color_hex');
    
    // Sync color picker with hex input
    colorPicker.addEventListener('input', function() {
        colorHex.value = this.value;
    });
    
    colorHex.addEventListener('input', function() {
        if (this.value.match(/^#[0-9A-F]{6}$/i)) {
            colorPicker.value = this.value;
        }
    });
});

function deleteCategory() {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection