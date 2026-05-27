<?php

namespace App\Services;

use App\Models\Home;
use App\Models\Order;

class RentReviewService
{
    public function __construct(
        protected OrderShowPresenter $presenter
    ) {}

    public function hasReviewed(Home $home, int $renterId): bool
    {
        return $home->comments()
            ->where('user_id', $renterId)
            ->whereNull('parent_id')
            ->exists();
    }

    public function canSubmit(Order $order, int $renterId): bool
    {
        if ((int) $order->renter_id !== $renterId) {
            return false;
        }

        if ($this->presenter->isUnsuccessful($order)) {
            return false;
        }

        if (! $this->presenter->checkoutPassed($order)) {
            return false;
        }

        return ! $this->hasReviewed($order->home, $renterId);
    }
}
