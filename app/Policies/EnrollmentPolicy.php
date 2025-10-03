<?php

namespace App\Policies;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EnrollmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins and tutors can view all enrollments
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Admins and tutors can view enrollment details
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins and tutors can create enrollments
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        // Admins can update any enrollment, tutors can update status
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Only admins can delete enrollments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        // Only admins can restore enrollments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        // Only admins can permanently delete enrollments
        return $user->isAdmin();
    }

    /**
     * Determine whether a student can view their own enrollments.
     */
    public function viewOwn(Student $student, Enrollment $enrollment): bool
    {
        // Students can view their own enrollments
        return $student->id === $enrollment->student_id;
    }

    /**
     * Determine whether a student can create their own enrollment.
     */
    public function createOwn(Student $student): bool
    {
        // Students can enroll themselves in courses
        return $student->isLearner();
    }

    /**
     * Determine whether a student can update their own enrollment.
     */
    public function updateOwn(Student $student, Enrollment $enrollment): bool
    {
        // Students can drop their own courses (change status to dropped)
        return $student->id === $enrollment->student_id && $student->isLearner();
    }
}
