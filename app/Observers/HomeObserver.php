<?php

namespace App\Observers;

use App\Models\Home;
use App\Models\Province;

class HomeObserver
{
    /**
     * Handle the Home "created" event.
     *
     * @param Home $home
     * @return void
     */
    public function created(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);

        if (! filled($home->slug)) {
            $home->updateQuietly(['slug' => $home->suggestSlug()]);
        }

        // کاور به صورت WebP در Home::updateCover ذخیره می‌شود؛ دیگر کوچک‌کردن تهاجمی در صف لازم نیست.
    }

    /**
     * Handle the Home "updated" event.
     *
     * @param Home $home
     * @return void
     */
    public function updated(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);
    }

    /**
     * Handle the Home "deleted" event.
     *
     * @param Home $home
     * @return void
     */
    public function deleted(Home $home)
    {
        foreach ($home->images as $image){
            $image->deleteImage($home);
        }

        cache()->delete(Province::CACHE_KEY);
    }

    /**
     * Handle the Home "restored" event.
     *
     * @param Home $home
     * @return void
     */
    public function restored(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);
    }

    /**
     * Handle the Home "force deleted" event.
     *
     * @param Home $home
     * @return void
     */
    public function forceDeleted(Home $home)
    {
        cache()->delete(Province::CACHE_KEY);
    }
}
