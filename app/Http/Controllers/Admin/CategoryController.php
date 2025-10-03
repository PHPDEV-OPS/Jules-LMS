<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of categories.
     */
    public function index(Request $request)
    {
        $query = Category::withCount('courses');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $categories = $query->orderBy('name')->paginate(15);

        $stats = [
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'categories_with_courses' => Category::has('courses')->count()
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $colors = [
            '#ef4444' => 'Red',
            '#f97316' => 'Orange', 
            '#eab308' => 'Yellow',
            '#22c55e' => 'Green',
            '#06b6d4' => 'Cyan',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink'
        ];

        $icons = [
            'code', 'design_services', 'business', 'campaign', 'analytics', 
            'language', 'health_and_safety', 'school', 'science', 'sports_esports'
        ];

        return view('admin.categories.create', compact('colors', 'icons'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'required|string',
            'icon' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category)
    {
        $category->load(['courses' => function($query) {
            $query->withCount('enrollments');
        }]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $colors = [
            '#ef4444' => 'Red',
            '#f97316' => 'Orange', 
            '#eab308' => 'Yellow',
            '#22c55e' => 'Green',
            '#06b6d4' => 'Cyan',
            '#3b82f6' => 'Blue',
            '#8b5cf6' => 'Purple',
            '#ec4899' => 'Pink'
        ];

        $icons = [
            'code', 'design_services', 'business', 'campaign', 'analytics', 
            'language', 'health_and_safety', 'school', 'science', 'sports_esports'
        ];

        return view('admin.categories.edit', compact('category', 'colors', 'icons'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'color' => 'required|string',
            'icon' => 'required|string',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('admin.categories.show', $category)
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category.
     */
    public function destroy(Category $category)
    {
        if ($category->courses()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with existing courses.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Category {$status} successfully!");
    }
}