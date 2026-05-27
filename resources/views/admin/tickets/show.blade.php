@extends('layouts.admin.admin', ['title' => __('title.show_ticket'), 'active' => 'tickets'])

@section('content')
    <x-admin.card title="{{ __('title.show_ticket') }}">
        <div class="row mt-2">
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.user'):</span>
                <span class="text-center">{{ $ticket->user->fullname }}</span>
            </div>
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.id'):</span>
                <span class="text-center">{{ $ticket->id }}</span>
            </div>
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.title'):</span>
                <span class="text-center">{{ $ticket->title }}</span>
            </div>
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.created at'):</span>
                <span class="text-center">{{ $ticket->persianCreatedAt() }}</span>
            </div>
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.updated_at'):</span>
                <span class="text-center">{{ $ticket->persianUpdatedAt() }}</span>
            </div>
            <div class="col-12 col-sm-12 col-xl-2 py-2 d-flex flex-column align-items-center justify-content-center">
                <span class="table_head_tag mb-2">@lang('title.status'):</span>
                <span class="text-center">{{ $ticket->status('fa_text_admin') }}</span>
            </div>
            @can('update', $ticket)
                <div class="col-12">
                    <form method="post" action="{{ route('admin.tickets.update', $ticket->id) }}">
                        @csrf
                        @method('put')

                        <label for="status">@lang('title.status')</label>
                        <select onchange="this.form.submit()" name="status" id="status" class="form-control">
                            @foreach(\App\Models\Ticket::STATUS as $status)
                                <option value="{{ $status['value'] }}" @if($status['value'] === $ticket->status) selected @endif>{{ $status['fa_text_admin'] }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
            @endcan
        </div>

        <div class="row mx-0">
            @foreach($ticket->messages as $message)
                <div class="col-12 col-sm-12 mt-2 pt-2">

                    <div class="d-flex flex-column position-relative mb-4">

                        <div class="font-weight-bold mt-1" style="font-size:18px;">
                            {{ $message->user->fullname }} ({{ $message->sentFrom() }})
                            @can('update', $message)
                                <a class="text-primary" href="{{ route('admin.tickets.messages.edit', [$message->ticket->id, $message->id]) }}">
                                    <span class="fas fa-edit fa-2x"></span>
                                </a>
                            @endcan
                            @can('delete', $message)
                                <delete-modal
                                    route="{{ route('admin.tickets.messages.destroy', [$message->ticket->id, $message->id]) }}"
                                    csrf="{{ csrf_token() }}"
                                    title="@lang('title.delete message')"
                                    text="@lang('text.delete message')"
                                    button_hover_text="@lang('title.delete')"
                                    button_cancel_text="@lang('title.cancel')"
                                    button_submit_text="@lang('title.delete')"
                                    btn_class="fas fa-trash fa-2x text-danger"
                                ></delete-modal>
                            @endcan
                        </div>

                        <div class="font-weigh-bold mt-1" style="color: #adadad;font-size: 15px;">
                            {{ $message->persianCreatedAt() }}
                        </div>

                        {{-- message --}}
                        <div class="mt-2" style="line-height: 30px;">
                            {!! nl2br($message->content) !!}
                        </div>
                        {{-- attached file --}}
                        <div class="mt-3">
                            @if($message->attachments->isNotEmpty())
                                <div class="">
                                    <span class="font-weight-bold">@lang('title.attachment'):</span>
                                    <div class="mt-2" style="direction: ltr">
                                        @foreach($message->attachments as $attachment)
                                            <a class="m-1" href="{{ $attachment->file() }}">{{ $attachment->file }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        @can('reply', $ticket)
            <form action="{{ route('admin.tickets.messages.store', $ticket->id) }}" enctype="multipart/form-data" method="post">
                @csrf

                <div class="form-group">
                    <label for="message">@lang('title.message')</label>
                    <textarea name="message" id="message" cols="30" rows="10" class="form-control">{{ old('message') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="attachments">@lang('title.attachment')</label>
                    <file-input
                        id="attachments"
                        name="attachments[]"
                        text_select="@lang('title.select_file')"
                        text_selected="@lang('title.selected_files')"
                    ></file-input>
                </div>


                <div class="col-12 mt-5 d-flex justify-content-center">
                    <button class="btn btn-falcon-success">@lang('title.send_reply')</button>
                    <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
                </div>
            </form>
        @endcan
    </x-admin.card>
@endsection
