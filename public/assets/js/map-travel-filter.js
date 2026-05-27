/**
 * شیت فیلتر تاریخ و نفرات روی نقشه — بدون npm
 */
(function (global) {
    'use strict';

    var config = {
        minDate: '',
        maxDate: '',
        maxGuests: 20,
        labels: { start: 'تاریخ ورود', end: 'تاریخ خروج' },
    };

    var dateSheet, guestSheet;
    var dateState = { startIso: '', endIso: '' };
    var guestCount = 2;
    var calendar = null;
    var onDateApplied = null;
    var onGuestApplied = null;

    function $(id) { return document.getElementById(id); }

    function persianDigit(value) {
        return global.MapJalaliUtils
            ? global.MapJalaliUtils.toPersianNum(value)
            : String(value);
    }

    function openSheet(sheet) {
        sheet.classList.remove('is-closing');
        sheet.classList.add('is-open');
        sheet.setAttribute('aria-hidden', 'false');
        document.body.classList.add('map-travel-sheet-open');
    }

    function closeSheet(sheet) {
        if (!sheet.classList.contains('is-open')) return;
        sheet.classList.remove('is-open');
        sheet.classList.add('is-closing');
        window.setTimeout(function () {
            sheet.classList.remove('is-closing');
            sheet.setAttribute('aria-hidden', 'true');
            if (!document.querySelector('.map-travel-sheet.is-open')) {
                document.body.classList.remove('map-travel-sheet-open');
            }
        }, 480);
    }

    function updateGuestUI() {
        var countEl = $('mapFilterGuestCount');
        var minusBtn = $('mapFilterGuestMinus');
        var plusBtn = $('mapFilterGuestPlus');
        var breakdown = $('mapFilterGuestBreakdown');
        if (countEl) countEl.textContent = persianDigit(guestCount);
        if (minusBtn) minusBtn.disabled = guestCount <= 1;
        if (plusBtn) plusBtn.disabled = guestCount >= config.maxGuests;
        if (breakdown) breakdown.textContent = persianDigit(guestCount) + ' نفر';
    }

    function initCalendar() {
        var mount = $('mapFilterCalendarMount');
        if (!mount || !global.MapJalaliCalendar) return;

        calendar = new global.MapJalaliCalendar(mount, {
            minDate: config.minDate,
            maxDate: config.maxDate,
            startDate: dateState.startIso,
            endDate: dateState.endIso,
            showClearButton: false,
            onChange: function (payload) {
                dateState.startIso = payload.startDate || '';
                dateState.endIso = payload.endDate || '';
            },
        });
    }

    function applyDates() {
        if (!dateState.startIso || !dateState.endIso) {
            if (calendar && calendar.mountEl) {
                calendar.mountEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            return;
        }
        var payload = {
            start_at: global.MapJalaliUtils.isoToJalaliSlash(dateState.startIso),
            end_at: global.MapJalaliUtils.isoToJalaliSlash(dateState.endIso),
        };
        if (onDateApplied) onDateApplied(payload);
        closeSheet(dateSheet);
    }

    function clearDates() {
        dateState.startIso = '';
        dateState.endIso = '';
        if (calendar) calendar.clear();
        if (onDateApplied) onDateApplied({ start_at: '', end_at: '' });
        closeSheet(dateSheet);
    }

    function applyGuest() {
        if (onGuestApplied) onGuestApplied({ guest_count: String(guestCount) });
        closeSheet(guestSheet);
    }

    function clearGuest() {
        if (onGuestApplied) onGuestApplied({ guest_count: '' });
        closeSheet(guestSheet);
    }

    function openDateFilter(initial) {
        initial = initial || {};
        dateState.startIso = global.MapJalaliUtils.jalaliSlashToIso(initial.start_at || '');
        dateState.endIso = global.MapJalaliUtils.jalaliSlashToIso(initial.end_at || '');
        calendar = null;
        openSheet(dateSheet);
        window.setTimeout(function () {
            initCalendar();
            scrollCalendarToSelection();
        }, 80);
    }

    function scrollCalendarToSelection() {
        var mount = $('mapFilterCalendarMount');
        if (!mount) return;
        var target = mount.querySelector('.calendar-day.check-in, .calendar-day.check-out, .calendar-month-block');
        if (target) {
            var block = target.closest('.calendar-month-block') || target;
            var scroll = mount.querySelector('.calendar-scroll-months');
            if (scroll && block) {
                var top = block.offsetTop - 44;
                scroll.scrollTop = Math.max(0, top);
            }
        }
    }

    function openGuestFilter(initial) {
        var count = parseInt(initial.guest_count, 10);
        guestCount = count > 0 ? Math.min(config.maxGuests, count) : 2;
        updateGuestUI();
        openSheet(guestSheet);
    }

    function bindEvents() {
        dateSheet = $('mapDateFilterSheet');
        guestSheet = $('mapGuestFilterSheet');
        if (!dateSheet || !guestSheet) return;

        dateSheet.classList.add('is-calendar-only');

        dateSheet.querySelectorAll('[data-map-date-close]').forEach(function (el) {
            el.addEventListener('click', function () { closeSheet(dateSheet); });
        });
        guestSheet.querySelectorAll('[data-map-guest-close]').forEach(function (el) {
            el.addEventListener('click', function () { closeSheet(guestSheet); });
        });

        $('mapFilterCalendarBack')?.addEventListener('click', function () { closeSheet(dateSheet); });
        $('mapFilterDateApply')?.addEventListener('click', applyDates);
        $('mapFilterDateClear')?.addEventListener('click', clearDates);

        $('mapFilterGuestMinus')?.addEventListener('click', function () {
            guestCount = Math.max(1, guestCount - 1);
            updateGuestUI();
        });
        $('mapFilterGuestPlus')?.addEventListener('click', function () {
            guestCount = Math.min(config.maxGuests, guestCount + 1);
            updateGuestUI();
        });
        $('mapFilterGuestApply')?.addEventListener('click', applyGuest);
        $('mapFilterGuestClear')?.addEventListener('click', clearGuest);
    }

    global.MapTravelFilter = {
        init: function (opts) {
            config = Object.assign(config, opts || {});
            bindEvents();
        },
        openDateFilter: openDateFilter,
        openGuestFilter: openGuestFilter,
        onDateApplied: function (fn) { onDateApplied = fn; },
        onGuestApplied: function (fn) { onGuestApplied = fn; },
    };
})(window);
