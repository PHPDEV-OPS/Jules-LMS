<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users with different roles
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);
        
        $this->tutor = User::factory()->create([
            'email' => 'tutor@test.com', 
            'role' => 'tutor'
        ]);
        
        $this->student = Student::factory()->create([
            'email' => 'student@test.com',
            'role' => 'learner'
        ]);
        
        $this->course = Course::factory()->create();
        $this->enrollment = Enrollment::factory()->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id
        ]);
    }

    /** @test */
    public function admin_can_access_all_resources()
    {
        $this->actingAs($this->admin);
        
        // Test admin can access students index
        $response = $this->get(route('students.index'));
        $response->assertStatus(200);
        
        // Test admin can access courses management
        $response = $this->get(route('courses.create'));
        $response->assertStatus(200);
        
        // Test admin can access enrollments
        $response = $this->get(route('enrollments.index'));
        $response->assertStatus(200);
        
        // Test admin can access admin dashboard
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function tutor_has_limited_access()
    {
        $this->actingAs($this->tutor);
        
        // Test tutor can view students
        $response = $this->get(route('students.index'));
        $response->assertStatus(200);
        
        // Test tutor can view enrollments
        $response = $this->get(route('enrollments.index'));
        $response->assertStatus(200);
        
        // Test tutor cannot create courses (admin only)
        $response = $this->get(route('courses.create'));
        $response->assertStatus(403);
        
        // Test tutor can access admin dashboard
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }

    /** @test */
    public function student_can_only_access_own_data()
    {
        $this->actingAs($this->student, 'student');
        
        // Test student can access their dashboard
        $response = $this->get(route('student.dashboard'));
        $response->assertStatus(200);
        
        // Test student can view their own enrollment
        $response = $this->get(route('student.enrollment.details', $this->enrollment));
        $response->assertStatus(200);
        
        // Test student can enroll in courses
        $newCourse = Course::factory()->create();
        $response = $this->post(route('student.enroll'), [
            'course_id' => $newCourse->id
        ]);
        $response->assertStatus(302); // Redirect after successful enrollment
        
        // Verify enrollment was created
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $newCourse->id,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function student_cannot_access_admin_areas()
    {
        $this->actingAs($this->student, 'student');
        
        // Test student cannot access students management (different guard, should redirect)
        $response = $this->get(route('students.index'));
        $response->assertStatus(302); // Redirect because wrong guard
        
        // Test student cannot access enrollments management
        $response = $this->get(route('enrollments.index'));
        $response->assertStatus(302); // Redirect because wrong guard
        
        // Test student cannot access admin dashboard
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(302); // Redirect because wrong guard
    }

    /** @test */
    public function unauthenticated_users_can_access_public_pages()
    {
        // Test unauthenticated users can access public courses
        $response = $this->get(route('courses.index'));
        $response->assertStatus(200);
        
        // Test unauthenticated access to protected routes gets redirected
        $response = $this->get(route('students.index'));
        $response->assertStatus(302); // Should redirect to login
        
        $response = $this->get(route('admin.dashboard'));
        $response->assertStatus(302); // Should redirect to login
    }

    /** @test */
    public function student_can_drop_own_enrollment()
    {
        $this->actingAs($this->student, 'student');
        
        // Test student can drop their enrollment
        $response = $this->patch(route('student.drop', $this->enrollment));
        $response->assertStatus(302); // Redirect after successful drop
        
        // Verify enrollment status was updated to dropped
        $this->assertDatabaseHas('enrollments', [
            'id' => $this->enrollment->id,
            'status' => 'dropped'
        ]);
    }

    /** @test */
    public function admin_login_works()
    {
        // Test admin can login through admin login page
        $response = $this->post(route('admin.login'), [
            'email' => $this->admin->email,
            'password' => 'password'
        ]);
        
        $response->assertStatus(302); // Should redirect after login
        $this->assertAuthenticatedAs($this->admin);
    }

    /** @test */
    public function student_login_works()
    {
        // Test student can login through student login page
        $response = $this->post(route('login'), [
            'email' => $this->student->email,
            'password' => 'password'
        ]);
        
        $response->assertStatus(302); // Should redirect after login
        $this->assertAuthenticatedAs($this->student, 'student');
    }
}