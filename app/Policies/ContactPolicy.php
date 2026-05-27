<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('contacts:index');
    }

    public function delete(User $user, Contact $contact): bool
    {
        return $user->hasPermissionTo('contacts:destroy');
    }
}
