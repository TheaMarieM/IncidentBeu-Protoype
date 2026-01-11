<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    /**
     * Determine if the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal',
            'adviser'
        ]);
    }

    /**
     * Determine if the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Advisers can only view their own students
        if ($user->role?->name === 'adviser') {
            return $student->adviser_id === $user->id;
        }

        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can create students.
     */
    public function create(User $user): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal'
        ]);
    }
}
