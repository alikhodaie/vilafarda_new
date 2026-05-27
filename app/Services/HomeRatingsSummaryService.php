<?php

namespace App\Services;

use App\Models\Home;
use App\Support\HomeReviewCriteria;
use Illuminate\Support\Collection;

class HomeRatingsSummaryService
{
    public function forHome(Home $home): array
    {
        $comments = $this->recentRatedComments($home);

        $total = $comments->count();
        $distributionCounts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $criteriaSums = array_fill_keys(HomeReviewCriteria::KEYS, 0);
        $criteriaCounts = array_fill_keys(HomeReviewCriteria::KEYS, 0);

        foreach ($comments as $comment) {
            $star = (int) $comment->score;
            if ($star >= 1 && $star <= 5) {
                $distributionCounts[$star]++;
            }

            $details = is_array($comment->rating_details) ? $comment->rating_details : [];
            foreach ($details as $key => $value) {
                if ($key === 'facilities') {
                    $key = HomeReviewCriteria::TIMELY_DELIVERY;
                }

                if (! array_key_exists($key, $criteriaSums) || ! is_numeric($value)) {
                    continue;
                }

                $intValue = (int) $value;
                if ($intValue < 1 || $intValue > 5) {
                    continue;
                }

                $criteriaSums[$key] += $intValue;
                $criteriaCounts[$key]++;
            }
        }

        $criteriaAverages = [];
        foreach (HomeReviewCriteria::KEYS as $key) {
            if ($criteriaCounts[$key] > 0) {
                $criteriaAverages[$key] = round($criteriaSums[$key] / $criteriaCounts[$key], 1);
            }
        }

        $guestCount = (int) $home->count_comments;

        $overall = $total > 0
            ? round((float) $comments->avg('score'), 1)
            : ($home->guestRatingScore() ?? 0.0);

        $distribution = [];
        for ($stars = 5; $stars >= 1; $stars--) {
            $distribution[$stars] = $total > 0
                ? (int) round(($distributionCounts[$stars] / $total) * 100)
                : 0;
        }

        return [
            'has_data' => $home->hasGuestReviews(),
            'total' => $guestCount,
            'overall' => $overall,
            'overall_stars' => max(1, min(5, (int) round($overall))),
            'distribution' => $distribution,
            'criteria' => $criteriaAverages,
            'has_criteria' => $criteriaAverages !== [],
        ];
    }

    protected function recentRatedComments(Home $home): Collection
    {
        return $home->comments()
            ->active()
            ->parents()
            ->where('score', '>', 0)
            ->where('created_at', '>=', now()->subYear())
            ->get(['score', 'rating_details']);
    }
}
