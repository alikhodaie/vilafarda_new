<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderDeadlineService
{
    public function expireOrderIfOverdue(Order $order): Order
    {
        return $this->syncOrderLifecycle($order);
    }

    public function syncOrderLifecycle(Order $order): Order
    {
        $order->expireIfOverdue();
        $order->advanceStatusIfDue();
        $order->refresh();

        return $order;
    }

    /**
     * @param  Collection<int, Order>|array<int, Order>  $orders
     */
    public function expireCollectionIfOverdue(Collection|array $orders): void
    {
        foreach ($orders as $order) {
            $this->syncOrderLifecycle($order);
        }
    }
}
