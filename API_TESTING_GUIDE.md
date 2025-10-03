# Laravel Course Enrollment System - API Testing Guide

This guide shows how to test the API endpoints using curl commands or any HTTP client.

## Base URL
```
http://localhost:8000/api
```

## 1. Create Sample Data

First, start the Laravel server:
```bash
php artisan serve
```

## 2. Authentication Endpoints

### Register a New Student
```bash
curl.exe -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe", 
    "email": "john.doe@example.com",
    "date_of_birth": "1995-05-15"
  }'
```

Response:
```json
{
  "message": "Student created successfully",
  "student": {
    "id": 1,
    "full_name": "John Doe",
    "email": "john.doe@example.com"
  },
  "token": "1|abc123def456..."
}
```

### Login Existing Student
```bash
curl.exe -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "password"
  }'
```

## 3. Public Endpoints (No Authentication Required)

### Get All Students (Paginated)
```bash
curl.exe -X GET http://localhost:8000/api/students
```

### Get Specific Student with Courses
```bash
curl.exe -X GET http://localhost:8000/api/students/1
```

### Get All Courses
```bash
curl.exe -X GET http://localhost:8000/api/courses
```

### Create a New Course
```bash
curl.exe -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "course_code": "CS301", 
    "course_name": "Advanced Computer Science",
    "credits": 3
  }'
```

## 4. Protected Endpoints (Authentication Required)

**Note: Replace `YOUR_TOKEN_HERE` with the actual token from registration/login**

### Get My Courses (Authenticated Student)
```bash
curl.exe -X GET http://localhost:8000/api/my-courses \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Enroll in a Course
```bash
curl.exe -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "student_id": 1,
    "course_id": 1
  }'
```

### Try to Enroll Again (Should Fail)
```bash
curl.exe -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "student_id": 1,
    "course_id": 1
  }'
```

Expected response:
```json
{
  "message": "Already enrolled"
}
```

### Logout
```bash
curl.exe -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## 5. Error Testing

### Try Enrollment Without Authentication
```bash
curl.exe -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "course_id": 1
  }'
```

Expected: 401 Unauthorized

### Try Enrollment with Invalid Data
```bash
curl.exe -X POST http://localhost:8000/api/enrollments \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "student_id": 999,
    "course_id": 1
  }'
```

Expected: 422 Validation Error

## 6. Form Request Validation Testing

### Create Course with Invalid Data
```bash
# Missing required fields
curl.exe -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "course_name": "Test Course"
  }'
```

### Create Course with Invalid Credits
```bash
curl.exe -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "course_code": "TEST101",
    "course_name": "Test Course",
    "credits": 10
  }'
```

Expected: 422 Validation Error (credits max 6)

### Create Course with Duplicate Code
```bash
# First create a course, then try to create another with same code
curl.exe -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "course_code": "DUP101",
    "course_name": "Duplicate Course",
    "credits": 3
  }'

# This should fail
curl.exe -X POST http://localhost:8000/api/courses \
  -H "Content-Type: application/json" \
  -d '{
    "course_code": "DUP101",
    "course_name": "Another Course",
    "credits": 4
  }'
```

Expected: 422 Validation Error (course_code must be unique)

## Sample Workflow

1. Register as a student
2. Create some courses
3. Enroll in courses
4. View your enrolled courses
5. Try to enroll in the same course again (should fail)
6. View student details with courses