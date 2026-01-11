<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ParentModel;

class ParentPolicy
{
    /**
     * Determine if the user can view any parents.
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
     * Determine if the user can view the parent.
     */
    public function view(User $user, ParentModel $parent): bool
    {
        // Advisers can only view parents of their students
        if ($user->role?->name === 'adviser') {
            return $parent->students->contains(function ($student) use ($user) {
                return $student->adviser_id === $user->id;
            });
        }

        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can create parents.
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
     * Determine if the user can update the parent.
     */
    public function update(User $user, ParentModel $parent): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can delete the parent.
     */
    public function delete(User $user, ParentModel $parent): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal'
        ]);
    }
}
