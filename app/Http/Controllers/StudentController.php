<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Student::class);
        
        $query = Student::with(['courses', 'enrollments']);
        
        // Handle search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }
        
        $students = $query->orderBy('created_at', 'desc')->paginate(12);
        
        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $this->authorize('view', $student);
        
        $student->load(['courses.enrollments', 'enrollments.course']);
        
        return view('admin.students.show', compact('student'));
    }

    public function create()
    {
        $this->authorize('create', Student::class);
        
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Student::class);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'date_of_birth' => 'required|date|before:today',
        ]);

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student created successfully!');
    }

    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'date_of_birth' => 'required|date|before:today',
        ]);

        $student->update($validated);

        return redirect()->route('students.show', $student)
            ->with('success', 'Student updated successfully!');
    }

    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }
}