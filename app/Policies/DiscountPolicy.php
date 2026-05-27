<?php

namespace App\Policies;

use App\Models\Discount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->checkPermissionTo('discounts:index');
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo('discounts:create');
    }

    public function update(User $user, Discount $discount): bool
    {
        return $user->checkPermissionTo('discounts:update');
    }

    public function delete(User $user, Discount $discount): bool
    {
        return $user->checkPermissionTo('discounts:destroy');
    }
}
