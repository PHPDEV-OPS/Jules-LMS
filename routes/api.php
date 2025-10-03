<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('auth/register', [AuthController::class, 'createStudentToken']);
Route::post('auth/login', [AuthController::class, 'login']);

// Public API routes
Route::apiResource('students', StudentController::class)->only(['index', 'show'])->names([
    'index' => 'api.students.index',
    'show' => 'api.students.show'
]);
Route::apiResource('courses', CourseController::class)->names([
    'index' => 'api.courses.index',
    'store' => 'api.courses.store', 
    'show' => 'api.courses.show',
    'update' => 'api.courses.update',
    'destroy' => 'api.courses.destroy'
]);



// Check for duplicate enrollments
Route::get('enrollments/check-duplicate', [EnrollmentController::class, 'checkDuplicate']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('auth/logout', [AuthController::class, 'logout']);
    
    // GET /api/my-courses
    Route::get('my-courses', [StudentController::class, 'myCourses']);
    
    // Enrollment routes
    Route::get('enrollments', [EnrollmentController::class, 'index']);
    Route::post('enrollments', [EnrollmentController::class, 'store']);
    
    // Additional
    Route::apiResource('enrollments', EnrollmentController::class)->except(['store'])->names([
        'index' => 'api.enrollments.index',
        'show' => 'api.enrollments.show',
        'update' => 'api.enrollments.update',
        'destroy' => 'api.enrollments.destroy'
    ]);
});