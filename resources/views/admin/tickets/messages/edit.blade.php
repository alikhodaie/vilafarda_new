@extends('layouts.admin.admin', ['title' => __('title.edit_message'), 'active' => 'tickets'])

@section('content')
    <x-admin.card title="{{ __('title.edit_message') }}">
        <form action="{{ route('admin.tickets.messages.update', [$message->ticket->id, $message->id]) }}" method="POST" class="p-3 row" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-12 mb-5">
                <label for="name" class="form_label">@lang('title.name'):</label>
                {{ $message->user->fullname }} ({{ $message->sentFrom() }})
            </div>

            <div class="col-12 mb-5">
                <label for="content" class="form_label">@lang('title.message')</label>
                <textarea id="content" name="content" class="form-control round" rows="10">{!! $message->content !!}</textarea>
            </div>

            @if($message->attachments->isNotEmpty())
                <div class="col-12 mb-5">
                    <input type="hidden" name="has-old-attachment" value="true">
                    <label class="form_label">@lang('title.old_attachments')</label>
                    <div style="direction: ltr">
                        @foreach($message->attachments as $attachment)
                            <span class="m-1">
                                <i class="fa fa-times-circle" style="color: red; cursor: pointer; font-size: 20px;" onclick="this.parentNode.parentNode.removeChild(this.parentNode);"></i>
                                <input type="hidden" name="old-attachments[]" value="{{ $attachment->id }}">
                                <a href="{{ $attachment->file() }}">{{ $attachment->file }}</a>
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="col-12 mb-5">
                <label for="attachments" class="form_label">@lang('title.new_attachment')</label>
                <file-input
                    id="attachments"
                    name="attachments[]"
                    text_select="@lang('title.select_file')"
                    text_selected="@lang('title.selected_files')"
                ></file-input>
            </div>


            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.tickets.show', $message->ticket->id) }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>

        </form>
    </x-admin.card>
@endsection
