@props(['home'])

@php
    $offValue = (int) old('off', $home->off ?? 0);
    $dailyOffValue = (int) old('daily_off', $home->daily_off ?? 0);
@endphp

<div class="card-mobile mb-3">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-percent me-2"></i>
        تخفیف
    </h5>

    <div class="mb-4">
        <label for="off" class="form-label-mobile">تخفیف لحظه آخری</label>
        <select name="off" id="off" class="form-select form-control-mobile">
            @if(! array_key_exists($offValue, \App\Models\Home::DAILY_OFF_AMOUNTS))
                <option value="{{ $offValue }}" selected>{{ $offValue }} درصد</option>
            @endif
            @foreach(\App\Models\Home::DAILY_OFF_AMOUNTS as $item)
                <option value="{{ $item['value'] }}"
                    @if($offValue === (int) $item['value']) selected @endif>
                    {{ $item['text'] }}
                </option>
            @endforeach
        </select>
        <p class="text-mobile-muted d-block mt-2 mb-0" style="font-size: 12px; line-height: 1.7;">
            اگر تقویم اقامتگاه برای <strong>امروز</strong> خالی بماند و تا رسیدن همان روز رزروی ثبت نشود،
            این درصد تخفیف به‌صورت خودکار روی قیمت همان روز در تقویم شما اعمال می‌شود.
            برای روزهای آینده تا رسیدن نوبت همان روز، قیمت پایه نمایش داده می‌شود.
        </p>
        @error('off')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label-mobile d-block">تخفیف اقامت بلندمدت</label>
        <p class="text-mobile-muted mb-2" style="font-size: 12px;">برای رزروهایی که تعداد شب آن‌ها از حداقل زیر بیشتر باشد.</p>

        <label for="daily_off" class="form-label-mobile">حداقل تعداد شب</label>
        <div class="mobile-qty-stepper" data-min="0" data-max="90">
            <button type="button" class="qty-btn qty-minus" aria-label="کم کردن" onclick="mobileQtyAdjust(this, -1)">−</button>
            <input type="number" name="daily_off" id="daily_off" class="qty-input form-control-mobile"
                   min="0" max="90" inputmode="numeric" value="{{ $dailyOffValue }}" readonly>
            <button type="button" class="qty-btn qty-plus" aria-label="زیاد کردن" onclick="mobileQtyAdjust(this, 1)">+</button>
        </div>
        @error('daily_off')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-0">
        <label for="daily_off_amount" class="form-label-mobile">درصد تخفیف بلندمدت</label>
        <select name="daily_off_amount" id="daily_off_amount" class="form-select form-control-mobile">
            @foreach(\App\Models\Home::DAILY_OFF_AMOUNTS as $item)
                <option value="{{ $item['value'] }}"
                    @if((int) old('daily_off_amount', $home->daily_off_amount ?? 0) === (int) $item['value']) selected @endif>
                    {{ $item['text'] }}
                </option>
            @endforeach
        </select>
        @error('daily_off_amount')
            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>
</div>

<script>
if (typeof window.mobileQtyAdjust !== 'function') {
    window.mobileQtyAdjust = function (btn, delta) {
        const stepper = btn.closest('.mobile-qty-stepper');
        if (!stepper) return;
        const input = stepper.querySelector('.qty-input');
        if (!input) return;
        const min = parseInt(stepper.dataset.min || input.min || 0, 10);
        const max = parseInt(stepper.dataset.max || input.max || 999, 10);
        let value = parseInt(input.value, 10);
        if (Number.isNaN(value)) value = min;
        value = Math.min(max, Math.max(min, value + delta));
        input.value = String(value);
        input.dispatchEvent(new Event('change', { bubbles: true }));
    };
}
</script>
