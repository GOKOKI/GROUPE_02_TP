<?php

namespace App\Policies;

use App\Models\Professor;
use App\Models\User;

class ProfessorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Professor $professor): bool
    {
        return $user->isAdmin() ||
               ($user->isProfessor() && $user->id === $professor->user_id) ||
               $user->isStudent();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Professor $professor): bool
    {
        return $user->isAdmin() ||
               ($user->isProfessor() && $user->id === $professor->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Professor $professor): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Professor $professor): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Professor $professor): bool
    {
        return false;
    }
}
