@extends('layouts.dashboard.dashboard', ['title' => __('title.inbox'), 'active' => 'inbox', 'breadcrumbs' => [
    ['url' => null, 'title' => __('title.inbox')]
]])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">
            @lang('title.inbox')
            @if(($inboxUnreadCount ?? 0) > 0)
                <span class="badge badge-success">{{ $inboxUnreadCount }}</span>
            @endif
        </h3>
        @if(($inboxUnreadCount ?? 0) > 0)
            <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#markAllReadModal">
                @lang('title.inbox_mark_all_read')
            </button>
        @endif
    </div>

    <div class="mb-3 d-flex flex-wrap" style="gap: 8px;">
        @foreach([
            'all' => __('title.inbox_filter_all'),
            'unread' => __('title.inbox_filter_unread'),
            'sms' => __('title.inbox_filter_sms'),
            'newsletter' => __('title.inbox_filter_news'),
        ] as $key => $label)
            <a href="{{ route('dashboard.inbox.index', $key === 'all' ? [] : ['filter' => $key]) }}"
               class="btn btn-sm {{ ($filter ?? 'all') === $key ? 'btn-warning' : 'btn-light' }}">
                {{ $label }}
                @if($key === 'unread' && ($inboxUnreadCount ?? 0) > 0)
                    ({{ $inboxUnreadCount }})
                @endif
            </a>
        @endforeach
    </div>

    @if($messages->isNotEmpty())
        <div class="inbox-wa-desktop">
            @foreach($messages as $message)
                <a href="{{ $message->showRoute($filter ?? 'all') }}" class="inbox-wa-desktop__row text-dark {{ $message->isUnread ? 'is-unread' : '' }}">
                    <span class="inbox-wa-desktop__avatar {{ $message->type === \App\Support\UserInboxMessage::TYPE_NEWSLETTER ? 'is-news' : 'is-sms' }}">
                        <i class="{{ $message->type === \App\Support\UserInboxMessage::TYPE_NEWSLETTER ? 'fa fa-bullhorn' : 'fa fa-cog' }}"></i>
                    </span>
                    <span class="inbox-wa-desktop__main">
                        <span class="d-flex justify-content-between">
                            <strong>{{ $message->title }}</strong>
                            <small class="text-muted">{{ $message->timeLabel() }}</small>
                        </span>
                        <span class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $message->preview }}</small>
                            @if($message->isUnread)
                                <span class="badge badge-success">1</span>
                            @endif
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
        <div class="text-center pt-3">
            {{ $messages->appends(request()->query())->links() }}
        </div>
    @else
        <div class="alert alert-warning">
            {{ ($filter ?? 'all') === 'all' ? __('title.inbox_empty') : __('title.inbox_empty_filter') }}
        </div>
    @endif

    @if(($inboxUnreadCount ?? 0) > 0)
        <div class="modal fade" id="markAllReadModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('title.inbox_mark_all_confirm_title')</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        @lang('title.inbox_mark_all_confirm')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('title.cancel')</button>
                        <form action="{{ route('dashboard.inbox.read-all') }}" method="POST">
                            @csrf
                            <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                            <button type="submit" class="btn btn-warning">@lang('title.inbox_mark_all_read')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .inbox-wa-desktop { background: #fff; border-radius: 8px; overflow: hidden; }
        .inbox-wa-desktop__row {
            display: flex; align-items: center; gap: 12px; padding: 14px 16px;
            border-bottom: 1px solid #f1f1f1; text-decoration: none;
        }
        .inbox-wa-desktop__row:hover { background: #fafafa; }
        .inbox-wa-desktop__avatar {
            width: 46px; height: 46px; border-radius: 50%; color: #fff;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .inbox-wa-desktop__avatar.is-sms { background: #25d366; }
        .inbox-wa-desktop__avatar.is-news { background: #d39d1a; }
        .inbox-wa-desktop__main { flex: 1; min-width: 0; }
        .inbox-wa-desktop__row.is-unread strong { font-weight: 800; }
    </style>
@endsection
