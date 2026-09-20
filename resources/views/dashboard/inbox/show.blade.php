@extends('layouts.dashboard.dashboard', ['title' => $message->title, 'active' => 'inbox', 'breadcrumbs' => [
    ['url' => $inboxIndexUrl, 'title' => __('title.inbox')],
    ['url' => null, 'title' => $message->title],
]])

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">{{ $message->title }}</h3>
        <a href="{{ $inboxIndexUrl }}" class="btn btn-outline-secondary btn-sm">
            @lang('title.back')
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="badge {{ $message->type === \App\Support\UserInboxMessage::TYPE_SMS ? 'badge-info' : 'badge-primary' }}">
                {{ $message->kindLabel }}
            </span>
            <small class="text-muted">
                <i class="fas fa-clock ml-1"></i>
                {{ $message->createdAt->format('Y/m/d H:i') }}
            </small>
        </div>
        <div class="card-body">
            @if($message->bodyIsHtml)
                {!! $message->body !!}
            @else
                <p class="mb-0" style="white-space: pre-wrap;">{!! inbox_linkify($message->body) !!}</p>
            @endif
        </div>
    </div>
@endsection
