<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display student profile
     */
    public function show()
    {
        $student = Auth::guard('student')->user();
        
        // Get enrollment statistics
        $enrollmentStats = [
            'total' => $student->enrollments()->count(),
            'active' => $student->enrollments()->where('status', 'active')->count(),
            'completed' => $student->enrollments()->where('status', 'completed')->count(),
            'certificates' => $student->certificates()->where('status', 'issued')->count(),
        ];
        
        // Get recent activity
        $recentEnrollments = $student->enrollments()
            ->with('course')
            ->latest()
            ->take(5)
            ->get();
            
        return view('student.profile.show', compact('student', 'enrollmentStats', 'recentEnrollments'));
    }

    /**
     * Show profile edit form
     */
    public function edit()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile.edit', compact('student'));
    }

    /**
     * Update student profile
     */
    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('students')->ignore($student->id),
            ],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($student->avatar) {
                Storage::disk('public')->delete($student->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $student->update($validated);

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Change password form
     */
    public function passwordForm()
    {
        return view('student.profile.password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Auth::guard('student')->user();

        // Verify current password
        if (!Hash::check($validated['current_password'], $student->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        // Update password
        $student->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('student.profile.show')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $student = Auth::guard('student')->user();
        
        if ($student->avatar) {
            Storage::disk('public')->delete($student->avatar);
            $student->update(['avatar' => null]);
        }

        return response()->json(['success' => true]);
    }
}