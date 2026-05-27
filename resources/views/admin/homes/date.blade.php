@php
    use App\Classes\Date;
    use App\Models\Order;
@endphp
@extends('layouts.admin.admin', ['title' => __('title.edit home calender'), 'active' => 'homes'])

@section('content')
    <x-admin.card title="{{ __('title.edit home calender') }} — {{ $home->name }}">

        <div class="px-3 pt-3 pb-0">
            <div class="alert alert-light border small mb-3 text-secondary">
                <span class="fas fa-info-circle text-warning ms-1"></span>
                <strong>راهنمای تقویم:</strong>
                روزها را انتخاب کنید و با «ویرایش» قیمت، وضعیت پر/خالی،
                <strong>حداقل مدت رزرو (اقامت چندشبه)</strong> و تخفیف لحظه‌آخری را اعمال کنید.
                تخفیف رزرو چندشبه (بلندمدت) از
                <a href="{{ route('admin.homes.edit', ['home' => $home, 'open_tab' => 'tab-discount']) }}">بخش تخفیف ویرایش اقامتگاه</a>
                تنظیم می‌شود.
            </div>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('admin.homes.edit', $home) }}" class="btn btn-sm btn-outline-secondary">
                    <span class="fas fa-edit ms-1"></span> ویرایش اقامتگاه
                </a>
                <a href="{{ route('admin.homes.index') }}" class="btn btn-sm btn-outline-danger">
                    <span class="fas fa-arrow-right ms-1"></span> @lang('title.return')
                </a>
            </div>
        </div>

        <div class="px-3 pb-3 admin-home-calendar-wrap">
            <custom-date
                stacked_calendar="true"
                max_date_prop="{{ Order::getMaxReserveDate() }}"
                home_edit_url="{{ route('admin.homes.edit', $home) }}"
                route="{{ route('admin.homes.date.store', $home) }}"
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
                :holidays_prop="{{ Date::holidayList() }}"
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
    </x-admin.card>
@endsection

@section('bottom-assets')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/home-calendar-mobile.css') }}">
    <style>
        .admin-home-calendar-wrap .home-custom-date-picker {
            width: 100%;
        }
        .admin-home-calendar-wrap .persian-calendar--stacked {
            max-width: 100%;
        }
        @media (min-width: 992px) {
            .admin-home-calendar-wrap {
                max-width: 720px;
                margin: 0 auto;
            }
        }
    </style>
@endsection
