<?php

namespace App\Services;

use App\Models\HostPayout;
use App\Models\Order;

class HostPayoutService
{
    public function __construct(
        private OrderInvoiceService $orderInvoiceService
    ) {
    }

    public function createForOrder(Order $order): ?HostPayout
    {
        return $this->syncFromOrder($order, HostPayout::PENDING);
    }

    public function cancelForOrder(Order $order): ?HostPayout
    {
        $payout = HostPayout::query()->where('order_id', $order->id)->first();

        if (! $payout || $payout->status === HostPayout::PAID) {
            return $payout;
        }

        $payout->update([
            'status' => HostPayout::CANCELLED,
            'paid_at' => null,
        ]);

        return $payout->fresh();
    }

    public function syncFromOrder(Order $order, string $status): ?HostPayout
    {
        $order->loadMissing('home');

        if (! $order->home || ! $order->paid_at) {
            return null;
        }

        $settlement = $this->orderInvoiceService->hostSettlement($order);
        $hostId = (int) $order->home->user_id;

        $existing = HostPayout::query()->where('order_id', $order->id)->first();

        if ($existing) {
            if ($existing->status === HostPayout::PAID) {
                return $existing;
            }

            $existing->update([
                'user_id' => $hostId,
                'amount' => $settlement['payout_amount'],
                'order_price' => (int) $order->price,
                'commission_percent' => $settlement['commission_percent'],
                'commission_amount' => $settlement['commission_amount'],
                'status' => $status,
                'paid_at' => $status === HostPayout::PAID ? ($existing->paid_at ?? now()) : null,
            ]);

            return $existing->fresh();
        }

        return HostPayout::query()->create([
            'user_id' => $hostId,
            'order_id' => $order->id,
            'amount' => $settlement['payout_amount'],
            'order_price' => (int) $order->price,
            'commission_percent' => $settlement['commission_percent'],
            'commission_amount' => $settlement['commission_amount'],
            'status' => $status,
            'paid_at' => $status === HostPayout::PAID ? now() : null,
        ]);
    }

    public function markPaid(HostPayout $payout): HostPayout
    {
        $payout->update([
            'status' => HostPayout::PAID,
            'paid_at' => now(),
        ]);

        return $payout->fresh();
    }
}
