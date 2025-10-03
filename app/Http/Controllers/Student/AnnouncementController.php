<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display announcements for student
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get all active announcements
        $announcements = Announcement::where('is_active', true)
            ->with(['readings' => function($query) use ($student) {
                $query->where('student_id', $student->id);
            }])
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get unread count
        $unreadCount = Announcement::where('is_active', true)
            ->whereDoesntHave('readings', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->count();
        
        return view('student.announcements.index', compact('announcements', 'unreadCount', 'student'));
    }

    /**
     * Show specific announcement
     */
    public function show(Announcement $announcement)
    {
        $student = Auth::guard('student')->user();
        
        if (!$announcement->is_active) {
            abort(404, 'Announcement not found.');
        }
        
        // Mark as read if not already
        $reading = AnnouncementReading::firstOrCreate([
            'announcement_id' => $announcement->id,
            'student_id' => $student->id,
        ], [
            'read_at' => now(),
        ]);
        
        return view('student.announcements.show', compact('announcement', 'student'));
    }

    /**
     * Mark announcement as read
     */
    public function markAsRead(Announcement $announcement)
    {
        $student = Auth::guard('student')->user();
        
        AnnouncementReading::firstOrCreate([
            'announcement_id' => $announcement->id,
            'student_id' => $student->id,
        ], [
            'read_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }
}