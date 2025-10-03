<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display student settings
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        return view('student.settings.index', compact('student'));
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'course_notifications' => 'boolean',
            'assessment_notifications' => 'boolean',
            'certificate_notifications' => 'boolean',
            'announcement_notifications' => 'boolean',
        ]);
        
        // Update student preferences (you may want to create a separate preferences table)
        $student->update([
            'notification_preferences' => json_encode($validated)
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'profile_visibility' => 'in:public,enrolled,private',
            'show_progress' => 'boolean',
            'show_certificates' => 'boolean',
        ]);
        
        $student->update([
            'privacy_settings' => json_encode($validated)
        ]);
        
        return response()->json(['success' => true]);
    }
}