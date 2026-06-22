<?php

namespace App\Observers;

use App\Classes\SMS;
use App\Models\Comment;
use App\Models\Home;
use App\Models\Order;
use App\Services\HomeProfileStatsService;

class CommentObserver
{
    /**
     * Handle the Comment "created" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        $comment->updateCounter();
        $this->refreshHomePerformanceStats($comment);
    }

    /**
     * Handle the Comment "updated" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        if ($comment->isDirty('status')){
            $comment->updateCounter();
            $this->refreshHomePerformanceStats($comment);

            if ($comment->status === Comment::CONFIRMED && $comment->commentable_type === Home::class){
//                $order = $comment->commentable->orders()
//                    ->whereIn('status', [Order::DONE, Order::IN_RENT])
//                    ->where('user_id', $comment->user_id)
//                    ->latest()->first();
//
//                SMS::sendPattern($comment->commentable->user->mobile, '431957', [[
//                    'name' => 'DATE',
//                    'value' => $order->persianDate('start_at', 'Y/m/d'),
//                ]]);
            }
        }
    }

    /**
     * Handle the Comment "deleted" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        $comment->updateCounter();
        $this->refreshHomePerformanceStats($comment);
    }

    /**
     * Handle the Comment "restored" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        $comment->updateCounter();
        $this->refreshHomePerformanceStats($comment);
    }

    /**
     * Handle the Comment "force deleted" event.
     *
     * @param Comment $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        $comment->updateCounter();
        $this->refreshHomePerformanceStats($comment);
    }

    private function refreshHomePerformanceStats(Comment $comment): void
    {
        if ($comment->commentable_type !== Home::class) {
            return;
        }

        $home = $comment->relationLoaded('commentable')
            ? $comment->commentable
            : Home::query()->find($comment->commentable_id);

        if ($home) {
            app(HomeProfileStatsService::class)->recalculateGuestReviewTier($home);
        }
    }
}
