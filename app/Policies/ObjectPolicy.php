<?php

namespace App\Policies;

use App\Models\Bucket;
use App\Models\Objecto;
use App\Models\User;

class ObjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->id == auth()->user()->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Objecto $objecto): bool
    {
        if ($objecto->visibility == 'pu') {
            return true;
        }

        return $user->id == $objecto->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Bucket $bucket): bool
    {
        return $user->id == $bucket->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Objecto $objecto): bool
    {
        return $user->id == $objecto->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteObject(User $user, Objecto $objecto): bool
    {
        return $user->id == $objecto->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Objecto $objecto): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Objecto $objecto): bool
    {
        return false;
    }

    public function createLink(User $user, Objecto $objecto)
    {
        return $objecto->user_id == $user->id;
    }
}
