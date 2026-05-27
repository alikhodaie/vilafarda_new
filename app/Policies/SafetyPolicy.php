<?php

namespace App\Policies;

use App\Models\Safety;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SafetyPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('home-safeties:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('home-safeties:create');
    }

    public function update(User $user, Safety $safety): bool
    {
        return $user->hasPermissionTo('home-safeties:update');
    }

    public function delete(User $user, Safety $safety): bool
    {
        return $user->hasPermissionTo('home-safeties:destroy');
    }
}
