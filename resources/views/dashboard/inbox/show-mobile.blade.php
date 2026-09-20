@extends('layouts.main.main_mobile', ['title' => $message->title])

@section('styles')
    <style>
        .inbox-show-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .inbox-wa-back {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            color: #128C7E;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
            line-height: 1;
        }
        .inbox-wa-back:hover,
        .inbox-wa-back:focus {
            color: #075E54;
            text-decoration: none;
        }
        .inbox-wa-back-icon {
            width: 22px;
            height: 22px;
            flex-shrink: 0;
        }
        .inbox-action-link {
            color: #128C7E;
            word-break: break-all;
        }
    </style>
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <div class="inbox-show-header" dir="ltr">
                <a href="{{ $inboxIndexUrl }}" class="inbox-wa-back" aria-label="@lang('title.inbox_back')">
                    <svg class="inbox-wa-back-icon" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M15.2 4.8L7.6 12l7.6 7.2" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>@lang('title.inbox_back')</span>
                </a>
                <span class="badge {{ $message->type === \App\Support\UserInboxMessage::TYPE_SMS ? 'bg-info' : 'bg-primary' }} inbox-show-kind" style="font-size: 11px;">
                    {{ $message->kindLabel }}
                </span>
            </div>
            <h1 class="fw-bold mb-1 mt-3" style="font-size: 18px; color: #333;">{{ $message->title }}</h1>
            <small class="text-muted" style="font-size: 12px;">{{ $message->createdAt->format('Y/m/d H:i') }}</small>
        </div>
    </div>

    <div class="container px-3 pb-4" style="padding-bottom: 110px;">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            @if($message->bodyIsHtml)
                <div class="inbox-html-body" style="font-size: 14px; color: #333; line-height: 1.8;">
                    {!! $message->body !!}
                </div>
            @else
                <p class="mb-0 inbox-plain-body" style="font-size: 14px; color: #333; line-height: 1.8; white-space: pre-wrap;">{!! inbox_linkify($message->body) !!}</p>
            @endif
        </div>
    </div>
@endsection
