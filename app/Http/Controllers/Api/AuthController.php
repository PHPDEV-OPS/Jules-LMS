<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register a new student and return an API token.
     */
    public function createStudentToken(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email|max:255',
            'date_of_birth' => 'required|date|before:today',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $student = Student::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'date_of_birth' => $data['date_of_birth'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $student->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Student registered successfully',
            'student' => [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'date_of_birth' => $student->date_of_birth->format('Y-m-d'),
            ],
            'token' => $token,
        ], 201);
    }

    /**
     * Login a student and return an API token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string',
        ]);

        $student = Student::where('email', $request->email)->first();

        if (!$student || !Hash::check($request->password, $student->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ], 422);
        }

        // Revoke existing tokens for security
        $student->tokens()->delete();
        
        $token = $student->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'student' => [
                'id' => $student->id,
                'full_name' => $student->full_name,
                'email' => $student->email,
                'date_of_birth' => $student->date_of_birth->format('Y-m-d'),
            ],
            'token' => $token,
        ]);
    }

    /**
     * Logout the authenticated student.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}