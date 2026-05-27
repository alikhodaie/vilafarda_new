<?php

namespace App\Policies;

use App\Models\LandingPage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LandingPagePolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('landing-pages:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('landing-pages:create');
    }

    public function update(User $user, LandingPage $landingPage): bool
    {
        return $user->hasPermissionTo('landing-pages:update');
    }

    public function delete(User $user, LandingPage $landingPage): bool
    {
        return $user->hasPermissionTo('landing-pages:destroy');
    }
}
