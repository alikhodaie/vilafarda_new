<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Variable;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariablePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('home-variables:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('home-variables:create');
    }

    public function update(User $user, Variable $variable): bool
    {
        return $user->hasPermissionTo('home-variables:update');
    }

    public function delete(User $user, Variable $variable): bool
    {
        return $user->hasPermissionTo('home-variables:destroy');
    }
}
