<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins and tutors can view all students
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Student $student): bool
    {
        // Admins and tutors can view student details
        return $user->isAdmin() || $user->isTutor();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create students (registration is separate)
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Student $student): bool
    {
        // Admins can update any student, tutors can view but not update
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Student $student): bool
    {
        // Only admins can delete students
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Student $student): bool
    {
        // Only admins can restore students
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Student $student): bool
    {
        // Only admins can permanently delete students
        return $user->isAdmin();
    }

    /**
     * Determine whether a student can update their own profile.
     */
    public function updateOwn(Student $currentStudent, Student $student): bool
    {
        // Students can only update their own profile
        return $currentStudent->id === $student->id;
    }

    /**
     * Determine whether a student can view their own profile.
     */
    public function viewOwn(Student $currentStudent, Student $student): bool
    {
        // Students can view their own profile
        return $currentStudent->id === $student->id;
    }
}
