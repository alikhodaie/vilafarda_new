@extends('layouts.admin.admin', ['title' => __('title.edit discount'), 'active' => 'discounts'])

@section('content')
    <x-admin.card title="{{ __('title.edit discount') }}">
        <form action="{{ route('admin.discounts.update', $discount) }}" method="POST" class="p-3 row">
            @csrf
            @method('PUT')

            <discount-form
                title_expired_at="@lang('title.expired_at')"
                title_type="@lang('title.type')"
                title_user_type="@lang('title.user_type')"
                title_amount="@lang('title.amount')"
                title_users="@lang('title.users')"
                title_users_list="@lang('title.users_list')"
                title_sms_type="@lang('title.sms_type')"
                title_sms="@lang('title.sms')"
                type_old="{{ old('type', $discount->type) }}"
                user_type_old="{{ old('user_type', $discount->user_type) }}"
                amount_old="{{ old('amount', $discount->amount) }}"
                users_old="{{ old('users') }}"
                users_list_old="{{ old('users_list') }}"
                expired_at_old="{{ old('expired_at', $discount->expired_at) }}"
                sms_type_old="{{ old('sms_type', $discount->sms_data?->type) }}"
                sms_old="{{ old('sms', $discount->sms_data?->sms) }}"
                :user_types="{{ json_encode(\App\Models\Discount::USER_TYPES, true) }}"
                :types="{{ json_encode(\App\Models\Discount::TYPES, true) }}"
                placeholder="@lang('title.select_user')"
                select_label="@lang('title.select')"
                selected_label="@lang('title.selected')"
                deselect_label="@lang('title.remove')"
                no_result_text="@lang('text.empty_result')"
                no_options_text="@lang('text.empty_list')"
                users_route="{{ route('admin.ajax.users') }}"
                title_start_date="@lang('title.start_date')"
                title_end_date="@lang('title.end_date')"
                :is_edit="true"
            ></discount-form>

            <div class="col-12 mt-5 d-flex justify-content-center">
                <button class="btn btn-falcon-success mx-3">@lang('title.edit')</button>
                <a href="{{ route('admin.discounts.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
            </div>
        </form>
    </x-admin.card>
@endsection
