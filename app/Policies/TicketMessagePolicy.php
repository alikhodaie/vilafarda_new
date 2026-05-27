<?php

namespace App\Policies;

use App\Models\TicketMessage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketMessagePolicy
{
    use HandlesAuthorization;

    public function update(User $user, TicketMessage $message): bool
    {
        return $user->hasPermissionTo('ticket-messages:update') && $message->user_id === Auth()->id() && $message->sent_from === TicketMessage::ADMIN;
    }

    public function delete(User $user, TicketMessage $message): bool
    {
        return $user->hasPermissionTo('ticket-messages:destroy') && $message->user_id === Auth()->id() && $message->sent_from === TicketMessage::ADMIN;
    }
}
