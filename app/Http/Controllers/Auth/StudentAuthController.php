<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StudentAuthController extends Controller
{
    /**
     * Display the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.signup');
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $student = Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('student')->login($student);

        return redirect()->route('student.dashboard.analytics')->with('success', 'Welcome to our learning community! Your account has been created successfully.');
    }

    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.signin');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('student.dashboard.analytics'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the student dashboard (redirects to new analytics dashboard).
     */
    public function dashboard()
    {
        return redirect()->route('student.dashboard.analytics');
    }
}