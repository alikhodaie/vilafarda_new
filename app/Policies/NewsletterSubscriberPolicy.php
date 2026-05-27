<?php

namespace App\Policies;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsletterSubscriberPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('newsletter_subscribers:index');
    }

    public function delete(User $user, NewsletterSubscriber $subscriber): bool
    {
        return $user->hasPermissionTo('newsletter_subscribers:destroy');
    }
}
