<?php

namespace App\Policies\User;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization,
        AdminPolicy;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('users:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users:create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users:update') && !$model->isAdmin();
    }

    public function block(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users:block') && !$model->isAdmin();
    }

    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users:destroy') && $user->id !== $model->id && !$model->isAdmin();
    }
}
