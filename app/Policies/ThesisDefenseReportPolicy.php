<?php

namespace App\Policies;

use App\Models\ThesisDefenseReport;
use App\Models\User;

class ThesisDefenseReportPolicy
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
    public function view(User $user, ThesisDefenseReport $thesisDefenseReport): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProfessor()) {
            $isSupervisor = $thesisDefenseReport->supervisor_id === $user->professor->id;
            $isJuryMember = $thesisDefenseReport->juryMembers->contains('professor_id', $user->professor->id);

            return $isSupervisor || $isJuryMember;
        }

        if ($user->isStudent()) {
            return $thesisDefenseReport->student_id === $user->student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ThesisDefenseReport $thesisDefenseReport): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProfessor()) {
            return $thesisDefenseReport->supervisor_id === $user->professor->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ThesisDefenseReport $thesisDefenseReport): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ThesisDefenseReport $thesisDefenseReport): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ThesisDefenseReport $thesisDefenseReport): bool
    {
        return false;
    }
}
