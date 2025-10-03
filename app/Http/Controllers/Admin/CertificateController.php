<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of certificates.
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['student.user', 'course']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('certificate_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('student.user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('course', function($q) use ($search) {
                      $q->where('title', 'LIKE', "%{$search}%");
                  });
        }

        // Filter by course
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by grade
        if ($request->has('grade') && $request->grade) {
            $query->where('grade', $request->grade);
        }

        $certificates = $query->orderBy('issued_date', 'desc')->paginate(15);

        $courses = Course::orderBy('title')->get();
        $statuses = ['active', 'revoked', 'suspended', 'pending'];
        $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'F'];

        $stats = [
            'total_certificates' => Certificate::count(),
            'active_certificates' => Certificate::where('status', 'active')->count(),
            'revoked_certificates' => Certificate::where('status', 'revoked')->count(),
            'expired_certificates' => Certificate::where('expiry_date', '<', now())->count()
        ];

        return view('admin.certificates.index', compact('certificates', 'courses', 'statuses', 'grades', 'stats'));
    }

    /**
     * Show the form for creating a new certificate.
     */
    public function create()
    {
        $courses = Course::active()->orderBy('title')->get();
        $students = Student::with('user')->get();
        $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C'];

        return view('admin.certificates.create', compact('courses', 'students', 'grades'));
    }

    /**
     * Store a newly created certificate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|in:A+,A,B+,B,C+,C,F',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,pending,revoked,suspended'
        ]);

        // Check if certificate already exists for this student-course combination
        $existing = Certificate::where('student_id', $validated['student_id'])
                              ->where('course_id', $validated['course_id'])
                              ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Certificate already exists for this student and course.')
                ->withInput();
        }

        $validated['certificate_number'] = $this->generateCertificateNumber();
        $validated['verification_code'] = $this->generateVerificationCode();
        $validated['issued_date'] = now();
        $validated['issued_by'] = auth()->id();

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate created successfully!');
    }

    /**
     * Display the specified certificate.
     */
    public function show(Certificate $certificate)
    {
        $certificate->load(['student.user', 'course', 'issuedBy']);

        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified certificate.
     */
    public function edit(Certificate $certificate)
    {
        $courses = Course::active()->orderBy('title')->get();
        $students = Student::with('user')->get();
        $grades = ['A+', 'A', 'B+', 'B', 'C+', 'C', 'F'];

        return view('admin.certificates.edit', compact('certificate', 'courses', 'students', 'grades'));
    }

    /**
     * Update the specified certificate.
     */
    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|in:A+,A,B+,B,C+,C,F',
            'completion_percentage' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'status' => 'required|in:active,pending,revoked,suspended'
        ]);

        // Check if certificate already exists for this student-course combination (excluding current)
        $existing = Certificate::where('student_id', $validated['student_id'])
                              ->where('course_id', $validated['course_id'])
                              ->where('id', '!=', $certificate->id)
                              ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Certificate already exists for this student and course.')
                ->withInput();
        }

        $certificate->update($validated);

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Certificate updated successfully!');
    }

    /**
     * Remove the specified certificate.
     */
    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate deleted successfully!');
    }

    /**
     * Revoke a certificate
     */
    public function revoke(Certificate $certificate)
    {
        $certificate->update(['status' => 'revoked']);

        return redirect()->back()
            ->with('success', 'Certificate revoked successfully!');
    }

    /**
     * Activate a certificate
     */
    public function activate(Certificate $certificate)
    {
        $certificate->update(['status' => 'active']);

        return redirect()->back()
            ->with('success', 'Certificate activated successfully!');
    }

    /**
     * Verify a certificate by verification code
     */
    public function verify(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string'
        ]);

        $certificate = Certificate::where('verification_code', $request->verification_code)
                                 ->with(['student.user', 'course'])
                                 ->first();

        if (!$certificate) {
            return redirect()->back()
                ->with('error', 'Invalid verification code.');
        }

        return view('admin.certificates.verify', compact('certificate'));
    }

    /**
     * Bulk issue certificates for course completions
     */
    public function bulkIssue(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'grade' => 'required|in:A+,A,B+,B,C+,C'
        ]);

        $course = Course::findOrFail($request->course_id);
        $completedEnrollments = $course->enrollments()
            ->where('status', 'completed')
            ->whereDoesntHave('student.certificates', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->with('student')
            ->get();

        $issued = 0;

        foreach ($completedEnrollments as $enrollment) {
            Certificate::create([
                'student_id' => $enrollment->student_id,
                'course_id' => $course->id,
                'certificate_number' => $this->generateCertificateNumber(),
                'verification_code' => $this->generateVerificationCode(),
                'issued_date' => now(),
                'grade' => $request->grade,
                'completion_percentage' => 100,
                'status' => 'active',
                'issued_by' => auth()->id()
            ]);
            $issued++;
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', "Issued {$issued} certificates successfully!");
    }

    /**
     * Generate unique certificate number
     */
    private function generateCertificateNumber()
    {
        do {
            $number = 'CERT-' . date('Y') . '-' . strtoupper(uniqid());
        } while (Certificate::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Generate unique verification code
     */
    private function generateVerificationCode()
    {
        do {
            $code = strtoupper(str()->random(12));
        } while (Certificate::where('verification_code', $code)->exists());

        return $code;
    }
}