<?php

namespace App\Policies;

use App\Models\HostPayout;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HostPayoutPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo('withdraws:index');
    }

    public function create(User $user)
    {
        return false;
    }

    public function update(User $user, HostPayout $hostPayout)
    {
        return $user->hasPermissionTo('withdraws:update');
    }
}
