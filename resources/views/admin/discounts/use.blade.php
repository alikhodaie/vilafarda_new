@extends('layouts.admin.admin', ['title' => __('title.use user discount'), 'active' => 'discounts'])

@section('content')
    <x-admin.card title="{{ __('title.use user discount') }}">
        <form action="{{ route('admin.discounts.update.use', $discount) }}" method="POST" class="p-3 row">
            @csrf

            <user-input
                @if(request()->filled('user'))
                    old="{{ request('user') }}"
                @endif
                name="user"
                route="{{ route('admin.ajax.users', ['discount' => $discount->id]) }}"
                placeholder="@lang('title.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
            ></user-input>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success mx-3">@lang('title.submit')</button>
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
