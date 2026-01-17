<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class RolePolicy
{
    public function before(User $user, string $ability)
    {
        return $user->roles()->where('name', 'superadmin')->exists() ?: null;
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission('roles.view');
    }

    public function create(User $user)
    {
        return $user->hasPermission('roles.create');
    }

    public function update(User $user)
    {
        return $user->hasPermission('roles.edit');
    }

    public function delete(User $user)
    {
        return $user->hasPermission('roles.delete');
    }
}
