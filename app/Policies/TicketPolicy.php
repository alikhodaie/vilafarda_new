<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function index(User $user): bool
    {
        return $user->hasPermissionTo('tickets:index');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('tickets:create');
    }

    public function reply(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('tickets:reply');
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('tickets:update');
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasPermissionTo('tickets:destroy');
    }
}
