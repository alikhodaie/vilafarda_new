<?php

namespace App\Http\View\Composers;

use App\Services\UserInboxService;
use Illuminate\View\View;

class InboxUnreadComposer
{
    public function compose(View $view): void
    {
        $user = auth()->user();

        $view->with(
            'inboxUnreadCount',
            $user ? app(UserInboxService::class)->unreadCount($user) : 0
        );
    }
}
