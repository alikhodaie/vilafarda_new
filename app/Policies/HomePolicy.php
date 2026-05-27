<?php

namespace App\Policies;

use App\Models\Home;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('homes:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('homes:create');
    }

    public function update(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('homes:update') && ! $home->is_draft;
    }

    public function delete(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('homes:destroy') && ! $home->is_draft;
    }

    public function showDate(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('home-dates:show');
    }

    public function updateDate(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('home-dates:update');
    }

    public function updateFastReserve(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('home-dates:update-fast-reserve');
    }

    public function deleteDate(User $user, Home $home): bool
    {
        return $user->hasPermissionTo('home-dates:destroy');
    }
}
