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

    <div class="home-calendar-desktop">
        <div class="home-calendar-mobile__picker">
            <custom-date
                calendar_audience="host"
                stacked_calendar="true"
                max_date_prop="{{ Order::getMaxReserveDate() }}"
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
                :order_blocked_dates_prop='@json($home->disable_order_dates)'
                :host_closed_dates_prop='@json($home->disable_custom_dates)'
                :custom_prices_prop='@json($home->custom_prices_map)'
                :custom_min_nights_prop='@json($home->custom_min_nights_map)'
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
                text_reset_base_price="@lang('title.reset_base_price')"
                text_reset_base_price_description="@lang('text.reset_base_price_description')"
                text_off="@lang('title.off')"
                text_price_set_based_on_selected_first_date="@lang('text.price_set_based_on_selected_first_date')"
                text_custom_date_price_retry_hint="@lang('text.custom_date_price_retry_hint')"
                text_percentage="@lang('title.percentage')"
                text_no_off="@lang('title.no_off')"
                text_min_nights_warning_intro="@lang('text.min_nights_warning_intro')"
                text_min_nights_confirm_save="@lang('text.min_nights_confirm_save')"
                text_min_nights_blocked_order_night="@lang('text.min_nights_blocked_order_night')"
                text_min_nights_blocked_host_closed_checkin="@lang('text.min_nights_blocked_host_closed_checkin')"
                text_min_nights_blocked_order_checkin="@lang('text.min_nights_blocked_order_checkin')"
                text_host_cannot_select_booked_date="@lang('text.host_cannot_select_booked_date')"
                text_min_nights_blocked_max_date="@lang('text.min_nights_blocked_max_date')"
                text_min_nights_saved_with_limits="@lang('text.min_nights_saved_with_limits')"
            ></custom-date>
        </div>
        <p class="home-calendar-card__hint mt-3 mb-0">
            با کلیک بر روی یک یا چند روز، تغییرات را بصورت یکجا اعمال کنید.
        </p>
    </div>

    <div class="mt-5 text-center">
        <a href="{{ route('dashboard.homes.index') }}" class="btn btn-danger">@lang('title.return')</a>
    </div>
@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/home-calendar-mobile.css') }}">
@endsection
