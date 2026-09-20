<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\UserInboxService;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index(Request $request, UserInboxService $inbox)
    {
        $user = $request->user();
        $filter = $this->resolvedFilter($request);
        $messages = $inbox->paginate($user, 15, $filter);
        $inboxUnreadCount = $inbox->unreadCount($user);

        if ($request->is_mobile ?? false) {
            return view('dashboard.inbox.index-mobile', compact('messages', 'inboxUnreadCount', 'filter'));
        }

        return view('dashboard.inbox.index', compact('messages', 'inboxUnreadCount', 'filter'));
    }

    public function markAllRead(Request $request, UserInboxService $inbox)
    {
        $inbox->markAllRead($request->user());

        return redirect()
            ->route('dashboard.inbox.index', array_filter(['filter' => $this->resolvedFilter($request)]))
            ->with('success', __('title.inbox_marked_all_read'));
    }

    public function show(Request $request, UserInboxService $inbox, string $type, int $id)
    {
        $user = $request->user();
        $message = $inbox->find($user, $type, $id);
        $inbox->markRead($user, $type, $id);
        $message->isUnread = false;

        $filter = $this->resolvedFilter($request);
        $inboxIndexUrl = $this->indexUrl($filter);

        if ($request->is_mobile ?? false) {
            return view('dashboard.inbox.show-mobile', compact('message', 'inboxIndexUrl', 'filter'));
        }

        return view('dashboard.inbox.show', compact('message', 'inboxIndexUrl', 'filter'));
    }

    private function resolvedFilter(Request $request): string
    {
        $filter = (string) $request->get('filter', 'all');

        return in_array($filter, ['all', 'unread', 'sms', 'newsletter'], true) ? $filter : 'all';
    }

    private function indexUrl(string $filter): string
    {
        return route('dashboard.inbox.index', $filter === 'all' ? [] : ['filter' => $filter]);
    }
}
