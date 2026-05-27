<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Auth\Access\HandlesAuthorization;

class WithdrawPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {
        return $user->hasPermissionTo('withdraws:index');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('withdraws:create');
    }

    public function update(User $user, Withdraw $withdraw)
    {
        return $user->hasPermissionTo('withdraws:update');
    }
}
