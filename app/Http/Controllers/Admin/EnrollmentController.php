<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrollments.
     */
    public function index(Request $request)
    {
        $query = Enrollment::with(['student', 'course'])
            ->orderBy('enrolled_on', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        // Search by student name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $enrollments = $query->paginate(15);

        // Get filter options
        $courses = Course::orderBy('course_name')->get();
        $statuses = ['enrolled', 'completed', 'dropped', 'pending'];

        // Get statistics for the view
        $activeCount = Enrollment::where('status', 'active')->count();
        $completedCount = Enrollment::where('status', 'completed')->count();
        $droppedCount = Enrollment::where('status', 'dropped')->count();

        return view('admin.enrollments.index', compact('enrollments', 'courses', 'activeCount', 'completedCount', 'droppedCount'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $students = Student::orderBy('first_name')->get();
        $courses = Course::where('status', 'active')->orderBy('course_name')->get();

        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:enrolled,pending,completed,dropped',
            'enrolled_on' => 'required|date',
            'progress' => 'nullable|integer|min:0|max:100',
            'grade' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string'
        ]);

        // Check if student is already enrolled in this course
        $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Student is already enrolled in this course.');
        }

        // Check course capacity
        $course = Course::findOrFail($validated['course_id']);
        if ($course->is_full) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Course is full. Cannot enroll more students.');
        }

        $enrollment = Enrollment::create($validated);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment created successfully!');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student', 'course']);
        
        return view('admin.enrollments.show', compact('enrollment'));
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(Enrollment $enrollment)
    {
        $students = Student::orderBy('first_name')->get();
        $courses = Course::orderBy('course_name')->get();

        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    /**
     * Update the specified enrollment in storage.
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'status' => 'required|in:enrolled,pending,completed,dropped',
            'progress' => 'nullable|integer|min:0|max:100',
            'grade' => 'nullable|numeric|min:0|max:100',
            'completion_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        // If status is changed to completed, set completion date
        if ($validated['status'] === 'completed' && !$enrollment->completion_date) {
            $validated['completion_date'] = now();
            $validated['progress'] = 100;
        }

        // If status is changed from completed, remove completion date
        if ($validated['status'] !== 'completed') {
            $validated['completion_date'] = null;
        }

        $enrollment->update($validated);

        return redirect()->route('admin.enrollments.show', $enrollment)
            ->with('success', 'Enrollment updated successfully!');
    }

    /**
     * Remove the specified enrollment from storage.
     */
    public function destroy(Enrollment $enrollment)
    {
        $studentName = $enrollment->student->first_name . ' ' . $enrollment->student->last_name;
        $courseName = $enrollment->course->course_name;
        
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', "Enrollment for {$studentName} in {$courseName} deleted successfully!");
    }

    /**
     * Bulk update enrollments
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'enrollment_ids' => 'required|array',
            'enrollment_ids.*' => 'exists:enrollments,id',
            'bulk_action' => 'required|in:complete,drop,activate,delete',
            'bulk_grade' => 'nullable|numeric|min:0|max:100',
            'bulk_notes' => 'nullable|string'
        ]);

        $enrollmentIds = $validated['enrollment_ids'];
        $action = $validated['bulk_action'];
        
        $updates = [];
        
        switch ($action) {
            case 'complete':
                $updates = [
                    'status' => 'completed',
                    'completion_date' => now(),
                    'progress' => 100
                ];
                if ($request->bulk_grade) {
                    $updates['grade'] = $validated['bulk_grade'];
                }
                break;
                
            case 'drop':
                $updates = ['status' => 'dropped'];
                break;
                
            case 'activate':
                $updates = ['status' => 'enrolled'];
                break;
                
            case 'delete':
                Enrollment::whereIn('id', $enrollmentIds)->delete();
                return redirect()->route('admin.enrollments.index')
                    ->with('success', count($enrollmentIds) . ' enrollments deleted successfully!');
        }

        if ($request->bulk_notes) {
            $updates['notes'] = $validated['bulk_notes'];
        }

        if (!empty($updates)) {
            Enrollment::whereIn('id', $enrollmentIds)->update($updates);
        }

        $message = ucfirst($action) . 'd ' . count($enrollmentIds) . ' enrollments successfully!';
        
        return redirect()->route('admin.enrollments.index')
            ->with('success', $message);
    }

    /**
     * Get enrollments by course for analytics
     */
    public function byCourse(Course $course)
    {
        $enrollments = $course->enrollments()
            ->with('student')
            ->orderBy('enrolled_on', 'desc')
            ->paginate(10);

        $stats = [
            'total_enrolled' => $course->enrollments->count(),
            'active_enrolled' => $course->enrollments->where('status', 'enrolled')->count(),
            'completed' => $course->enrollments->where('status', 'completed')->count(),
            'dropped' => $course->enrollments->where('status', 'dropped')->count(),
            'completion_rate' => $this->getCourseCompletionRate($course)
        ];

        return view('admin.enrollments.by-course', compact('course', 'enrollments', 'stats'));
    }

    /**
     * Get overall completion rate
     */
    private function getCompletionRate()
    {
        $total = Enrollment::count();
        if ($total === 0) return 0;
        
        $completed = Enrollment::where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    /**
     * Get course-specific completion rate
     */
    private function getCourseCompletionRate(Course $course)
    {
        $total = $course->enrollments->count();
        if ($total === 0) return 0;
        
        $completed = $course->enrollments->where('status', 'completed')->count();
        return round(($completed / $total) * 100, 1);
    }

    /**
     * Get average progress across all active enrollments
     */
    private function getAverageProgress()
    {
        return Enrollment::where('status', 'enrolled')
            ->whereNotNull('progress')
            ->avg('progress') ?? 0;
    }

    /**
     * Export enrollments to CSV
     */
    public function export(Request $request)
    {
        $query = Enrollment::with(['student', 'course']);

        // Apply same filters as index
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        $enrollments = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="enrollments_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($enrollments) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, [
                'ID', 'Student Name', 'Student Email', 'Course Code', 'Course Name',
                'Enrollment Date', 'Status', 'Progress', 'Grade', 'Completion Date'
            ]);

            // CSV Data
            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->id,
                    $enrollment->student->first_name . ' ' . $enrollment->student->last_name,
                    $enrollment->student->email,
                    $enrollment->course->course_code,
                    $enrollment->course->course_name,
                    $enrollment->enrolled_on->format('Y-m-d'),
                    $enrollment->status,
                    $enrollment->progress ?? 'N/A',
                    $enrollment->grade ?? 'N/A',
                    $enrollment->completion_date ? $enrollment->completion_date->format('Y-m-d') : 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mark enrollment as completed
     */
    public function complete(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress' => 100
        ]);

        return redirect()->back()->with('success', 'Enrollment marked as completed.');
    }

    /**
     * Suspend enrollment
     */
    public function suspend(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'suspended'
        ]);

        return redirect()->back()->with('success', 'Enrollment suspended.');
    }

    /**
     * Reactivate enrollment
     */
    public function reactivate(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Enrollment reactivated.');
    }

    /**
     * Drop enrollment
     */
    public function drop(Enrollment $enrollment)
    {
        $enrollment->update([
            'status' => 'dropped',
            'dropped_at' => now()
        ]);

        return redirect()->back()->with('success', 'Student dropped from course.');
    }
}