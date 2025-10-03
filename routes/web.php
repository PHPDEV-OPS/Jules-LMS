<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Student Authentication Routes
Route::get('register', [StudentAuthController::class, 'showRegistrationForm'])->name('student.register');
Route::post('register', [StudentAuthController::class, 'register']);
Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [StudentAuthController::class, 'login']);
Route::post('logout', [StudentAuthController::class, 'logout'])->name('student.logout');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        // Admin Course Management
        Route::resource('courses', AdminCourseController::class);
        Route::post('courses/{course}/toggle-status', [AdminCourseController::class, 'toggleStatus'])->name('courses.toggle-status');
        Route::post('courses/{course}/duplicate', [AdminCourseController::class, 'duplicate'])->name('courses.duplicate');
        
        // Admin Analytics
        Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');
        Route::get('reports', [App\Http\Controllers\Admin\AnalyticsController::class, 'reports'])->name('reports');
    });
});

// Protected Student Routes (Legacy - redirects to new dashboard)
Route::middleware(['auth:student', 'learner'])->group(function () {
    Route::get('dashboard', function() {
        return redirect()->route('student.dashboard.analytics');
    })->name('legacy.student.dashboard');
});

// Admin and Tutor shared routes (both can access)
Route::middleware(['auth'])->group(function () {
    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/{student}', [StudentController::class, 'show'])->name('students.show');
    
    Route::get('enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::get('enrollments/export', [EnrollmentController::class, 'export'])->name('enrollments.export');
    Route::get('enrollments/create', [EnrollmentController::class, 'create'])->name('enrollments.create');
    Route::post('enrollments', [EnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('enrollments/{enrollment}', [EnrollmentController::class, 'show'])->name('enrollments.show');
    Route::get('enrollments/{enrollment}/edit', [EnrollmentController::class, 'edit'])->name('enrollments.edit');
    Route::put('enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update');
    Route::patch('enrollments/{enrollment}', [EnrollmentController::class, 'update'])->name('enrollments.update.patch');
    Route::post('enrollments/bulk-update', [EnrollmentController::class, 'bulkUpdate'])->name('enrollments.bulk-update');
    
    // Enrollment status updates
    Route::patch('enrollments/{enrollment}/complete', [EnrollmentController::class, 'complete'])->name('enrollments.complete');
    Route::patch('enrollments/{enrollment}/suspend', [EnrollmentController::class, 'suspend'])->name('enrollments.suspend');
    Route::patch('enrollments/{enrollment}/reactivate', [EnrollmentController::class, 'reactivate'])->name('enrollments.reactivate');
    Route::patch('enrollments/{enrollment}/drop', [EnrollmentController::class, 'drop'])->name('enrollments.drop');
});

// Admin-only routes (full CRUD access)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('students', StudentController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('courses', CourseController::class)->except(['index', 'show']);
    Route::delete('enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
});

// Public Course routes (anyone can view courses)
Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

// Student routes (self-enrollment and viewing own enrollments)
Route::middleware(['auth:student', 'learner'])->prefix('student')->name('student.')->group(function () {
    // Legacy dashboard route (redirect to analytics)
    Route::get('dashboard', function() {
        return redirect()->route('student.dashboard.analytics');
    })->name('dashboard');
    
    // New Analytics Dashboard
    Route::get('dashboard/analytics', [StudentDashboard::class, 'analytics'])->name('dashboard.analytics');
    
    // Enrollment actions
    Route::post('enroll', [StudentDashboard::class, 'enroll'])->name('enroll');
    Route::patch('enrollments/{enrollment}/drop', [StudentDashboard::class, 'drop'])->name('drop');
    Route::get('enrollments/{enrollment}', [StudentDashboard::class, 'viewEnrollment'])->name('enrollment.details');
});


