<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('orders:index');
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->hasPermissionTo('orders:status');
    }

    public function showContract(User $user, Order $order): bool
    {
        return $this->index($user) && !$order->isPreContract();
    }
}
