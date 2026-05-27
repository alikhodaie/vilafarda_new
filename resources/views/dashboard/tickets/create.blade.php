@extends('layouts.dashboard.dashboard', ['title' => __('title.create_ticket'), 'active' => 'tickets', 'breadcrumbs' => [
    ['url' => route('dashboard.tickets.index'), 'title' => __('title.tickets')],
    ['url' => null, 'title' => __('title.create_ticket')]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.create_ticket')
        </h3>
    </div>

    <form action="{{ route('dashboard.tickets.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">@lang('title.title')</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
        </div>

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
            <button class="btn btn-info">@lang('title.create')</button>
        </div>
    </form>
@endsection
