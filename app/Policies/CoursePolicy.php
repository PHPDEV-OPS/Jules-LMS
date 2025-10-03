<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user = null): bool
    {
        // Anyone can view courses (public listing)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user = null, Course $course): bool
    {
        // Anyone can view individual courses (public access)
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Admins can update any course, tutors can update courses they're assigned to
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Only admins can delete courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        // Only admins can restore courses
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        // Only admins can permanently delete courses
        return $user->isAdmin();
    }

    /**
     * Determine whether a student can enroll in the course.
     */
    public function enroll(?Student $student = null, Course $course): bool
    {
        // Students can enroll if they're authenticated
        return $student !== null && $student->isLearner();
    }
}
