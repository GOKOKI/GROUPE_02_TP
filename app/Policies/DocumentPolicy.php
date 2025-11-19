<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
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
    public function view(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isProfessor()) {
            if ($document->user_id === $user->id) {
                return true;
            }

            if ($document->documentable_type === 'App\\Models\\Professor') {
                $professor = $document->documentable;

                return $professor && $professor->department_id === $user->professor->department_id;
            }

            if ($document->documentable_type === 'App\\Models\\Student') {
                $student = $document->documentable;

                return $student && $student->department_id === $user->professor->department_id;
            }
        }

        if ($user->isStudent()) {
            return $document->documentable_type === 'App\\Models\\Student' &&
                $document->documentable_id === $user->student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isProfessor() || $user->isStudent();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $document->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Document $document): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $document->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Document $document): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return false;
    }
}
