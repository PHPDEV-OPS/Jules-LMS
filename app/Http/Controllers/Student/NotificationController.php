<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display student's notifications
     */
    public function index()
    {
        $student = Auth::guard('student')->user();
        
        // Get notifications
        $notifications = Notification::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Get unread count
        $unreadCount = Notification::where('student_id', $student->id)
            ->where('is_read', false)
            ->count();
        
        return view('student.notifications.index', compact('notifications', 'unreadCount', 'student'));
    }

    /**
     * Show specific notification
     */
    public function show(Notification $notification)
    {
        $student = Auth::guard('student')->user();
        
        if ($notification->student_id !== $student->id) {
            abort(403, 'This notification does not belong to you.');
        }
        
        // Mark as read if not already
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        
        return view('student.notifications.show', compact('notification', 'student'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        $student = Auth::guard('student')->user();
        
        if ($notification->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $student = Auth::guard('student')->user();
        
        Notification::where('student_id', $student->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json(['success' => true]);
    }
}