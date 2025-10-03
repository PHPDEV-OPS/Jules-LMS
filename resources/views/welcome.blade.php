@extends('layouts.app')

@section('title', 'Welcome - Learning Management System')

@section('content')
<div class="container">
    <h1>Welcome to the Learning Management System</h1>
    <p>This is a fresh start! You can now build your frontend from scratch.</p>
    
    <div class="navigation">
        <h2>Available Routes:</h2>
        <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            @if(Route::has('courses.index'))
                <li><a href="{{ route('courses.index') }}">Courses</a></li>
            @endif
            @if(Route::has('students.index'))
                <li><a href="{{ route('students.index') }}">Students</a></li>
            @endif
            @if(Route::has('enrollments.index'))
                <li><a href="{{ route('enrollments.index') }}">Enrollments</a></li>
            @endif
            @if(Route::has('login'))
                <li><a href="{{ route('login') }}">Login</a></li>
            @endif
            @if(Route::has('student.register'))
                <li><a href="{{ route('student.register') }}">Register</a></li>
            @endif
        </ul>
    </div>
</div>

<style>
/* Minimal starter styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f5f5f5;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

h1 {
    color: #333;
    border-bottom: 3px solid #007bff;
    padding-bottom: 10px;
}

h2 {
    color: #555;
    margin-top: 30px;
}

.navigation ul {
    list-style: none;
    padding: 0;
}

.navigation li {
    margin: 10px 0;
}

.navigation a {
    color: #007bff;
    text-decoration: none;
    padding: 8px 16px;
    border: 1px solid #007bff;
    border-radius: 4px;
    display: inline-block;
    transition: all 0.3s ease;
}

.navigation a:hover {
    background-color: #007bff;
    color: white;
}
</style>
@endsection