<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('enrollments')
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_courses' => Course::count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'total_enrollments' => Enrollment::count(),
            'completion_rate' => $this->getCompletionRate()
        ];

        return view('admin.courses.index', compact('courses', 'stats'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        $categories = [
            'Programming' => 'Programming',
            'Design' => 'Design', 
            'Business' => 'Business',
            'Marketing' => 'Marketing',
            'Data Science' => 'Data Science',
            'Languages' => 'Languages',
            'Health' => 'Health',
            'Other' => 'Other'
        ];

        return view('admin.courses.create', compact('categories'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses',
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,draft'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $imagePath;
        }

        $course = Course::create($validated);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load(['enrollments.student', 'enrollments' => function($query) {
            $query->orderBy('enrolled_on', 'desc');
        }]);

        $stats = [
            'total_enrolled' => $course->enrollments->count(),
            'active_enrolled' => $course->enrollments->where('status', 'enrolled')->count(),
            'completed' => $course->enrollments->where('status', 'completed')->count(),
            'dropped' => $course->enrollments->where('status', 'dropped')->count(),
            'available_slots' => $course->available_slots,
            'completion_rate' => $course->enrollments->count() > 0 
                ? round(($course->enrollments->where('status', 'completed')->count() / $course->enrollments->count()) * 100, 1)
                : 0
        ];

        return view('admin.courses.show', compact('course', 'stats'));
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        $categories = [
            'Programming' => 'Programming',
            'Design' => 'Design', 
            'Business' => 'Business',
            'Marketing' => 'Marketing',
            'Data Science' => 'Data Science',
            'Languages' => 'Languages',
            'Health' => 'Health',
            'Other' => 'Other'
        ];

        return view('admin.courses.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code,' . $course->id,
            'course_name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor' => 'required|string|max:255',
            'credits' => 'required|integer|min:1|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'max_students' => 'required|integer|min:1',
            'category' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,draft'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image_url && Storage::disk('public')->exists($course->image_url)) {
                Storage::disk('public')->delete($course->image_url);
            }
            
            $imagePath = $request->file('image')->store('courses', 'public');
            $validated['image_url'] = $imagePath;
        }

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Check if course has enrollments
        if ($course->enrollments()->count() > 0) {
            return redirect()->route('admin.courses.index')
                ->with('error', 'Cannot delete course with existing enrollments.');
        }

        // Delete image if exists
        if ($course->image_url && Storage::disk('public')->exists($course->image_url)) {
            Storage::disk('public')->delete($course->image_url);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Toggle course status (active/inactive)
     */
    public function toggleStatus(Course $course)
    {
        $course->update([
            'status' => $course->status === 'active' ? 'inactive' : 'active'
        ]);

        $status = $course->status === 'active' ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Course {$status} successfully!");
    }

    /**
     * Get completion rate for all courses
     */
    private function getCompletionRate()
    {
        $totalEnrollments = Enrollment::count();
        if ($totalEnrollments === 0) return 0;
        
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        return round(($completedEnrollments / $totalEnrollments) * 100, 1);
    }

    /**
     * Duplicate a course
     */
    public function duplicate(Course $course)
    {
        $newCourse = $course->replicate();
        $newCourse->course_code = $course->course_code . '_COPY';
        $newCourse->course_name = $course->course_name . ' (Copy)';
        $newCourse->status = 'draft';
        $newCourse->save();

        return redirect()->route('admin.courses.edit', $newCourse)
            ->with('success', 'Course duplicated successfully! Please update the details.');
    }
}