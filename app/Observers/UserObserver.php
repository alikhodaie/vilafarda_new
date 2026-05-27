<?php

namespace App\Observers;

use App\Models\Discount;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        if ($user->isAdmin()) {
            cache()->delete(User::ADMIN_ORDER_SMS_CACHE_KEY);

            return;
        }

        Discount::attachToNewUser($user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isAdmin()){
            cache()->delete(User::ADMIN_ORDER_SMS_CACHE_KEY);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        if ($user->isAdmin()){
            cache()->delete(User::ADMIN_ORDER_SMS_CACHE_KEY);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        if ($user->isAdmin()){
            cache()->delete(User::ADMIN_ORDER_SMS_CACHE_KEY);
        }
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        if ($user->isAdmin()){
            cache()->delete(User::ADMIN_ORDER_SMS_CACHE_KEY);
        }
    }
}
