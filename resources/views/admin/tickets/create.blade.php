@extends('layouts.admin.admin', ['title' => __('title.create_ticket'), 'active' => 'tickets'])

@section('content')
    <x-admin.card title="{{ __('title.create_ticket') }}">
        <form action="{{ route('admin.tickets.store') }}" method="POST" class="p-3 row" enctype="multipart/form-data">
            @csrf

            <div class="col-12 mb-5">
                <label class="form-label" for="user">@lang('title.user') <span>*</span></label>
                <user-input
                    @if(old('user'))
                    old="{{ old('user') }}"
                    @endif
                    route="{{ route('admin.ajax.users') }}"
                    placeholder="@lang('text.select_user')"
                    select_label="@lang('title.select')"
                    selected_label="@lang('title.selected')"
                    deselect_label="@lang('title.remove')"
                    no_result_text="@lang('text.empty_result')"
                    no_options_text="@lang('text.empty_list')"
                ></user-input>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="title">@lang('title.title') <span>*</span></label>
                <input class="form-control" name="title" id="title" type="text" value="{{ old('title') }}"/>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="message">@lang('title.message') <span>*</span></label>
                <textarea class="form-control" name="message" id="message">{!! old('message') !!}</textarea>
            </div>

            <div class="col-12 mb-5">
                <label class="form-label" for="attachments">@lang('title.attachment') <span>*</span></label>
                <file-input
                    id="attachments"
                    name="attachments[]"
                    text_select="@lang('title.select_file')"
                    text_selected="@lang('title.selected_files')"
                ></file-input>
                <em class="text-muted">@lang('title.max_size'): {{ round(\App\Models\TicketAttachment::MAX_SIZE / 1000) }}MB @lang('title.type'): jpg,png,zip,rar</em>
            </div>


            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
