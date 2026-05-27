<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('roles:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('roles:create');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('roles:update') && $role->name !== 'super-admin';
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('roles:destroy') && $role->name !== 'super-admin';
    }
}
