<?php

namespace App\Policies;

use App\Models\Option;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeOptionPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('home-options:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('home-options:create');
    }

    public function update(User $user, Option $option): bool
    {
        return $user->hasPermissionTo('home-options:update');
    }

    public function delete(User $user, Option $option): bool
    {
        return $user->hasPermissionTo('home-options:destroy');
    }
}
