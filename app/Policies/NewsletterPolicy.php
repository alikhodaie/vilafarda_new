<?php

namespace App\Policies;

use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsletterPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('newsletters:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('newsletters:create');
    }

    public function delete(User $user, Newsletter $newsletter): bool
    {
        return $user->hasPermissionTo('newsletters:destroy');
    }
}
