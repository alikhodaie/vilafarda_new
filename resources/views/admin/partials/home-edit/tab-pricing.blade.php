@php
    $adminPriceValue = function ($key, $fallback = null) {
        $v = old($key, $fallback);
        if ($v === null || $v === '') {
            return '';
        }
        return (string) (int) round((float) $v);
    };
@endphp

@include('admin.partials.home-edit.help', ['text' => '<strong>قیمت‌گذاری:</strong> نرخ هر شب بر اساس روز هفته و هزینهٔ نفر اضافه. مبالغ به تومان هستند؛ زیر هر فیلد معادل حروفی نمایش داده می‌شود.'])

<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="week_price">@lang('title.week_price')</label>
        <input type="text" class="form-control price-field" id="week_price" name="week_price"
               inputmode="numeric" autocomplete="off" required
               value="{{ $adminPriceValue('week_price', $home->week_price) }}">
        <small id="week_price_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
        <span class="text-muted small">شنبه تا سه‌شنبه — تومان</span>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="wed_price">@lang('title.wed_price')</label>
        <input type="text" class="form-control price-field" id="wed_price" name="wed_price"
               inputmode="numeric" autocomplete="off" required
               value="{{ $adminPriceValue('wed_price', $home->wed_price) }}">
        <small id="wed_price_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="thu_price">@lang('title.thu_price')</label>
        <input type="text" class="form-control price-field" id="thu_price" name="thu_price"
               inputmode="numeric" autocomplete="off" required
               value="{{ $adminPriceValue('thu_price', $home->thu_price) }}">
        <small id="thu_price_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="fri_price">@lang('title.fri_price')</label>
        <input type="text" class="form-control price-field" id="fri_price" name="fri_price"
               inputmode="numeric" autocomplete="off" required
               value="{{ $adminPriceValue('fri_price', $home->fri_price) }}">
        <small id="fri_price_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="price_per_surplus">@lang('title.price_per_surplus')</label>
        <input type="text" class="form-control price-field" id="price_per_surplus" name="price_per_surplus"
               inputmode="numeric" autocomplete="off" required
               value="{{ $adminPriceValue('price_per_surplus', $home->price_per_surplus) }}">
        <small id="price_per_surplus_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
        <p class="text-muted small mb-0 mt-1">مبلغ به ازای هر نفر اضافه در هر شب.</p>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="cleaning_fee">هزینه نظافت (تومان)</label>
        <input type="text" class="form-control price-field" id="cleaning_fee" name="cleaning_fee"
               inputmode="numeric" autocomplete="off"
               value="{{ $adminPriceValue('cleaning_fee', $home->cleaning_fee) }}">
        <small id="cleaning_fee_words" class="price-words text-muted d-block mt-1" style="display: none;"></small>
        <p class="text-muted small mb-0 mt-1">در صورت عدم نظافت توسط مهمان و کثیف بودن اقامتگاه، این مبلغ از مهمان دریافت می‌شود.</p>
        @error('cleaning_fee')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
