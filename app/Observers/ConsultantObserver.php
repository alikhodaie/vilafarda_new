<?php

namespace App\Observers;

use App\Models\Consultant;

class ConsultantObserver
{
    /**
     * Handle the Consultant "created" event.
     *
     * @param Consultant $consultant
     * @return void
     */
    public function created(Consultant $consultant)
    {
        cache()->delete(Consultant::CACHE_KEY);
    }

    /**
     * Handle the Consultant "updated" event.
     *
     * @param Consultant $consultant
     * @return void
     */
    public function updated(Consultant $consultant)
    {
        cache()->delete(Consultant::CACHE_KEY);
    }

    /**
     * Handle the Consultant "deleted" event.
     *
     * @param Consultant $consultant
     * @return void
     */
    public function deleted(Consultant $consultant)
    {
        cache()->delete(Consultant::CACHE_KEY);
    }

    /**
     * Handle the Consultant "restored" event.
     *
     * @param Consultant $consultant
     * @return void
     */
    public function restored(Consultant $consultant)
    {
        cache()->delete(Consultant::CACHE_KEY);
    }

    /**
     * Handle the Consultant "force deleted" event.
     *
     * @param Consultant $consultant
     * @return void
     */
    public function forceDeleted(Consultant $consultant)
    {
        cache()->delete(Consultant::CACHE_KEY);
    }
}
