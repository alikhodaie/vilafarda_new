@extends('layouts.main.main_mobile', ['title' => __('title.inbox')])

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="inbox-wa">
        <div class="inbox-wa__head">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="inbox-wa__title mb-0">@lang('title.inbox')</h1>
                @if(($inboxUnreadCount ?? 0) > 0)
                    <button type="button" class="inbox-wa__mark-all" data-bs-toggle="modal" data-bs-target="#markAllReadModal">
                        @lang('title.inbox_mark_all_read')
                    </button>
                @endif
            </div>

            <div class="inbox-wa__filters">
                @foreach([
                    'all' => __('title.inbox_filter_all'),
                    'unread' => __('title.inbox_filter_unread'),
                    'sms' => __('title.inbox_filter_sms'),
                    'newsletter' => __('title.inbox_filter_news'),
                ] as $key => $label)
                    <a href="{{ route('dashboard.inbox.index', $key === 'all' ? [] : ['filter' => $key]) }}"
                       class="inbox-wa__chip {{ ($filter ?? 'all') === $key ? 'is-active' : '' }}">
                        {{ $label }}
                        @if($key === 'unread' && ($inboxUnreadCount ?? 0) > 0)
                            <span class="inbox-wa__chip-count">{{ $inboxUnreadCount }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>

        <div class="inbox-wa__list">
            @forelse($messages as $message)
                <a href="{{ $message->showRoute($filter ?? 'all') }}" class="inbox-wa__row {{ $message->isUnread ? 'is-unread' : '' }}">
                    <span class="inbox-wa__avatar {{ $message->type === \App\Support\UserInboxMessage::TYPE_NEWSLETTER ? 'is-news' : 'is-sms' }}">
                        <i class="bi {{ $message->type === \App\Support\UserInboxMessage::TYPE_NEWSLETTER ? 'bi-megaphone-fill' : 'bi-gear-fill' }}"></i>
                    </span>
                    <span class="inbox-wa__body">
                        <span class="inbox-wa__top">
                            <span class="inbox-wa__name">{{ $message->title }}</span>
                            <span class="inbox-wa__time">{{ $message->timeLabel() }}</span>
                        </span>
                        <span class="inbox-wa__bottom">
                            <span class="inbox-wa__preview">{{ $message->preview }}</span>
                            @if($message->isUnread)
                                <span class="inbox-wa__unread">۱</span>
                            @endif
                        </span>
                    </span>
                </a>
            @empty
                <div class="inbox-wa__empty">
                    <i class="bi bi-chat-square-text"></i>
                    <p class="mb-0">{{ ($filter ?? 'all') === 'all' ? __('title.inbox_empty') : __('title.inbox_empty_filter') }}</p>
                </div>
            @endforelse
        </div>

        @if($messages->hasPages())
            <div class="d-flex justify-content-center py-3" style="padding-bottom: 110px;">
                {{ $messages->appends(request()->query())->links() }}
            </div>
        @else
            <div style="height: 90px;"></div>
        @endif
    </div>

    @if(($inboxUnreadCount ?? 0) > 0)
        <div class="modal fade" id="markAllReadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 16px;">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" style="font-size: 16px;">@lang('title.inbox_mark_all_confirm_title')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0" style="font-size: 14px; color: #555;">
                        @lang('title.inbox_mark_all_confirm')
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px;">@lang('title.cancel')</button>
                        <form action="{{ route('dashboard.inbox.read-all') }}" method="POST">
                            @csrf
                            <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
                            <button type="submit" class="btn" style="background: #D39D1A; color: #fff; border-radius: 10px;">
                                @lang('title.inbox_mark_all_read')
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('styles')
<style>
    .inbox-wa { background: #fff; min-height: 70vh; }
    .inbox-wa__head { padding: 14px 16px 8px; background: #fff; border-bottom: 1px solid #f0f0f0; }
    .inbox-wa__title { font-size: 20px; font-weight: 800; color: #111; }
    .inbox-wa__mark-all {
        border: 0; background: transparent; color: #D39D1A; font-size: 13px; font-weight: 700;
    }
    .inbox-wa__filters { display: flex; gap: 8px; overflow-x: auto; padding: 12px 0 6px; -webkit-overflow-scrolling: touch; }
    .inbox-wa__chip {
        flex: 0 0 auto; text-decoration: none; color: #444; background: #f2f2f2;
        border-radius: 999px; padding: 6px 12px; font-size: 12px; font-weight: 600; white-space: nowrap;
    }
    .inbox-wa__chip.is-active { background: #D39D1A; color: #fff; }
    .inbox-wa__chip-count {
        display: inline-block; min-width: 16px; margin-right: 4px; padding: 0 5px;
        border-radius: 999px; background: #fff; color: #D39D1A; font-size: 10px; line-height: 16px; text-align: center;
    }
    .inbox-wa__chip.is-active .inbox-wa__chip-count { background: #fff; color: #D39D1A; }
    .inbox-wa__row {
        display: flex; align-items: center; gap: 12px; padding: 12px 16px;
        text-decoration: none; color: inherit; border-bottom: 1px solid #f1f1f1;
    }
    .inbox-wa__avatar {
        width: 52px; height: 52px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 20px; flex-shrink: 0;
    }
    .inbox-wa__avatar.is-sms { background: #25D366; }
    .inbox-wa__avatar.is-news { background: #D39D1A; }
    .inbox-wa__body { flex: 1; min-width: 0; }
    .inbox-wa__top, .inbox-wa__bottom { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .inbox-wa__name { font-size: 15px; font-weight: 500; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .inbox-wa__row.is-unread .inbox-wa__name { font-weight: 800; }
    .inbox-wa__time { font-size: 12px; color: #888; flex-shrink: 0; }
    .inbox-wa__row.is-unread .inbox-wa__time { color: #25D366; font-weight: 700; }
    .inbox-wa__preview { font-size: 13px; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 3px; }
    .inbox-wa__row.is-unread .inbox-wa__preview { color: #444; font-weight: 600; }
    .inbox-wa__unread {
        min-width: 20px; height: 20px; padding: 0 6px; border-radius: 999px; background: #25D366;
        color: #fff; font-size: 11px; font-weight: 700; line-height: 20px; text-align: center; flex-shrink: 0;
    }
    .inbox-wa__empty { text-align: center; padding: 64px 20px; color: #888; }
    .inbox-wa__empty i { font-size: 40px; display: block; margin-bottom: 12px; color: #ccc; }
</style>
@endsection
