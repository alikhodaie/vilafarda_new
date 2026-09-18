<?php

namespace App\Observers;

use App\Models\Home;
use App\Models\Province;
use App\Services\SitemapService;

class HomeObserver
{
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
}
