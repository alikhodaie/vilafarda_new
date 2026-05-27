<?php

namespace App\Policies;

use App\Models\Health;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HealthPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('home-healths:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('home-healths:create');
    }

    public function update(User $user, Health $health): bool
    {
        return $user->hasPermissionTo('home-healths:update');
    }

    public function delete(User $user, Health $health): bool
    {
        return $user->hasPermissionTo('home-healths:destroy');
    }
}
