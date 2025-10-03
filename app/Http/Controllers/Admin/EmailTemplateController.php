<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of email templates.
     */
    public function index(Request $request)
    {
        $query = EmailTemplate::with('createdBy');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%");
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $templates = $query->orderBy('name')->paginate(15);

        $types = EmailTemplate::getAvailableTypes();

        $stats = [
            'total_templates' => EmailTemplate::count(),
            'active_templates' => EmailTemplate::where('is_active', true)->count(),
            'template_types' => EmailTemplate::distinct('type')->count()
        ];

        return view('admin.email-templates.index', compact('templates', 'types', 'stats'));
    }

    /**
     * Show the form for creating a new email template.
     */
    public function create()
    {
        $types = EmailTemplate::getAvailableTypes();
        $commonVariables = ['user_name', 'user_email', 'site_name', 'site_url', 'current_date'];

        return view('admin.email-templates.create', compact('types', 'commonVariables'));
    }

    /**
     * Store a newly created email template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(EmailTemplate::getAvailableTypes())),
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');

        // Set default variables based on type
        if (empty($validated['variables'])) {
            $validated['variables'] = EmailTemplate::getDefaultVariables($validated['type']);
        }

        EmailTemplate::create($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully!');
    }

    /**
     * Display the specified email template.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        $emailTemplate->load('createdBy');

        // Sample variables for preview
        $sampleVariables = [
            'user_name' => 'John Doe',
            'user_email' => 'john.doe@example.com',
            'site_name' => 'LMS Platform',
            'site_url' => url('/'),
            'current_date' => now()->format('F j, Y'),
            'course_title' => 'Sample Course Title',
            'course_url' => url('/courses/1'),
            'enrollment_date' => now()->format('F j, Y'),
            'completion_date' => now()->format('F j, Y'),
            'certificate_url' => url('/certificates/1'),
            'certificate_number' => 'CERT-2024-001',
            'amount' => '$99.00',
            'transaction_id' => 'TXN123456'
        ];

        $previewSubject = $emailTemplate->renderSubject($sampleVariables);
        $previewBody = $emailTemplate->renderBody($sampleVariables);

        return view('admin.email-templates.show', compact('emailTemplate', 'previewSubject', 'previewBody', 'sampleVariables'));
    }

    /**
     * Show the form for editing the specified email template.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        $types = EmailTemplate::getAvailableTypes();
        $commonVariables = ['user_name', 'user_email', 'site_name', 'site_url', 'current_date'];

        return view('admin.email-templates.edit', compact('emailTemplate', 'types', 'commonVariables'));
    }

    /**
     * Update the specified email template.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:email_templates,name,' . $emailTemplate->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string|in:' . implode(',', array_keys(EmailTemplate::getAvailableTypes())),
            'variables' => 'nullable|array',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $emailTemplate->update($validated);

        return redirect()->route('admin.email-templates.show', $emailTemplate)
            ->with('success', 'Email template updated successfully!');
    }

    /**
     * Remove the specified email template.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully!');
    }

    /**
     * Duplicate an email template
     */
    public function duplicate(EmailTemplate $emailTemplate)
    {
        $newTemplate = $emailTemplate->replicate();
        $newTemplate->name = $emailTemplate->name . ' (Copy)';
        $newTemplate->is_active = false;
        $newTemplate->created_by = auth()->id();
        $newTemplate->save();

        return redirect()->route('admin.email-templates.edit', $newTemplate)
            ->with('success', 'Email template duplicated successfully! Please review and activate when ready.');
    }

    /**
     * Toggle template status
     */
    public function toggleStatus(EmailTemplate $emailTemplate)
    {
        $emailTemplate->update(['is_active' => !$emailTemplate->is_active]);
        
        $status = $emailTemplate->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Email template {$status} successfully!");
    }

    /**
     * Preview template with custom variables
     */
    public function preview(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'variables' => 'nullable|array'
        ]);

        $variables = $request->get('variables', []);
        $previewSubject = $emailTemplate->renderSubject($variables);
        $previewBody = $emailTemplate->renderBody($variables);

        return response()->json([
            'subject' => $previewSubject,
            'body' => nl2br(e($previewBody))
        ]);
    }

    /**
     * Get default variables for a template type
     */
    public function getTypeVariables(Request $request)
    {
        $request->validate([
            'type' => 'required|string'
        ]);

        $variables = EmailTemplate::getDefaultVariables($request->type);

        return response()->json([
            'variables' => $variables
        ]);
    }

    /**
     * Send test email
     */
    public function sendTest(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'test_email' => 'required|email',
            'variables' => 'nullable|array'
        ]);

        $variables = array_merge([
            'user_name' => 'Test User',
            'user_email' => $request->test_email,
            'site_name' => config('app.name'),
            'site_url' => url('/'),
            'current_date' => now()->format('F j, Y')
        ], $request->get('variables', []));

        $subject = $emailTemplate->renderSubject($variables);
        $body = $emailTemplate->renderBody($variables);

        try {
            \Mail::raw($body, function ($message) use ($request, $subject) {
                $message->to($request->test_email)
                        ->subject('[TEST] ' . $subject);
            });

            return redirect()->back()
                ->with('success', 'Test email sent successfully to ' . $request->test_email);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}