<!-- شیت فیلتر تاریخ سفر -->
<div id="mapDateFilterSheet" class="mobile-reserve-sheet map-travel-sheet" aria-hidden="true">
    <div class="mobile-reserve-sheet__backdrop" data-map-date-close></div>
    <div class="mobile-reserve-sheet__dock">
        <button type="button" class="mobile-reserve-sheet__close" aria-label="بستن" data-map-date-close>
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="mobile-reserve-sheet__panel map-date-sheet__panel">
            <div class="mobile-reserve-sheet__calendar-header">
                <button type="button" class="mobile-reserve-sheet__calendar-back" id="mapFilterCalendarBack" aria-label="بستن">
                    <i class="bi bi-arrow-right"></i>
                </button>
                <span class="mobile-reserve-sheet__calendar-title">انتخاب تاریخ سفر</span>
            </div>
            <div class="mobile-reserve-sheet__calendar-mount" id="mapFilterCalendarMount"></div>
            <div class="mobile-reserve-sheet__footer map-travel-sheet__actions-row map-date-sheet__footer">
                <button type="button" class="mobile-reserve-sheet__submit mobile-reserve-sheet__submit--secondary" id="mapFilterDateClear">پاک کردن</button>
                <button type="button" class="mobile-reserve-sheet__submit" id="mapFilterDateApply">اعمال</button>
            </div>
        </div>
    </div>
</div>

<!-- شیت فیلتر تعداد نفرات -->
<div id="mapGuestFilterSheet" class="mobile-reserve-sheet map-travel-sheet" aria-hidden="true">
    <div class="mobile-reserve-sheet__backdrop" data-map-guest-close></div>
    <div class="mobile-reserve-sheet__dock">
        <button type="button" class="mobile-reserve-sheet__close" aria-label="بستن" data-map-guest-close>
            <i class="bi bi-x-lg"></i>
        </button>
        <div class="mobile-reserve-sheet__panel">
            <div class="mobile-reserve-sheet__step mobile-reserve-sheet__step--form">
                <div class="mobile-reserve-sheet__body">
                    <div class="mobile-reserve-sheet__field">
                        <div class="mobile-reserve-sheet__guest-row">
                            <div class="mobile-reserve-sheet__guest-info">
                                <div class="mobile-reserve-sheet__guest-title">
                                    <i class="bi bi-people" aria-hidden="true"></i>
                                    <span>تعداد مسافران</span>
                                </div>
                                <p class="mobile-reserve-sheet__guest-breakdown" id="mapFilterGuestBreakdown"></p>
                            </div>
                            <div class="mobile-reserve-sheet__guest-counter">
                                <button type="button" class="mobile-reserve-sheet__guest-btn" id="mapFilterGuestMinus" aria-label="کم کردن">−</button>
                                <span class="mobile-reserve-sheet__guest-count" id="mapFilterGuestCount">۲</span>
                                <button type="button" class="mobile-reserve-sheet__guest-btn" id="mapFilterGuestPlus" aria-label="زیاد کردن">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-reserve-sheet__info-banner">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        <span>کودک زیر دو سال جزو نفرات حساب نمی‌شود.</span>
                    </div>
                </div>
                <div class="mobile-reserve-sheet__footer map-travel-sheet__actions-row">
                    <button type="button" class="mobile-reserve-sheet__submit mobile-reserve-sheet__submit--secondary" id="mapFilterGuestClear">همه</button>
                    <button type="button" class="mobile-reserve-sheet__submit" id="mapFilterGuestApply">اعمال</button>
                </div>
            </div>
        </div>
    </div>
</div>
