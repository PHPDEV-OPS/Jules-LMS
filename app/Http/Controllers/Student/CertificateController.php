<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Display student's certificates
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get earned certificates
        $earnedCertificates = Certificate::where('student_id', $student->id)
            ->where('status', 'issued')
            ->with(['course'])
            ->orderBy('issued_at', 'desc')
            ->paginate(12);
        
        // Get available certificates (from completed courses)
        $completedEnrollmentIds = $student->enrollments()
            ->where('status', 'completed')
            ->pluck('id')
            ->toArray();
        
        $availableCertificates = Certificate::whereIn('enrollment_id', $completedEnrollmentIds)
            ->where('status', 'pending')
            ->with(['course', 'enrollment'])
            ->orderBy('created_at', 'desc')
            ->paginate(12, ['*'], 'available');
        
        return view('student.certificates.index', compact('earnedCertificates', 'availableCertificates', 'student'));
    }

    /**
     * Show certificate details
     */
    public function show(Certificate $certificate)
    {
        $student = Auth::guard('student')->user();
        
        if ($certificate->student_id !== $student->id) {
            abort(403, 'This certificate does not belong to you.');
        }
        
        $certificate->load(['course', 'enrollment']);
        
        return view('student.certificates.show', compact('certificate', 'student'));
    }

    /**
     * Download certificate
     */
    public function download(Certificate $certificate)
    {
        $student = Auth::guard('student')->user();
        
        if ($certificate->student_id !== $student->id) {
            abort(403, 'This certificate does not belong to you.');
        }
        
        if ($certificate->status !== 'issued') {
            abort(404, 'Certificate is not available for download.');
        }
        
        // Generate and download certificate PDF
        // This is a placeholder - you would implement actual PDF generation here
        $pdfContent = $this->generateCertificatePdf($certificate);
        
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="certificate-' . $certificate->certificate_code . '.pdf"');
    }

    /**
     * Generate certificate PDF (placeholder implementation)
     */
    private function generateCertificatePdf(Certificate $certificate)
    {
        // Placeholder PDF content
        // In a real implementation, you would use a PDF library like DOMPDF or TCPDF
        return "Certificate PDF content for: " . $certificate->student->name . " - " . $certificate->course->title;
    }
}