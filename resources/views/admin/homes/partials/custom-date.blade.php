@php
    use App\Classes\Date;
    use App\Models\Order;

    $stacked = $stacked ?? false;
@endphp
<custom-date
    calendar_audience="admin"
    @if($stacked)
        stacked_calendar="true"
        max_date_prop="{{ Order::getMaxReserveDate() }}"
    @endif
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
    :order_blocked_dates_prop='@json($home->disable_order_dates)'
    :host_closed_dates_prop='@json($home->disable_custom_dates)'
    :custom_prices_prop='@json($home->custom_prices_map)'
    :custom_min_nights_prop='@json($home->custom_min_nights_map)'
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
