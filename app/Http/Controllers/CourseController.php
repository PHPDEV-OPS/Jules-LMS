<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Store a newly created course in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        $this->authorize('create', Course::class);
        
        // The request is automatically validated using StoreCourseRequest rules
        $course = Course::create($request->validated());
        
        // Handle API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Course created successfully',
                'course' => $course
            ], 201);
        }

        // Handle web requests
        return redirect()->route('courses.show', $course)
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display a listing of the courses.
     */
    public function index(Request $request)
    {
        // Public course catalog - no authorization needed
        
        // Handle API requests
        if ($request->expectsJson()) {
            $courses = Course::with('enrollments')->paginate(15);
            return response()->json($courses);
        }
        
        // Handle web view requests
        $query = Course::with('enrollments')->withCount('enrollments');
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('course_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructor', 'like', "%{$search}%");
            });
        }
        
        // Handle level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        // Handle category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Handle sorting
        $sort = $request->get('sort', 'created_at');
        switch ($sort) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'enrollments_count':
                $query->orderBy('enrollments_count', 'desc');
                break;
            case 'start_date':
                $query->orderBy('start_date');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $courses = $query->paginate(12)->withQueryString();
        
        return view('courses.index', compact('courses'));
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course, Request $request)
    {
        // Public course view - no authorization needed
        
        // Handle API requests
        if ($request->expectsJson()) {
            return response()->json($course->load('enrollments'));
        }
        
        // Handle web requests
        $course->load(['enrollments.student'])->loadCount('enrollments');
        
        return view('courses.show', compact('course'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(StoreCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $course->update($request->validated());
        
        // Handle API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Course updated successfully',
                'course' => $course
            ]);
        }

        // Handle web requests
        return redirect()->route('courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course, Request $request)
    {
        $this->authorize('delete', $course);
        
        $course->delete();
        
        // Handle API requests
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Course deleted successfully'
            ]);
        }

        // Handle web requests
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        
        return view('courses.create-cooking');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        
        return view('courses.edit-cooking', compact('course'));
    }
}