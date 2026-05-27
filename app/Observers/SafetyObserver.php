<?php

namespace App\Observers;

use App\Models\Safety;

class SafetyObserver
{
    /**
     * Handle the Safety "created" event.
     *
     * @param  \App\Models\Safety  $safety
     * @return void
     */
    public function created(Safety $safety)
    {
        cache()->delete(Safety::CACHE_KEY);
    }

    /**
     * Handle the Safety "updated" event.
     *
     * @param  \App\Models\Safety  $safety
     * @return void
     */
    public function updated(Safety $safety)
    {
        cache()->delete(Safety::CACHE_KEY);
    }

    /**
     * Handle the Safety "deleted" event.
     *
     * @param  \App\Models\Safety  $safety
     * @return void
     */
    public function deleted(Safety $safety)
    {
        cache()->delete(Safety::CACHE_KEY);
    }

    /**
     * Handle the Safety "restored" event.
     *
     * @param  \App\Models\Safety  $safety
     * @return void
     */
    public function restored(Safety $safety)
    {
        cache()->delete(Safety::CACHE_KEY);
    }

    /**
     * Handle the Safety "force deleted" event.
     *
     * @param  \App\Models\Safety  $safety
     * @return void
     */
    public function forceDeleted(Safety $safety)
    {
        cache()->delete(Safety::CACHE_KEY);
    }
}
