<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before(User $user, string $ability)
    {
        return $user->roles()->where('name', 'superadmin')->exists() ?: null;
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('users.view');
    }

    public function create(User $user)
    {
        return $user->hasPermission('users.create');
    }

    public function update(User $user)
    {
        return $user->hasPermission('users.edit');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('users.delete');
    }
}
