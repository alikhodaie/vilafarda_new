/**
 * تقویم شمسی سبک — بدون npm/Vue (الگو از PersianCalendar صفحه رزرو)
 * تبدیل تاریخ از datepicker.fa.js
 */
(function (global) {
    'use strict';

    function mod(a, b) { return a - (b * Math.floor(a / b)); }

    function leap_gregorian(year) {
        return ((year % 4) === 0) && (!(((year % 100) === 0) && ((year % 400) !== 0)));
    }

    var GREGORIAN_EPOCH = 1721425.5;

    function gregorian_to_jd(year, month, day) {
        return (GREGORIAN_EPOCH - 1) +
            (365 * (year - 1)) +
            Math.floor((year - 1) / 4) +
            (-Math.floor((year - 1) / 100)) +
            Math.floor((year - 1) / 400) +
            Math.floor((((367 * month) - 362) / 12) +
                ((month <= 2) ? 0 : (leap_gregorian(year) ? -1 : -2)) +
                day);
    }

    function jd_to_gregorian(jd) {
        var wjd = Math.floor(jd - 0.5) + 0.5;
        var depoch = wjd - GREGORIAN_EPOCH;
        var quadricent = Math.floor(depoch / 146097);
        var dqc = mod(depoch, 146097);
        var cent = Math.floor(dqc / 36524);
        var dcent = mod(dqc, 36524);
        var quad = Math.floor(dcent / 1461);
        var dquad = mod(dcent, 1461);
        var yindex = Math.floor(dquad / 365);
        var year = (quadricent * 400) + (cent * 100) + (quad * 4) + yindex;
        if (!((cent === 4) || (yindex === 4))) { year++; }
        var yearday = wjd - gregorian_to_jd(year, 1, 1);
        var leapadj = ((wjd < gregorian_to_jd(year, 3, 1)) ? 0 : (leap_gregorian(year) ? 1 : 2));
        var month = Math.floor((((yearday + leapadj) * 12) + 373) / 367);
        var day = (wjd - gregorian_to_jd(year, month, 1)) + 1;
        return [year, month, day];
    }

    function leap_persian(year) {
        return ((((((year - ((year > 0) ? 474 : 473)) % 2820) + 474) + 38) * 682) % 2816) < 682;
    }

    var PERSIAN_EPOCH = 1948320.5;

    function persian_to_jd(year, month, day) {
        var epbase = year - ((year >= 0) ? 474 : 473);
        var epyear = 474 + mod(epbase, 2820);
        return day +
            ((month <= 7) ? ((month - 1) * 31) : (((month - 1) * 30) + 6)) +
            Math.floor(((epyear * 682) - 110) / 2816) +
            (epyear - 1) * 365 +
            Math.floor(epbase / 2820) * 1029983 +
            (PERSIAN_EPOCH - 1);
    }

    function jd_to_persian(jd) {
        jd = Math.floor(jd) + 0.5;
        var depoch = jd - persian_to_jd(475, 1, 1);
        var cycle = Math.floor(depoch / 1029983);
        var cyear = mod(depoch, 1029983);
        var ycycle;
        if (cyear === 1029982) {
            ycycle = 2820;
        } else {
            var aux1 = Math.floor(cyear / 366);
            var aux2 = mod(cyear, 366);
            ycycle = Math.floor(((2134 * aux1) + (2816 * aux2) + 2815) / 1028522) + aux1 + 1;
        }
        var year = ycycle + (2820 * cycle) + 474;
        if (year <= 0) { year--; }
        var yday = (jd - persian_to_jd(year, 1, 1)) + 1;
        var month = (yday <= 186) ? Math.ceil(yday / 31) : Math.ceil((yday - 6) / 30);
        var day = (jd - persian_to_jd(year, month, 1)) + 1;
        return [year, month, day];
    }

    function gregorianToJalali(y, m, d) {
        var j = jd_to_persian(gregorian_to_jd(y, m, d));
        return { year: j[0], month: j[1], day: j[2] };
    }

    function jalaliToGregorian(jy, jm, jd) {
        var g = jd_to_gregorian(persian_to_jd(jy, jm, jd));
        return { year: g[0], month: g[1], day: g[2] };
    }

    function pad2(n) { return String(n).padStart(2, '0'); }

    function isoFromJalali(jy, jm, jd) {
        var g = jalaliToGregorian(jy, jm, jd);
        return g.year + '-' + pad2(g.month) + '-' + pad2(g.day);
    }

    function jalaliFromIso(iso) {
        if (!iso) return null;
        var p = iso.split('-').map(Number);
        if (p.length < 3) return null;
        return gregorianToJalali(p[0], p[1], p[2]);
    }

    function jalaliLabel(iso) {
        if (!iso) return '—';
        var j = jalaliFromIso(iso);
        if (!j) return '—';
        var months = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
        return toPersianNum(j.day) + ' ' + months[j.month - 1];
    }

    function isoToJalaliSlash(iso) {
        var j = jalaliFromIso(iso);
        if (!j) return '';
        return j.year + '/' + pad2(j.month) + '/' + pad2(j.day);
    }

    function jalaliSlashToIso(str) {
        if (!str) return '';
        var p = String(str).replace(/-/g, '/').split('/').map(Number);
        if (p.length < 3) return '';
        return isoFromJalali(p[0], p[1], p[2]);
    }

    function toPersianNum(num) {
        var digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return String(num).replace(/\d/g, function (d) { return digits[parseInt(d, 10)]; });
    }

    function compareIso(a, b) {
        return a < b ? -1 : (a > b ? 1 : 0);
    }

    function monthLength(jy, jm) {
        if (jm <= 6) return 31;
        if (jm <= 11) return 30;
        return leap_persian(jy) ? 30 : 29;
    }

    function firstWeekdayOffset(jy, jm) {
        var g = jalaliToGregorian(jy, jm, 1);
        var dt = new Date(g.year, g.month - 1, g.day);
        return (dt.getDay() + 1) % 7;
    }

    var MONTH_NAMES = ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'];
    var WEEK_DAYS = ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'];

    function MapJalaliCalendar(mountEl, options) {
        this.mountEl = mountEl;
        this.minDate = options.minDate || '';
        this.maxDate = options.maxDate || '';
        this.holidays = options.holidays || [];
        this.startDate = options.startDate || '';
        this.endDate = options.endDate || '';
        this.showClearButton = options.showClearButton !== false;
        this.onChange = options.onChange || function () {};
        this.render();
    }

    MapJalaliCalendar.prototype.isDisabled = function (iso) {
        if (this.minDate && compareIso(iso, this.minDate) < 0) return true;
        if (this.maxDate && compareIso(iso, this.maxDate) > 0) return true;
        return false;
    };

    MapJalaliCalendar.prototype.isFriday = function (iso) {
        var p = iso.split('-').map(Number);
        var dt = new Date(p[0], p[1] - 1, p[2]);
        return dt.getDay() === 5;
    };

    MapJalaliCalendar.prototype.isInRange = function (iso) {
        if (!this.startDate || !this.endDate) return false;
        return compareIso(iso, this.startDate) > 0 && compareIso(iso, this.endDate) < 0;
    };

    MapJalaliCalendar.prototype.selectIso = function (iso) {
        if (this.isDisabled(iso)) return;

        if (!this.startDate || (this.startDate && this.endDate)) {
            this.startDate = iso;
            this.endDate = '';
        } else if (compareIso(iso, this.startDate) <= 0) {
            this.startDate = iso;
            this.endDate = '';
        } else {
            this.endDate = iso;
        }
        this.onChange({ startDate: this.startDate, endDate: this.endDate });
        this.render();
    };

    MapJalaliCalendar.prototype.clear = function () {
        this.startDate = '';
        this.endDate = '';
        this.onChange({ startDate: '', endDate: '' });
        this.render();
    };

    MapJalaliCalendar.prototype.buildMonth = function (jy, jm) {
        var days = [];
        var offset = firstWeekdayOffset(jy, jm);
        var i;
        for (i = 0; i < offset; i++) {
            days.push({ empty: true });
        }
        var len = monthLength(jy, jm);
        for (var day = 1; day <= len; day++) {
            var iso = isoFromJalali(jy, jm, day);
            var disabled = this.isDisabled(iso);
            days.push({
                empty: false,
                iso: iso,
                day: toPersianNum(day),
                disabled: disabled,
                selected: iso === this.startDate || iso === this.endDate,
                inRange: this.isInRange(iso),
                checkIn: iso === this.startDate,
                checkOut: iso === this.endDate,
                isFriday: this.isFriday(iso),
            });
        }
        return days;
    };

    MapJalaliCalendar.prototype.monthsInRange = function () {
        if (!this.minDate || !this.maxDate) return [];
        var minJ = jalaliFromIso(this.minDate);
        var maxJ = jalaliFromIso(this.maxDate);
        var list = [];
        var y = minJ.year;
        var m = minJ.month;
        while (y < maxJ.year || (y === maxJ.year && m <= maxJ.month)) {
            list.push({ year: y, month: m });
            m++;
            if (m > 12) { m = 1; y++; }
        }
        return list;
    };

    MapJalaliCalendar.prototype.render = function () {
        var self = this;
        var months = this.monthsInRange();
        var startLabel = jalaliLabel(this.startDate);
        var endLabel = jalaliLabel(this.endDate);

        var html = '<div class="persian-calendar persian-calendar--stacked">';
        html += '<div class="calendar-range-header">';
        html += '<div class="calendar-range-cell' + (this.startDate && !this.endDate ? ' is-active' : '') + (this.startDate ? ' has-value' : '') + '">';
        html += '<span class="calendar-range-label">ورود</span><span class="calendar-range-value">' + startLabel + '</span></div>';
        html += '<div class="calendar-range-cell' + (this.startDate && !this.endDate ? ' is-active' : '') + (this.endDate ? ' has-value' : '') + '">';
        html += '<span class="calendar-range-label">خروج</span><span class="calendar-range-value">' + endLabel + '</span></div>';
        html += '</div><div class="calendar-scroll-months">';

        months.forEach(function (mo) {
            html += '<section class="calendar-month-block"><h4 class="calendar-month-title">';
            html += MONTH_NAMES[mo.month - 1] + ' ' + toPersianNum(mo.year);
            html += '</h4><div class="calendar-weekdays">';
            WEEK_DAYS.forEach(function (wd) { html += '<div class="weekday">' + wd + '</div>'; });
            html += '</div><div class="calendar-grid">';
            self.buildMonth(mo.year, mo.month).forEach(function (cell) {
                if (cell.empty) {
                    html += '<div class="calendar-day empty"></div>';
                    return;
                }
                var cls = 'calendar-day';
                if (cell.disabled) cls += ' disabled';
                else cls += ' available';
                if (cell.selected) cls += ' selected';
                if (cell.inRange) cls += ' in-range';
                if (cell.checkIn) cls += ' check-in';
                if (cell.checkOut) cls += ' check-out';
                if (cell.isFriday) cls += ' is-friday';
                html += '<div class="' + cls + '" data-iso="' + cell.iso + '"><div class="day-content">';
                html += '<div class="day-number">' + cell.day + '</div></div></div>';
            });
            html += '</div></section>';
        });

        html += '</div>';
        if (this.showClearButton) {
            html += '<div class="calendar-stacked-footer">';
            html += '<button type="button" class="calendar-clear-btn" data-action="clear"><i class="bi bi-trash"></i> پاک کردن</button>';
            html += '</div>';
        }
        html += '</div>';

        this.mountEl.innerHTML = html;

        this.mountEl.querySelectorAll('.calendar-day[data-iso]').forEach(function (el) {
            el.addEventListener('click', function () {
                self.selectIso(el.getAttribute('data-iso'));
            });
        });
        if (this.showClearButton) {
            var clearBtn = this.mountEl.querySelector('[data-action="clear"]');
            if (clearBtn) {
                clearBtn.addEventListener('click', function () { self.clear(); });
            }
        }
    };

    global.MapJalaliUtils = {
        isoToJalaliSlash: isoToJalaliSlash,
        jalaliSlashToIso: jalaliSlashToIso,
        jalaliLabel: jalaliLabel,
        toPersianNum: toPersianNum,
    };
    global.MapJalaliCalendar = MapJalaliCalendar;
})(window);
