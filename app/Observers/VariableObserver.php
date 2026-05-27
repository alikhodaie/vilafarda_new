<?php

namespace App\Observers;

use App\Models\Variable;

class VariableObserver
{
    /**
     * Handle the Variable "created" event.
     *
     * @param Variable $variable
     * @return void
     */
    public function created(Variable $variable)
    {
        cache()->delete(Variable::CACHE_KEY);
    }

    /**
     * Handle the Variable "updated" event.
     *
     * @param Variable $variable
     * @return void
     */
    public function updated(Variable $variable)
    {
        cache()->delete(Variable::CACHE_KEY);
    }

    /**
     * Handle the Variable "deleted" event.
     *
     * @param Variable $variable
     * @return void
     */
    public function deleted(Variable $variable)
    {
        cache()->delete(Variable::CACHE_KEY);
    }

    /**
     * Handle the Variable "restored" event.
     *
     * @param Variable $variable
     * @return void
     */
    public function restored(Variable $variable)
    {
        cache()->delete(Variable::CACHE_KEY);
    }

    /**
     * Handle the Variable "force deleted" event.
     *
     * @param Variable $variable
     * @return void
     */
    public function forceDeleted(Variable $variable)
    {
        cache()->delete(Variable::CACHE_KEY);
    }
}
