<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Enrollment::class);
        
        $query = Enrollment::with(['student', 'course']);
        
        // Handle status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Handle course filter
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        // Handle student search (enhanced to include course search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('course_name', 'like', "%{$search}%")
                       ->orWhere('course_code', 'like', "%{$search}%");
                });
            });
        }
        
        // Handle sorting
        $sortField = $request->get('sort', 'created_at_desc');
        switch($sortField) {
            case 'created_at_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'student_name':
                $query->join('students', 'enrollments.student_id', '=', 'students.id')
                      ->orderBy('students.first_name')
                      ->orderBy('students.last_name')
                      ->select('enrollments.*');
                break;
            case 'course_name':
                $query->join('courses', 'enrollments.course_id', '=', 'courses.id')
                      ->orderBy('courses.course_name')
                      ->select('enrollments.*');
                break;
            case 'status':
                $query->orderBy('status');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        $enrollments = $query->paginate(15)->appends($request->query());
        $courses = Course::orderBy('course_name')->get();
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->get('ajax')) {
            return response()->json([
                'html' => view('enrollments.partials.table', compact('enrollments'))->render(),
                'pagination' => $enrollments->links()->render(),
                'stats' => [
                    'total' => $enrollments->total(),
                    'showing' => $enrollments->count(),
                    'from' => $enrollments->firstItem(),
                    'to' => $enrollments->lastItem()
                ]
            ]);
        }
        
        // Get statistics for the dashboard
        $activeCount = $enrollments->where('status', 'active')->count();
        $completedCount = $enrollments->where('status', 'completed')->count();
        $droppedCount = $enrollments->where('status', 'dropped')->count();
        
        return view('admin.enrollments.index', compact('enrollments', 'courses', 'activeCount', 'completedCount', 'droppedCount'));
    }

    public function create()
    {
        $this->authorize('create', Enrollment::class);
        
        $students = Student::orderBy('first_name')->get();
        $courses = Course::orderBy('course_name')->get();
        
        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Enrollment::class);
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:active,completed,dropped',
        ]);

        // Check for duplicate enrollment
        $existingEnrollment = Enrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingEnrollment) {
            return back()->withErrors([
                'enrollment' => 'This student is already enrolled in this course.'
            ])->withInput();
        }

        $enrollment = Enrollment::create(array_merge($validated, [
            'enrolled_on' => now(),
        ]));

        return redirect()->route('enrollments.index')
            ->with('success', 'Student enrolled successfully!');
    }

    public function show(Enrollment $enrollment)
    {
        $this->authorize('view', $enrollment);
        
        $enrollment->load(['student', 'course']);
        
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        $students = Student::orderBy('first_name')->get();
        $courses = Course::orderBy('course_name')->get();
        
        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorize('update', $enrollment);
        $validated = $request->validate([
            'status' => 'required|in:active,completed,dropped',
        ]);

        $enrollment->update($validated);

        return redirect()->route('enrollments.show', $enrollment)
            ->with('success', 'Enrollment updated successfully!');
    }

    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);
        
        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully!');
    }

    public function bulkEnroll(Request $request)
    {
        $this->authorize('create', Enrollment::class);
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $course = Course::findOrFail($validated['course_id']);
        $successCount = 0;
        $errors = [];

        DB::transaction(function() use ($validated, &$successCount, &$errors) {
            foreach ($validated['student_ids'] as $studentId) {
                // Check for existing enrollment
                $existingEnrollment = Enrollment::where('student_id', $studentId)
                    ->where('course_id', $validated['course_id'])
                    ->first();

                if (!$existingEnrollment) {
                    Enrollment::create([
                        'student_id' => $studentId,
                        'course_id' => $validated['course_id'],
                        'enrolled_on' => now(),
                        'status' => 'active',
                    ]);
                    $successCount++;
                } else {
                    $student = Student::find($studentId);
                    $errors[] = "{$student->full_name} is already enrolled in this course.";
                }
            }
        });

        $message = "Successfully enrolled {$successCount} student(s) in {$course->course_name}.";
        
        if (count($errors) > 0) {
            $message .= " " . count($errors) . " student(s) were already enrolled.";
        }

        return redirect()->route('enrollments.index')
            ->with('success', $message)
            ->with('warnings', $errors);
    }

    public function export(Request $request)
    {
        $this->authorize('viewAny', Enrollment::class);
        
        $query = Enrollment::with(['student', 'course']);
        
        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                })->orWhereHas('course', function($cq) use ($search) {
                    $cq->where('course_name', 'like', "%{$search}%")
                       ->orWhere('course_code', 'like', "%{$search}%");
                });
            });
        }
        
        $enrollments = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'enrollments_export_' . now()->format('Y_m_d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($enrollments) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Student Name',
                'Student Email', 
                'Course Code',
                'Course Name',
                'Course Credits',
                'Status',
                'Enrolled Date',
                'Last Updated'
            ]);
            
            // CSV Data
            foreach ($enrollments as $enrollment) {
                fputcsv($file, [
                    $enrollment->student->full_name,
                    $enrollment->student->email,
                    $enrollment->course->course_code,
                    $enrollment->course->course_name,
                    $enrollment->course->credits,
                    ucfirst($enrollment->status),
                    $enrollment->created_at->format('Y-m-d H:i:s'),
                    $enrollment->updated_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}