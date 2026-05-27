<?php


namespace App\Policies\User;


use App\Models\User;

trait AdminPolicy
{
    public function adminIndex(User $user): bool
    {
        return $user->hasPermissionTo('admins:index');
    }

    public function adminCreate(User $user): bool
    {
        return $user->hasPermissionTo('admins:create');
    }

    public function adminUpdate(User $user, User $model): bool
    {
        return $user->id === $model->id || ($user->hasPermissionTo('admins:update') && (! $model->hasRole('super-admin') || $model->id === $user->id) && $model->isAdmin());
    }

    public function adminBlock(User $user, User $model): bool
    {
        return $user->hasPermissionTo('admins:block') && ! $model->hasRole('super-admin') && $model->isAdmin();
    }

    public function adminAssignRole(User $user): bool
    {
        return $user->hasPermissionTo('admins:assignRole');
    }

    public function adminUpdateRole(User $user, User $model): bool
    {
        return $user->hasPermissionTo('admins:updateRole') && ! $model->hasRole('super-admin') && $model->isAdmin();
    }

    public function adminDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('admins:destroy') && $user->id !== $model->id && ! $model->hasRole('super-admin') && $model->isAdmin();
    }
}
