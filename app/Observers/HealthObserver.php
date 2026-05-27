<?php

namespace App\Observers;

use App\Models\Health;

class HealthObserver
{
    /**
     * Handle the Health "created" event.
     *
     * @param  \App\Models\Health  $health
     * @return void
     */
    public function created(Health $health)
    {
        cache()->delete(Health::CACHE_KEY);
    }

    /**
     * Handle the Health "updated" event.
     *
     * @param  \App\Models\Health  $health
     * @return void
     */
    public function updated(Health $health)
    {
        cache()->delete(Health::CACHE_KEY);
    }

    /**
     * Handle the Health "deleted" event.
     *
     * @param  \App\Models\Health  $health
     * @return void
     */
    public function deleted(Health $health)
    {
        cache()->delete(Health::CACHE_KEY);
    }

    /**
     * Handle the Health "restored" event.
     *
     * @param  \App\Models\Health  $health
     * @return void
     */
    public function restored(Health $health)
    {
        cache()->delete(Health::CACHE_KEY);
    }

    /**
     * Handle the Health "force deleted" event.
     *
     * @param  \App\Models\Health  $health
     * @return void
     */
    public function forceDeleted(Health $health)
    {
        cache()->delete(Health::CACHE_KEY);
    }
}
