<?php

namespace App\Observers;

use App\Models\Navbar;

class NavbarObserver
{
    /**
     * Handle the Navbar "created" event.
     *
     * @param  \App\Models\Navbar  $navbar
     * @return void
     */
    public function created(Navbar $navbar)
    {
        cache()->delete(Navbar::CACHE_KEY);
    }

    /**
     * Handle the Navbar "updated" event.
     *
     * @param  \App\Models\Navbar  $navbar
     * @return void
     */
    public function updated(Navbar $navbar)
    {
        cache()->delete(Navbar::CACHE_KEY);
    }

    /**
     * Handle the Navbar "deleted" event.
     *
     * @param  \App\Models\Navbar  $navbar
     * @return void
     */
    public function deleted(Navbar $navbar)
    {
        cache()->delete(Navbar::CACHE_KEY);
    }

    /**
     * Handle the Navbar "restored" event.
     *
     * @param  \App\Models\Navbar  $navbar
     * @return void
     */
    public function restored(Navbar $navbar)
    {
        cache()->delete(Navbar::CACHE_KEY);
    }

    /**
     * Handle the Navbar "force deleted" event.
     *
     * @param  \App\Models\Navbar  $navbar
     * @return void
     */
    public function forceDeleted(Navbar $navbar)
    {
        cache()->delete(Navbar::CACHE_KEY);
    }
}
