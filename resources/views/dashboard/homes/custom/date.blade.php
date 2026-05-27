@php use App\Models\Order; @endphp
@extends('layouts.dashboard.dashboard', ['title' => __('title.edit_home_date') .' - '. $home->name , 'active' => 'my-homes', 'breadcrumbs' => [
    ['url' => route('dashboard.homes.index'), 'title' => __('title.my_homes')],
    ['url' => null, 'title' => __('title.edit_home_date')],
    ['url' => null, 'title' =>  $home->name]
]])

@section('content')
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-3 mb-sm-0">
            @lang('title.edit_home_date') - {{ $home->name }}
        </h3>
    </div>

    <custom-date
        route="{{ route('dashboard.homes.custom.date.store', $home) }}"
        csrf="{{ csrf_token() }}"
        date_name="dates"
        price_name="price"
        is_active_name="is_active"
        placeholder="@lang('text.select_custom_date')"
        week_price="{{ $home->week_price }}"
        wed_price="{{ $home->wed_price }}"
        thu_price="{{ $home->thu_price }}"
        fri_price="{{ $home->fri_price }}"
        min_date="{{ Order::getMinReserveDate() }}"
        :all_custom_dates='@json($home->custom_dates)'
        :custom_dates="{{ $home->disable_custom_dates }}"
        :custom_prices_prop="{{ $home->custom_prices->pluck('price', 'date') }}"
        :custom_min_nights_prop='@json($home->custom_min_nights_map)'
        :disable_dates_prop="{{ $home->disable_order_dates }}"
        :holidays_prop="{{ \App\Classes\Date::holidayList() }}"
        text_submit="@lang('title.submit')"
        button_cancel_text="@lang('title.cancel')"
        text_delete_changes="@lang('title.delete_changes')"
        text_set_custom_price="@lang('title.set_custom_price')"
        text_set_custom_reserve="@lang('title.text_set_custom_reserve')"
        select_range_days="@lang('title.select_range_days')"
        text_edit="@lang('title.edit')"
        text_remove_selected="@lang('title.remove_selected')"
        text_day_selected="@lang('title.day_selected')"
        text_price="@lang('title.price')"
        text_active_or_deactivate_days="@lang('title.active_or_deactivate_days')"
        text_is_active_description="@lang('text.is_active_description')"
        text_off="@lang('title.off')"
        text_price_set_based_on_selected_first_date="@lang('text.price_set_based_on_selected_first_date')"
        text_percentage="@lang('title.percentage')"
        text_no_off="@lang('title.no_off')"
        text_min_nights_warning_intro="@lang('text.min_nights_warning_intro')"
        text_min_nights_confirm_save="@lang('text.min_nights_confirm_save')"
        text_min_nights_blocked_order_night="@lang('text.min_nights_blocked_order_night')"
        text_min_nights_blocked_host_closed_checkin="@lang('text.min_nights_blocked_host_closed_checkin')"
        text_min_nights_blocked_order_checkin="@lang('text.min_nights_blocked_order_checkin')"
        text_min_nights_blocked_max_date="@lang('text.min_nights_blocked_max_date')"
        text_min_nights_saved_with_limits="@lang('text.min_nights_saved_with_limits')"
    ></custom-date>

{{--    <div class="mb-4 mt-3">--}}
{{--        <h3 class="mb-3 mb-sm-0">--}}
{{--            @lang('title.fast_reserve')--}}
{{--        </h3>--}}
{{--        <p>میتوانید با انتخاب دوره رزور سریع امکان رزرو سریع (رزرو بدون تاییدیه) را در بازه انتخاب شده فراهم کنید</p>--}}
{{--    </div>--}}

    @php
        $start = ($home->fast_reserve_start_at)
            ? $home->fast_reserve_start_at->format('Y/m/d')
            : null;

        $end = ($home->fast_reserve_end_at)
            ? $home->fast_reserve_end_at->format('Y/m/d')
            : null
    @endphp

{{--    <change-fast-reserve-date--}}
{{--        route="{{ route('dashboard.homes.custom.date.update.fast.reserve', $home) }}"--}}
{{--        csrf="{{ csrf_token() }}"--}}
{{--        :old="{{ json_encode([$start, $end]) }}"--}}
{{--        button_title="@lang('title.submit')"--}}
{{--        min="{{ \App\Models\Order::getMinReserveDate()->format('Y/m/d') }}"--}}
{{--    ></change-fast-reserve-date>--}}

    <div class="mt-5 text-center">
        <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
    </div>
@endsection


@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker-custom.css') }}">
@endsection
