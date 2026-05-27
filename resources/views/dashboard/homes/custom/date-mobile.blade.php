@php use App\Models\Order; @endphp
@extends('layouts.main.main_mobile', ['title' => 'ویرایش تقویم'])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home-calendar-mobile.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="home-calendar-page">
        <div class="container px-3 pt-2 pb-1">
            @include('dashboard.partials.home-edit-mobile-tabs', ['home' => $home, 'mode' => 'calendar'])
        </div>

        <div class="container px-3 pb-3">
            <div class="card-mobile home-calendar-card">
                <h2 class="home-calendar-card__heading">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    <span class="home-calendar-card__heading-label">تقویم اقامتگاه</span>
                    <span class="home-calendar-card__home-name">{{ $home->name }}</span>
                </h2>

                <div class="home-calendar-mobile__picker">
                    <custom-date
                        stacked_calendar="true"
                        max_date_prop="{{ Order::getMaxReserveDate() }}"
                        home_edit_url="{{ route('dashboard.homes.edit', $home) }}"
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
                </div>

                <div class="home-calendar-card__intro">
                    <p class="home-calendar-card__title">پر یا خالی شدن تقویم · تعیین نرخ روزهای خاص</p>
                    <p class="home-calendar-card__hint">
                        با کلیک بر روی یک یا چند روز، تغییرات را بصورت یکجا اعمال کنید.
                    </p>
                </div>
            </div>

            <a href="{{ route('dashboard.homes.index') }}" class="btn btn-mobile-secondary w-100 mt-3">
                <i class="bi bi-arrow-right me-1" aria-hidden="true"></i>
                @lang('title.return')
            </a>
        </div>
    </div>
@endsection
