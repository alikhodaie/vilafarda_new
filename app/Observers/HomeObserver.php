<?php

namespace App\Observers;

use App\Classes\SMS;
use App\Models\Home;
use App\Models\Province;
use App\Services\OrderAdminSmsService;
use App\Services\SitemapService;
use Illuminate\Support\Str;

class HomeObserver
{
    public function __construct(
        protected OrderAdminSmsService $orderAdminSmsService
    ) {
    }

    public function created(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);

        if (! filled($home->slug)) {
            $home->updateQuietly(['slug' => $home->suggestSlug()]);
        }
    }

    public function updated(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);

        if ($home->wasChanged(['status', 'is_draft', 'is_host_active', 'slug'])) {
            SitemapService::forgetCache();
        }

        if ($home->shouldNotifyHostOnSubmit()) {
            $this->sendHostSubmittedSms($home);
            $this->orderAdminSmsService->sendAdminHomeSubmittedSms($home);
        }

        if ($home->shouldNotifyHostOnReview()) {
            $this->sendHostReviewedSms($home);
        }
    }

    public function deleted(Home $home)
    {
        foreach ($home->images as $image){
            $image->deleteImage($home);
        }

        cache()->delete(Province::CACHE_KEY);
    }

    public function restored(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);
    }

    public function forceDeleted(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);
    }

    private function sendHostSubmittedSms(Home $home): void
    {
        $pattern = trim((string) config('sms.patterns.home_submitted_host'));
        if ($pattern === '') {
            return;
        }

        $host = $home->user;
        if (! $host || ! filled($host->mobile)) {
            return;
        }

        $limit = (int) config('sms.parameter_max_length', 25);
        if ($limit <= 0) {
            $limit = 25;
        }

        SMS::sendPattern($host->mobile, $pattern, [
            [
                'name' => config('sms.parameter_names.home_submitted_host.host_name', 'HOST-NAME'),
                'value' => Str::limit(trim($host->full_name), $limit, ''),
            ],
        ], [
            'user_id' => $host->id,
            'related' => $home,
            'source' => 'HomeObserver::updated',
        ]);
    }

    private function sendHostReviewedSms(Home $home): void
    {
        $pattern = trim((string) config('sms.patterns.home_reviewed_host'));
        if ($pattern === '') {
            return;
        }

        $host = $home->user;
        $result = $home->reviewResultLabel();
        if (! $host || ! filled($host->mobile) || ! $result) {
            return;
        }

        $limit = (int) config('sms.parameter_max_length', 25);
        if ($limit <= 0) {
            $limit = 25;
        }

        SMS::sendPattern($host->mobile, $pattern, [
            [
                'name' => config('sms.parameter_names.home_reviewed_host.host_name', 'HOST-NAME'),
                'value' => Str::limit(trim($host->full_name), $limit, ''),
            ],
            [
                'name' => config('sms.parameter_names.home_reviewed_host.review_result', 'REVIEW-RESULT'),
                'value' => $result,
            ],
        ], [
            'user_id' => $host->id,
            'related' => $home,
            'source' => 'HomeObserver::updated',
        ]);
    }
}
