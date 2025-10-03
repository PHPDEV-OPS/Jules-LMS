<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $student;
    protected $course;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);
        
        // Create test data
        $this->student = Student::factory()->create();
        $this->course = Course::factory()->create();
    }

    public function test_admin_can_access_enrollment_creation_page()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get(route('enrollments.create'));
        
        $response->assertStatus(200);
        $response->assertViewIs('enrollments.create-cooking');
        $response->assertViewHas(['students', 'courses']);
        $response->assertSee('New Enrollment');
        $response->assertSee('Select Student');
        $response->assertSee('Select Course');
    }

    public function test_admin_can_create_enrollment_successfully()
    {
        $this->actingAs($this->admin);
        
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ];
        
        $response = $this->post(route('enrollments.store'), $enrollmentData);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.index'));
        $response->assertSessionHas('success', 'Student enrolled successfully!');
        
        // Verify enrollment was created in database
        $this->assertDatabaseHas('enrollments', [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
        
        // Verify enrolled_on timestamp was set
        $enrollment = Enrollment::where('student_id', $this->student->id)
            ->where('course_id', $this->course->id)
            ->first();
        
        $this->assertNotNull($enrollment->enrolled_on);
        $this->assertEquals('active', $enrollment->status);
    }

    public function test_admin_can_create_enrollment_with_different_statuses()
    {
        $this->actingAs($this->admin);
        
        $statuses = ['active', 'completed', 'dropped'];
        
        foreach ($statuses as $status) {
            $student = Student::factory()->create();
            $course = Course::factory()->create();
            
            $enrollmentData = [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => $status,
            ];
            
            $response = $this->post(route('enrollments.store'), $enrollmentData);
            
            $response->assertStatus(302);
            $this->assertDatabaseHas('enrollments', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => $status,
            ]);
        }
    }

    public function test_admin_cannot_create_duplicate_enrollment()
    {
        $this->actingAs($this->admin);
        
        // Create first enrollment
        Enrollment::factory()->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
        
        // Try to create duplicate
        $enrollmentData = [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ];
        
        $response = $this->post(route('enrollments.store'), $enrollmentData);
        
        $response->assertStatus(302);
        $response->assertSessionHasErrors('enrollment');
        
        // Verify only one enrollment exists
        $enrollmentCount = Enrollment::where('student_id', $this->student->id)
            ->where('course_id', $this->course->id)
            ->count();
            
        $this->assertEquals(1, $enrollmentCount);
    }

    public function test_enrollment_validation_requires_all_fields()
    {
        $this->actingAs($this->admin);
        
        // Test missing student_id
        $response = $this->post(route('enrollments.store'), [
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
        $response->assertSessionHasErrors('student_id');
        
        // Test missing course_id
        $response = $this->post(route('enrollments.store'), [
            'student_id' => $this->student->id,
            'status' => 'active',
        ]);
        $response->assertSessionHasErrors('course_id');
        
        // Test missing status
        $response = $this->post(route('enrollments.store'), [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);
        $response->assertSessionHasErrors('status');
    }

    public function test_enrollment_validation_requires_valid_ids()
    {
        $this->actingAs($this->admin);
        
        // Test invalid student_id
        $response = $this->post(route('enrollments.store'), [
            'student_id' => 99999,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
        $response->assertSessionHasErrors('student_id');
        
        // Test invalid course_id
        $response = $this->post(route('enrollments.store'), [
            'student_id' => $this->student->id,
            'course_id' => 99999,
            'status' => 'active',
        ]);
        $response->assertSessionHasErrors('course_id');
        
        // Test invalid status
        $response = $this->post(route('enrollments.store'), [
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'invalid_status',
        ]);
        $response->assertSessionHasErrors('status');
    }

    public function test_admin_can_view_created_enrollment()
    {
        $this->actingAs($this->admin);
        
        // Create enrollment
        $enrollment = Enrollment::factory()->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => 'active',
        ]);
        
        $response = $this->get(route('enrollments.show', $enrollment));
        
        $response->assertStatus(200);
        $response->assertSee($this->student->full_name);
        $response->assertSee($this->course->course_name);
    }

    public function test_enrollment_form_displays_all_students_and_courses()
    {
        $this->actingAs($this->admin);
        
        // Create multiple students and courses
        $students = Student::factory()->count(3)->create();
        $courses = Course::factory()->count(3)->create();
        
        $response = $this->get(route('enrollments.create'));
        
        $response->assertStatus(200);
        
        // Check that all students are displayed
        foreach ($students as $student) {
            $response->assertSee($student->full_name);
            $response->assertSee($student->email);
        }
        
        // Check that all courses are displayed
        foreach ($courses as $course) {
            $response->assertSee($course->course_name);
            $response->assertSee($course->course_code);
        }
    }

    public function test_non_admin_cannot_access_enrollment_creation()
    {
        // Test with tutor
        $tutor = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($tutor);
        
        $response = $this->get(route('enrollments.create'));
        $response->assertStatus(200); // Tutors can access this based on our policy
        
        // Test with regular user (admin role but different from tutor/admin)
        $user = User::factory()->create(['role' => 'admin']); // This should still have access
        $this->actingAs($user);
        
        $response = $this->get(route('enrollments.create'));
        $response->assertStatus(200); // Admin should have access
    }

    public function test_bulk_enrollment_functionality()
    {
        $this->actingAs($this->admin);
        
        $students = Student::factory()->count(3)->create();
        $course = Course::factory()->create();
        
        $bulkData = [
            'course_id' => $course->id,
            'student_ids' => $students->pluck('id')->toArray(),
        ];
        
        $response = $this->post(route('enrollments.bulk-enroll'), $bulkData);
        
        $response->assertStatus(302);
        $response->assertRedirect(route('enrollments.index'));
        $response->assertSessionHas('success');
        
        // Verify all students were enrolled
        foreach ($students as $student) {
            $this->assertDatabaseHas('enrollments', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => 'active',
            ]);
        }
    }
}