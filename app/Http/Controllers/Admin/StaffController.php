<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of staff members.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'tutor']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && in_array($request->role, ['admin', 'tutor'])) {
            $query->where('role', $request->role);
        }

        $staff = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total_staff' => User::whereIn('role', ['admin', 'tutor'])->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_tutors' => User::where('role', 'tutor')->count(),
            'recent_staff' => User::whereIn('role', ['admin', 'tutor'])
                ->where('created_at', '>=', now()->subDays(30))->count()
        ];

        return view('admin.staff.index', compact('staff', 'stats'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        return view('admin.staff.create');
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,tutor',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member created successfully!');
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        if (!in_array($staff->role, ['admin', 'tutor'])) {
            abort(404);
        }

        return view('admin.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        if (!in_array($staff->role, ['admin', 'tutor'])) {
            abort(404);
        }

        return view('admin.staff.edit', compact('staff'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff)
    {
        if (!in_array($staff->role, ['admin', 'tutor'])) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $staff->id,
            'role' => 'required|in:admin,tutor',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validated['password']) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);

        return redirect()->route('admin.staff.show', $staff)
            ->with('success', 'Staff member updated successfully!');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(User $staff)
    {
        if (!in_array($staff->role, ['admin', 'tutor'])) {
            abort(404);
        }

        // Prevent deleting the last admin
        if ($staff->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.staff.index')
                ->with('error', 'Cannot delete the last admin user.');
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Staff member deleted successfully!');
    }
}