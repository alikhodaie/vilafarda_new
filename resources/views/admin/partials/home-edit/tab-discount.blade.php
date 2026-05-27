@include('admin.partials.home-edit.help', ['text' => '<strong>تخفیف‌ها:</strong> تخفیف لحظه‌آخری برای روز خالی امروز در تقویم، و تخفیف اقامت بلندمدت برای رزروهای چندشبه.'])

<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="off">@lang('title.off') — تخفیف لحظه‌آخری</label>
        <input min="0" max="50" type="number" class="form-control" id="off" name="off" required
               value="{{ old('off', $home->off) }}">
        <p class="text-muted small mb-0 mt-1">اگر تقویم برای <strong>امروز</strong> خالی بماند، این درصد روی قیمت همان روز اعمال می‌شود.</p>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="daily_off" class="form-label">حداقل تعداد شب (تخفیف بلندمدت)</label>
        <input class="form-control" type="number" id="daily_off" name="daily_off" min="0" max="90" required
               value="{{ old('daily_off', $home->daily_off) }}">
        <p class="text-muted small mb-0 mt-1">صفر = بدون تخفیف بلندمدت.</p>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="daily_off_amount" class="form-label">درصد تخفیف بلندمدت</label>
        <select class="form-control" id="daily_off_amount" name="daily_off_amount" required>
            @foreach(\App\Models\Home::DAILY_OFF_AMOUNTS as $item)
                <option value="{{ $item['value'] }}"
                        {{ old('daily_off_amount', $home->daily_off_amount) === $item['value'] ? 'selected' : '' }}>{{ $item['text'] }}</option>
            @endforeach
        </select>
        <p class="text-muted small mb-0 mt-1">برای رزروهایی که تعداد شب آن‌ها از حداقل بالا بیشتر باشد.</p>
    </div>
</div>
