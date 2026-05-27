<?php

namespace App\Policies;

use App\Models\Navbar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NavbarPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('navbar:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('navbar:create');
    }

    public function update(User $user, Navbar $navbar): bool
    {
        return $user->hasPermissionTo('navbar:update');
    }

    public function delete(User $user, Navbar $navbar): bool
    {
        return $user->hasPermissionTo('navbar:destroy');
    }
}
