<?php

namespace App\Policies;

use App\Models\FAQ;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FAQPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('faq:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('faq:create');
    }

    public function update(User $user, FAQ $fAQ): bool
    {
        return $user->hasPermissionTo('faq:update');
    }

    public function delete(User $user, FAQ $fAQ): bool
    {
        return $user->hasPermissionTo('faq:destroy');
    }
}
