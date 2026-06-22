<?php

namespace App\Services;

use App\Models\Home;
use App\Models\Order;
use App\Support\PerformanceTier;

class HomeProfileStatsService
{
    public function recalculateForHome(Home $home): void
    {
        $this->recalculateOrderResponse($home);
        $this->recalculateGuestReviewTier($home);
    }

    public function recalculateOrderResponse(Home $home): void
    {
        $accepted = Order::query()
            ->where('home_id', $home->id)
            ->whereNotNull('accepted_at')
            ->count();

        $rejected = Order::query()
            ->where('home_id', $home->id)
            ->where('status', Order::REJECTED)
            ->count();

        $approvalPercent = PerformanceTier::percentFromCounts($accepted, $rejected);

        Home::query()
            ->whereKey($home->id)
            ->update([
                'orders_accepted_count' => $accepted,
                'orders_rejected_count' => $rejected,
                'order_response_tier' => PerformanceTier::fromPercent($approvalPercent),
            ]);
    }

    public function recalculateGuestReviewTier(Home $home): void
    {
        $home->refresh();

        $score = $home->hasGuestReviews() ? (float) $home->score : null;

        Home::query()
            ->whereKey($home->id)
            ->update([
                'guest_review_tier' => PerformanceTier::fromRatingScore($score),
            ]);
    }
}
