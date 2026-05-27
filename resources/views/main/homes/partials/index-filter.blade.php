@php use Morilog\Jalali\Jalalian; @endphp
<div class="row align-items-center justify-content-center">
    <div class="col-md-4 col-12">
        <div class="form-group">
            <date-picker
                placeholder="تاریخ ورود"
                min="{{ \App\Models\Order::getMinReserveDate() }}"
                max="{{ \App\Models\Order::getMaxReserveDate() }}"
                type="date"
                name="start_at"
                value="{{ (request('start_at')) ? Jalalian::fromFormat('Y/m/d', request('start_at'))->toCarbon(): null }}"
            ></date-picker>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="form-group">
            <date-picker
                placeholder="تاریخ خروج"
                min="{{ \App\Models\Order::getMinReserveDate() }}"
                max="{{ \App\Models\Order::getMaxReserveDate() }}"
                type="date"
                name="end_at"
                value="{{ (request('start_at')) ? \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', request('end_at'))->toCarbon(): null }}"
            ></date-picker>
        </div>
    </div>
    <div class="col-md-2 col-12 text-center">
        <guest-number-input
            count_guest_text="@lang('title.guest_count')"
            name="guest"
        ></guest-number-input>
    </div>
    <div class="col-md-2 col-12">
        <div class="text-center">
            <label for="fast_reserve" class="form-label m-l-10">@lang('title.fast_reserve')</label>
            <input type="checkbox" class="form-check-input" id="fast_reserve" name="fast_reserve"
                   @if(request('fast_reserve')) checked @endif>
        </div>
    </div>
</div>
