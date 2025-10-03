<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentWebController extends Controller
{
    /**
     * Display a listing of students for web view.
     */
    public function index()
    {
        $students = Student::with('courses')->paginate(12);
        
        return view('students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load('courses');
        
        return view('students.show', compact('student'));
    }
}