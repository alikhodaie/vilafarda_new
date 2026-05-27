<?php

namespace App\Observers;

use App\Models\FAQ;

class FAQObserver
{
    /**
     * Handle the FAQ "created" event.
     *
     * @param FAQ $fAQ
     * @return void
     */
    public function created(FAQ $fAQ)
    {
        cache()->delete(FAQ::CACHE_KEY);
    }

    /**
     * Handle the FAQ "updated" event.
     *
     * @param FAQ $fAQ
     * @return void
     */
    public function updated(FAQ $fAQ)
    {
        cache()->delete(FAQ::CACHE_KEY);
    }

    /**
     * Handle the FAQ "deleted" event.
     *
     * @param FAQ $fAQ
     * @return void
     */
    public function deleted(FAQ $fAQ)
    {
        cache()->delete(FAQ::CACHE_KEY);
    }

    /**
     * Handle the FAQ "restored" event.
     *
     * @param FAQ $fAQ
     * @return void
     */
    public function restored(FAQ $fAQ)
    {
        cache()->delete(FAQ::CACHE_KEY);
    }

    /**
     * Handle the FAQ "force deleted" event.
     *
     * @param FAQ $fAQ
     * @return void
     */
    public function forceDeleted(FAQ $fAQ)
    {
        cache()->delete(FAQ::CACHE_KEY);
    }
}
