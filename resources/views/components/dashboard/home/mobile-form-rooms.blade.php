@props(['home'])

@php
    $bedrooms = $home->sleepPlaces->where('is_share', false)->values()->map(function ($room) {
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

<div class="card-mobile mb-3" id="mobileBedroomsRoot">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-door-open me-2"></i>
        اتاق‌های خواب
    </h5>
    <p class="text-mobile-muted mb-3" style="font-size: 12px;">تعداد اتاق خواب را مشخص کنید و برای هر اتاق، تخت‌ها را ثبت کنید.</p>

    <div class="mb-4">
        <label class="form-label-mobile d-block mb-2">تعداد اتاق خواب</label>
        <div class="mobile-qty-stepper mobile-room-count-stepper" data-min="0" data-max="20">
            <button type="button" class="qty-btn qty-minus" aria-label="کم کردن اتاق"
                    onclick="window.mobileBedroomsAdjustCount(-1)">−</button>
            <span class="qty-input qty-display" id="bedroomCountDisplay">{{ count($bedrooms) }}</span>
            <button type="button" class="qty-btn qty-plus" aria-label="افزودن اتاق"
                    onclick="window.mobileBedroomsAdjustCount(1)">+</button>
        </div>
    </div>

    <div id="mobileBedroomsList"></div>

    <div class="mt-3">
        <label for="sleep_area_description" class="form-label-mobile">توضیحات فضای خواب (اختیاری)</label>
        <textarea name="sleep_area_description" id="sleep_area_description" class="form-control form-control-mobile" rows="2"
                  placeholder="توضیح کلی درباره فضای خواب">{{ old('sleep_area_description', $home->sleep_area_description) }}</textarea>
    </div>
</div>

<script type="application/json" id="mobileBedroomsInitialData">@json($bedrooms)</script>
<script>
(function () {
    if (typeof window.mobileQtyAdjust !== 'function') {
        window.mobileQtyAdjust = function (btn, delta) {
            const stepper = btn.closest('.mobile-qty-stepper');
            if (!stepper) return;
            const input = stepper.querySelector('.qty-input');
            if (!input || input.tagName !== 'INPUT') return;
            const min = parseInt(stepper.dataset.min || input.min || '0', 10);
            const max = parseInt(stepper.dataset.max || input.max || '100', 10);
            let val = parseInt(input.value || '0', 10);
            if (isNaN(val)) val = 0;
            val = Math.min(max, Math.max(min, val + delta));
            input.value = String(val);
        };
    }

    function escapeHtml(str) {
        return String(str || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/"/g, '&quot;');
    }

    function defaultRoom() {
        return { id: null, single_bed: 0, double_bed: 0, traditional_bed: 0, more: '' };
    }

    function readInitialBedrooms() {
        const dataEl = document.getElementById('mobileBedroomsInitialData');
        if (!dataEl) return [defaultRoom()];
        try {
            const parsed = JSON.parse(dataEl.textContent || '[]');
            if (Array.isArray(parsed) && parsed.length > 0) {
                return parsed;
            }
        } catch (e) {
            //
        }
        return [defaultRoom()];
    }

    window.mobileBedroomsSyncFromDom = function () {
        const listEl = document.getElementById('mobileBedroomsList');
        if (!listEl) return;

        const cards = listEl.querySelectorAll('.mobile-bedroom-card');
        if (!cards.length) return;

        const synced = [];
        cards.forEach(function (card, index) {
            const idInput = card.querySelector(`input[name="sleep_room[${index}][id]"]`);
            synced.push({
                id: idInput ? (idInput.value || null) : null,
                single_bed: parseInt(card.querySelector(`input[name="sleep_room[${index}][single_bed]"]`)?.value || '0', 10) || 0,
                double_bed: parseInt(card.querySelector(`input[name="sleep_room[${index}][double_bed]"]`)?.value || '0', 10) || 0,
                traditional_bed: parseInt(card.querySelector(`input[name="sleep_room[${index}][traditional_bed]"]`)?.value || '0', 10) || 0,
                more: card.querySelector(`input[name="sleep_room[${index}][more]"]`)?.value || '',
            });
        });

        window.mobileBedroomsState.bedrooms = synced;
    };

    window.mobileBedroomsRender = function () {
        const listEl = document.getElementById('mobileBedroomsList');
        const countDisplay = document.getElementById('bedroomCountDisplay');
        if (!listEl || !window.mobileBedroomsState) return;

        listEl.innerHTML = '';
        const bedrooms = window.mobileBedroomsState.bedrooms;

        bedrooms.forEach(function (room, index) {
            const card = document.createElement('div');
            card.className = 'mobile-bedroom-card';
            const idField = room.id ? `<input type="hidden" name="sleep_room[${index}][id]" value="${room.id}">` : '';
            card.innerHTML = `
                ${idField}
                <div class="mobile-bedroom-card-header">
                    <strong>اتاق خواب ${index + 1}</strong>
                </div>
                <div class="mb-3">
                    <label class="form-label-mobile d-block mb-1">تخت یک‌نفره</label>
                    <div class="mobile-qty-stepper" data-min="0" data-max="100">
                        <button type="button" class="qty-btn qty-minus" aria-label="کم کردن" onclick="mobileQtyAdjust(this, -1)">−</button>
                        <input type="number" name="sleep_room[${index}][single_bed]" class="qty-input form-control-mobile" min="0" max="100" value="${room.single_bed || 0}" readonly>
                        <button type="button" class="qty-btn qty-plus" aria-label="زیاد کردن" onclick="mobileQtyAdjust(this, 1)">+</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-mobile d-block mb-1">تخت دو‌نفره</label>
                    <div class="mobile-qty-stepper" data-min="0" data-max="100">
                        <button type="button" class="qty-btn qty-minus" aria-label="کم کردن" onclick="mobileQtyAdjust(this, -1)">−</button>
                        <input type="number" name="sleep_room[${index}][double_bed]" class="qty-input form-control-mobile" min="0" max="100" value="${room.double_bed || 0}" readonly>
                        <button type="button" class="qty-btn qty-plus" aria-label="زیاد کردن" onclick="mobileQtyAdjust(this, 1)">+</button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-mobile d-block mb-1">رخت‌خواب سنتی</label>
                    <div class="mobile-qty-stepper" data-min="0" data-max="100">
                        <button type="button" class="qty-btn qty-minus" aria-label="کم کردن" onclick="mobileQtyAdjust(this, -1)">−</button>
                        <input type="number" name="sleep_room[${index}][traditional_bed]" class="qty-input form-control-mobile" min="0" max="100" value="${room.traditional_bed || 0}" readonly>
                        <button type="button" class="qty-btn qty-plus" aria-label="زیاد کردن" onclick="mobileQtyAdjust(this, 1)">+</button>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="form-label-mobile" for="sleep_room_${index}_more">سایر موارد</label>
                    <input type="text" id="sleep_room_${index}_more" name="sleep_room[${index}][more]"
                           class="form-control form-control-mobile" placeholder="مثلاً: مبل تخت‌خواب‌شو"
                           value="${escapeHtml(room.more)}">
                </div>
            `;
            listEl.appendChild(card);
        });

        if (countDisplay) {
            countDisplay.textContent = String(bedrooms.length);
        }
    };

    window.mobileBedroomsSetCount = function (count) {
        if (!window.mobileBedroomsState) return;
        window.mobileBedroomsSyncFromDom();
        count = Math.min(20, Math.max(0, count));
        const bedrooms = window.mobileBedroomsState.bedrooms;
        while (bedrooms.length < count) {
            bedrooms.push(defaultRoom());
        }
        while (bedrooms.length > count) {
            bedrooms.pop();
        }
        window.mobileBedroomsRender();
    };

    window.mobileBedroomsAdjustCount = function (delta) {
        if (!window.mobileBedroomsState) {
            window.initMobileBedrooms();
        }
        const next = window.mobileBedroomsState.bedrooms.length + delta;
        window.mobileBedroomsSetCount(next);
    };

    window.initMobileBedrooms = function (resetFromServer) {
        const root = document.getElementById('mobileBedroomsRoot');
        if (!root) return;

        if (!window.mobileBedroomsState || resetFromServer) {
            window.mobileBedroomsState = { bedrooms: readInitialBedrooms() };
        } else if (document.getElementById('mobileBedroomsList')?.children.length) {
            window.mobileBedroomsSyncFromDom();
        }

        window.mobileBedroomsRender();
    };

    window.initMobileBedrooms(true);
})();
</script>
