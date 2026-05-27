@php
    $bedrooms = $sleepPrivatePlaces->map(function ($room) {
        return [
            'id' => $room->id,
            'single_bed' => (int) $room->single_bed,
            'double_bed' => (int) $room->double_bed,
            'traditional_bed' => (int) $room->traditional_bed,
            'more' => $room->more ?? '',
        ];
    })->values()->all();

    if ($bedrooms === []) {
        $bedrooms = [[
            'id' => null,
            'single_bed' => 0,
            'double_bed' => 0,
            'traditional_bed' => 0,
            'more' => '',
        ]];
    }
@endphp

@include('admin.partials.home-edit.help', ['text' => '<strong>فضای خواب:</strong> تعداد اتاق و تخت‌ها را مشخص کنید. با دکمه‌های + و − می‌توانید اتاق اضافه یا کم کنید (مثل نسخهٔ موبایل میزبان).'])

<div id="adminBedroomsRoot">
    <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
        <label class="form-label fw-semibold mb-0">تعداد اتاق خواب</label>
        <div class="btn-group" role="group" aria-label="تعداد اتاق">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adminBedroomsAdjustCount(-1)" aria-label="کم کردن">−</button>
            <span class="btn btn-light btn-sm disabled px-3" id="adminBedroomCountDisplay">{{ count($bedrooms) }}</span>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="adminBedroomsAdjustCount(1)" aria-label="افزودن">+</button>
        </div>
    </div>

    <div id="adminBedroomsList"></div>
    <script type="application/json" id="adminBedroomsInitialData">@json($bedrooms)</script>

    @if($sleepSharePlace)
        <div class="card mb-3 border-warning">
            <div class="card-body">
                <h6 class="card-title mb-2">فضای مشترک</h6>
                <p class="text-muted small">فقط برای اقامتگاه‌هایی که قبلاً فضای مشترک ثبت شده است.</p>
                <div class="row">
                    <div class="col-12 col-md-4 mb-2">
                        <label class="form-label">تخت یک نفره</label>
                        <input type="number" min="0" max="100" class="form-control"
                               name="sleep_share[single_bed]"
                               value="{{ old('sleep_share.single_bed', $sleepSharePlace->single_bed) }}">
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label class="form-label">تخت دو نفره</label>
                        <input type="number" min="0" max="100" class="form-control"
                               name="sleep_share[double_bed]"
                               value="{{ old('sleep_share.double_bed', $sleepSharePlace->double_bed) }}">
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label class="form-label">رخت‌خواب سنتی</label>
                        <input type="number" min="0" max="100" class="form-control"
                               name="sleep_share[traditional_bed]"
                               value="{{ old('sleep_share.traditional_bed', $sleepSharePlace->traditional_bed) }}">
                    </div>
                </div>
                <label class="form-label">سایر موارد</label>
                <input type="text" class="form-control" name="sleep_share[more]"
                       value="{{ old('sleep_share.more', $sleepSharePlace->more) }}">
            </div>
        </div>
    @endif

    <div class="mb-0">
        <label for="sleep_area_description" class="form-label">توضیحات فضای خواب</label>
        <textarea name="sleep_area_description" id="sleep_area_description" class="form-control" rows="3">{{ old('sleep_area_description', $home->sleep_area_description) }}</textarea>
        <p class="text-muted small mb-0 mt-1">توضیح تکمیلی برای مهمان دربارهٔ امکانات خواب.</p>
    </div>
</div>
