@extends('layouts.dashboard.dashboard', ['title' => __('title.show_ticket'), 'active' => 'tickets', 'breadcrumbs' => [
    ['url' => route('dashboard.tickets.index'), 'title' => __('title.tickets')],
    ['url' => null, 'title' => __('title.show_ticket')]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.show_ticket')
        </h3>
    </div>

    <div class="d-flex justify-content-between my-4">
        <span>@lang('title.ticket') #{{ $ticket->id }} - {{ $ticket->title }}</span>
        <span>@lang('title.status'): {{ $ticket->status() }}</span>
        <span>@lang('title.date_create'): {{ $ticket->persianCreatedAt() }}</span>
    </div>

    @foreach($ticket->messages as $message)
        <div class="card rounded mb-3">
            <div class="card-header text-dark">
                <div class="d-flex justify-content-between">
                    <span>{{ $message->user->full_name }}</span>
                    <span>{{ $message->persianCreatedAt() }}</span>
                </div>
            </div>
            <div class="card-body">
                {{ $message->content }}
            </div>
            @if($message->attachments->isNotEmpty())
                <div class="card-footer">
                    <div class="d-inline">
                        @foreach($message->attachments as $index => $attachment)
                            <a href="{{ $attachment->file() }}">@lang('title.attachment') {{ $index + 1 }}</a>@if($message->attachments->count() !== $index + 1) , @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <form class="mt-5" action="{{ route('dashboard.tickets.reply', $ticket->id) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="message">@lang('title.message')</label>
            <textarea type="text" class="form-control" id="message" name="message">{!! old('message') !!}</textarea>
        </div>

        <div class="form-group">
            <label for="attachments">@lang('title.attachment')</label>
            <file-input
                id="attachments"
                name="attachments[]"
                text_select="@lang('title.select_file')"
                text_selected="@lang('title.selected_files')"
            ></file-input>
            <em class="text-muted">@lang('title.max_size'): {{ round(\App\Models\TicketAttachment::MAX_SIZE / 1000) }}MB @lang('title.type'): jpg,png,zip,rar</em>
        </div>

        <div class="text-left">
            <button class="btn btn-info">@lang('title.send_reply')</button>
        </div>
    </form>
@endsection
