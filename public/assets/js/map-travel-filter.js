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
    var onDateAppliedCallbacks = [];
    var onGuestAppliedCallbacks = [];

    function $(id) { return document.getElementById(id); }

    function isDesktopHomesPopup() {
        return !!document.querySelector('.homes-desktop-page') &&
            window.matchMedia('(min-width: 992px)').matches;
    }

    function positionDesktopDateSheet() {
        if (!dateSheet || !isDesktopHomesPopup()) {
            dateSheet?.classList.remove('map-travel-sheet--desktop-popup');
            return;
        }

        var badge = $('filterDateBadgeBtn');
        var dock = dateSheet.querySelector('.mobile-reserve-sheet__dock');
        if (!badge || !dock) return;

        dateSheet.classList.add('map-travel-sheet--desktop-popup');

        var rect = badge.getBoundingClientRect();
        var width = Math.min(460, window.innerWidth - 32);
        var right = Math.max(16, window.innerWidth - rect.right);
        if (right + width > window.innerWidth - 16) {
            right = Math.max(16, window.innerWidth - width - 16);
        }

        dock.style.position = 'fixed';
        dock.style.top = (rect.bottom + 8) + 'px';
        dock.style.right = right + 'px';
        dock.style.left = 'auto';
        dock.style.bottom = 'auto';
        dock.style.width = width + 'px';
        dock.style.maxWidth = 'calc(100vw - 32px)';
        dock.style.transform = 'none';
    }

    function resetDesktopDateSheetPosition() {
        if (!dateSheet) return;
        dateSheet.classList.remove('map-travel-sheet--desktop-popup');
        var dock = dateSheet.querySelector('.mobile-reserve-sheet__dock');
        if (!dock) return;
        dock.style.position = '';
        dock.style.top = '';
        dock.style.right = '';
        dock.style.left = '';
        dock.style.bottom = '';
        dock.style.width = '';
        dock.style.maxWidth = '';
        dock.style.transform = '';
    }

    function getInitialMonthOffset(allMonths, startIso) {
        if (!startIso || !global.MapJalaliUtils?.jalaliFromIso) return 0;
        var j = global.MapJalaliUtils.jalaliFromIso(String(startIso));
        if (!j) return 0;
        var idx = -1;
        allMonths.forEach(function (mo, i) {
            if (mo.year === j.year && mo.month === j.month) idx = i;
        });
        if (idx < 0) return 0;
        return Math.max(0, Math.min(idx, Math.max(0, allMonths.length - 2)));
    }

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
        if (sheet === dateSheet) {
            resetDesktopDateSheetPosition();
        }
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

        var dualMonth = isDesktopHomesPopup();
        var monthOffset = 0;
        if (dualMonth && global.MapJalaliUtils?.monthsInRangeFor) {
            monthOffset = getInitialMonthOffset(
                global.MapJalaliUtils.monthsInRangeFor(config.minDate, config.maxDate),
                dateState.startIso
            );
        }

        calendar = new global.MapJalaliCalendar(mount, {
            minDate: config.minDate,
            maxDate: config.maxDate,
            startDate: dateState.startIso,
            endDate: dateState.endIso,
            showClearButton: false,
            dualMonth: dualMonth,
            monthOffset: monthOffset,
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
        onDateAppliedCallbacks.forEach(function (fn) { fn(payload); });
        closeSheet(dateSheet);
    }

    function clearDates() {
        dateState.startIso = '';
        dateState.endIso = '';
        if (calendar) calendar.clear();
        onDateAppliedCallbacks.forEach(function (fn) { fn({ start_at: '', end_at: '' }); });
        closeSheet(dateSheet);
    }

    function applyGuest() {
        onGuestAppliedCallbacks.forEach(function (fn) { fn({ guest_count: String(guestCount) }); });
        closeSheet(guestSheet);
    }

    function clearGuest() {
        onGuestAppliedCallbacks.forEach(function (fn) { fn({ guest_count: '' }); });
        closeSheet(guestSheet);
    }

    function openDateFilter(initial) {
        initial = initial || {};
        dateState.startIso = global.MapJalaliUtils.jalaliSlashToIso(initial.start_at || '');
        dateState.endIso = global.MapJalaliUtils.jalaliSlashToIso(initial.end_at || '');
        calendar = null;
        openSheet(dateSheet);
        positionDesktopDateSheet();
        window.setTimeout(function () {
            initCalendar();
            positionDesktopDateSheet();
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

        window.addEventListener('resize', function () {
            if (dateSheet && dateSheet.classList.contains('is-open')) {
                positionDesktopDateSheet();
            }
        });
    }

    global.MapTravelFilter = {
        init: function (opts) {
            config = Object.assign(config, opts || {});
            bindEvents();
        },
        openDateFilter: openDateFilter,
        openGuestFilter: openGuestFilter,
        onDateApplied: function (fn) {
            if (typeof fn === 'function') {
                onDateAppliedCallbacks.push(fn);
            }
        },
        onGuestApplied: function (fn) {
            if (typeof fn === 'function') {
                onGuestAppliedCallbacks.push(fn);
            }
        },
    };
})(window);
