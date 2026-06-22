<?php

namespace App\Services;

use App\Models\Home;
use App\Models\User;
use App\Support\PerformanceTier;

class HostAdminProfileService
{
    public function buildForHome(Home $home, ?User $viewer = null): array
    {
        $viewer = $viewer ?? auth()->user();
        $host = $home->user;

        return [
            'home_id' => $home->id,
            'home_name' => $home->name,
            'id' => $host->id,
            'name' => $host->full_name,
            'mobile' => $host->mobile,
            'email' => $host->email,
            'edit_url' => ($viewer && $viewer->can('update', $host))
                ? route('admin.users.edit', $host)
                : null,
            'order_response' => $this->orderResponseFromHome($home),
            'guest_reviews' => $this->guestReviewsFromHome($home),
        ];
    }

    /** @deprecated Use buildForHome() — stats are per-home, not per-host. */
    public function build(User $host, ?User $viewer = null): array
    {
        $home = $host->homes()->where('is_draft', false)->latest('id')->first();

        if ($home) {
            return $this->buildForHome($home, $viewer);
        }

        $viewer = $viewer ?? auth()->user();

        return [
            'home_id' => null,
            'home_name' => null,
            'id' => $host->id,
            'name' => $host->full_name,
            'mobile' => $host->mobile,
            'email' => $host->email,
            'edit_url' => ($viewer && $viewer->can('update', $host))
                ? route('admin.users.edit', $host)
                : null,
            'order_response' => $this->emptyOrderResponse(),
            'guest_reviews' => $this->emptyGuestReviews(),
        ];
    }

    private function orderResponseFromHome(Home $home): array
    {
        $accepted = (int) $home->orders_accepted_count;
        $rejected = (int) $home->orders_rejected_count;
        $total = $accepted + $rejected;
        $approvalPercent = PerformanceTier::percentFromCounts($accepted, $rejected);
        $rejectionPercent = $total > 0 ? (int) round($rejected / $total * 100) : null;
        $tier = $home->order_response_tier;

        return [
            'accepted' => $accepted,
            'rejected' => $rejected,
            'total' => $total,
            'approval_percent' => $approvalPercent,
            'rejection_percent' => $rejectionPercent,
            'approval_percent_display' => PerformanceTier::percentDisplay($approvalPercent),
            'rejection_percent_display' => PerformanceTier::percentDisplay($rejectionPercent),
            'tier' => $tier,
            'tier_label' => PerformanceTier::label($tier),
            'tier_color' => PerformanceTier::color($tier),
        ];
    }

    private function guestReviewsFromHome(Home $home): array
    {
        $count = (int) $home->count_comments;
        $avgScore = $home->hasGuestReviews() ? round((float) $home->score, 1) : null;
        $tier = $home->guest_review_tier;

        return [
            'count' => $count,
            'average_score' => $avgScore,
            'average_score_display' => $avgScore !== null
                ? persianNumber($avgScore, fmod($avgScore, 1.0) ? 1 : 0)
                : null,
            'tier' => $tier,
            'tier_label' => PerformanceTier::label($tier),
            'tier_color' => PerformanceTier::color($tier),
        ];
    }

    private function emptyOrderResponse(): array
    {
        return [
            'accepted' => 0,
            'rejected' => 0,
            'total' => 0,
            'approval_percent' => null,
            'rejection_percent' => null,
            'approval_percent_display' => null,
            'rejection_percent_display' => null,
            'tier' => null,
            'tier_label' => null,
            'tier_color' => 'secondary',
        ];
    }

    private function emptyGuestReviews(): array
    {
        return [
            'count' => 0,
            'average_score' => null,
            'average_score_display' => null,
            'tier' => null,
            'tier_label' => null,
            'tier_color' => 'secondary',
        ];
    }
}
