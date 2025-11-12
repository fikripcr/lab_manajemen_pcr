<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // For now, allow users with admin-like roles
        return $user->hasRole(['kajur', 'ka_lab', 'admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $currentUser, User $user): bool
    {
        // Users can view themselves or users in their lab/with permission
        return $currentUser->id === $user->id || $currentUser->hasRole(['kajur', 'ka_lab', 'admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['kajur', 'ka_lab', 'admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $currentUser, User $user): bool
    {
        // Users can update themselves or others if they have permission
        return $currentUser->id === $user->id || $currentUser->hasRole(['kajur', 'ka_lab', 'admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $currentUser, User $user): bool
    {
        // Only high-level roles can delete users
        return $currentUser->hasRole(['kajur', 'admin']);
    }
}