@extends('layouts.admin.admin', ['title' => __('title.withdraws'), 'active' => 'withdraws'])

@section('content')
    <x-admin.card title="{{ __('title.withdraws') }}">
        <form action="{{ route('admin.withdraws.store') }}" method="POST" class="p-3 row">
            @csrf

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="user">@lang('title.user') <span>*</span></label>
                <user-input
                    @if(old('user', request('user')))
                    old="{{ old('user', request('user')) }}"
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

            <div class="col-12 col-md-6 mb-5">
                <label class="form-label" for="status">@lang('title.status') <span>*</span></label>
                <select name="status" id="status" class="form-control">
                    <option value="">@lang('title.select')</option>
                    @foreach(\App\Models\Withdraw::STATUES as $status)
                        <option @if($status['value'] === old('status', \App\Models\Withdraw::PENDING)) selected @endif value="{{ $status['value'] }}">{{ $status['fa_text'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success">@lang('title.submit')</button>
                <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
                <a href="{{ route('admin.withdraws.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
