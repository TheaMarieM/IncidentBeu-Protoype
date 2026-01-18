<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Incident;

class IncidentPolicy
{
    /**
     * Determine if the user can view any incidents.
     */
    public function viewAny(User $user): bool
    {
        return auth()->check();
    }

    /**
     * Determine if the user can view the incident.
     */
    public function view(User $user, Incident $incident): bool
    {
        // Advisers can only view incidents for their students
        if ($user->role?->name === 'adviser') {
            return $incident->students->contains(function ($student) use ($user) {
                return $student->adviser_id === $user->id;
            });
        }

        // Parents can only view incidents for their children
        if ($user->role?->name === 'parent') {
            $parentRecord = \App\Models\ParentModel::where('email', $user->email)->first();
            if ($parentRecord) {
                return $incident->students->intersect($parentRecord->students)->isNotEmpty();
            }
            return false;
        }

        return true;
    }

    /**
     * Determine if the user can create incidents.
     */
    public function create(User $user): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal',
            'adviser'
        ]);
    }

    /**
     * Determine if the user can update the incident.
     */
    public function update(User $user, Incident $incident): bool
    {
        // Only the reporter can update within 24 hours
        if ($incident->reported_by === $user->id && $incident->created_at->gt(now()->subDay())) {
            return true;
        }

        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal',
            'assistant_principal'
        ]);
    }

    /**
     * Determine if the user can delete the incident.
     */
    public function delete(User $user, Incident $incident): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal'
        ]);
    }

    /**
     * Determine if the user can approve the incident.
     */
    public function approve(User $user, Incident $incident): bool
    {
        return in_array($user->role?->name, [
            'discipline_coordinator',
            'principal'
        ]);
    }
}
