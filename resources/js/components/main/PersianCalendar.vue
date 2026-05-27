<template>
    <div class="persian-calendar" :class="{ 'persian-calendar--stacked': stackedMonths }">
        <template v-if="stackedMonths">
            <div v-if="selectionMode === 'range'" class="calendar-range-header">
                <div class="calendar-range-cell" :class="{ 'is-active': !endDate && startDate, 'has-value': !!startDate }">
                    <span class="calendar-range-label">ورود</span>
                    <span class="calendar-range-value">{{ startDateLabel }}</span>
                </div>
                <div class="calendar-range-cell" :class="{ 'is-active': startDate && !endDate, 'has-value': !!endDate }">
                    <span class="calendar-range-label">خروج</span>
                    <span class="calendar-range-value">{{ endDateLabel }}</span>
                </div>
            </div>

            <div class="calendar-scroll-months">
                <section
                    v-for="month in stackedMonthList"
                    :key="month.key"
                    class="calendar-month-block">
                    <h4 class="calendar-month-title">{{ month.title }}</h4>
                    <div class="calendar-weekdays">
                        <div class="weekday" v-for="day in weekDays" :key="day">{{ day }}</div>
                    </div>
                    <div class="calendar-grid">
                        <div
                            v-for="(day, index) in month.days"
                            :key="month.key + '-' + index"
                            class="calendar-day"
                            :class="dayCellClasses(day)"
                            :style="day.gridColumnStart ? { gridColumnStart: day.gridColumnStart } : null"
                            @click="selectDate(day)">
                            <div class="day-content">
                                <div class="day-number">{{ day.day }}</div>
                                <span
                                    v-if="day.minNights > 1"
                                    class="day-min-nights-badge"
                                    aria-hidden="true">{{ day.minNightsLabel }}</span>
                                <div v-if="day.available && !day.disabled && day.price" class="day-price">
                                    {{ formatPrice(day.price) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="calendar-stacked-footer">
                <button type="button" class="calendar-clear-btn" @click="clearSelection">
                    <i class="bi bi-trash" aria-hidden="true"></i>
                    پاک کردن
                </button>
            </div>
        </template>

        <template v-else>
        <div class="calendar-header">
            <h3 class="calendar-title">انتخاب تاریخ رزرو</h3>
            <div class="calendar-month-nav">
                <button 
                    type="button" 
                    class="nav-arrow" 
                    @click="previousMonth"
                    :disabled="!canGoPrevious">
                    <
                </button>
                <div class="month-year">
                    {{ currentMonthName }} {{ toPersianNum(currentYear) }}
                </div>
                <button 
                    type="button" 
                    class="nav-arrow" 
                    @click="nextMonth"
                    :disabled="!canGoNext">
                    >
                </button>
            </div>
        </div>

        <!-- Days of Week -->
        <div class="calendar-weekdays">
            <div class="weekday" v-for="day in weekDays" :key="day">{{ day }}</div>
        </div>

        <!-- Calendar Grid -->
        <div class="calendar-grid">
            <div 
                v-for="(day, index) in calendarDays" 
                :key="index"
                class="calendar-day"
                :class="dayCellClasses(day)"
                @click="selectDate(day)"
            >
                <div class="day-content">
                    <div class="day-number">{{ day.day }}</div>
                    <span
                        v-if="day.minNights > 1"
                        class="day-min-nights-badge"
                        aria-hidden="true">{{ day.minNightsLabel }}</span>
                    <div v-if="day.available && !day.disabled" class="available-indicator"></div>
                    <div v-if="day.available && !day.disabled && day.price" class="day-price">
                        {{ formatPrice(day.price) }}
                    </div>
                </div>
            </div>
        </div>
        </template>
    </div>
</template>

<script>
// Import moment-jalaali - it extends moment with Jalali calendar support
import moment from 'moment-jalaali';

export default {
    name: 'PersianCalendar',
    props: {
        minDate: {
            type: String,
            default: ''
        },
        maxDate: {
            type: String,
            default: ''
        },
        disableDates: {
            type: Array,
            default: () => []
        },
        customPrices: {
            type: Object,
            default: () => {}
        },
        customMinNights: {
            type: Object,
            default: () => ({})
        },
        holidays: {
            type: Array,
            default: () => []
        },
        weekPrice: {
            type: [String, Number],
            default: 0
        },
        wedPrice: {
            type: [String, Number],
            default: 0
        },
        thuPrice: {
            type: [String, Number],
            default: 0
        },
        friPrice: {
            type: [String, Number],
            default: 0
        },
        off: {
            type: [String, Number],
            default: 0
        },
        startDate: {
            type: String,
            default: ''
        },
        endDate: {
            type: String,
            default: ''
        },
        stackedMonths: {
            type: Boolean,
            default: false
        },
        selectionMode: {
            type: String,
            default: 'range'
        },
        selectedDates: {
            type: Array,
            default: () => []
        },
        reservedDates: {
            type: Array,
            default: () => []
        },
        hostClosedDates: {
            type: Array,
            default: () => []
        },
        minEndDate: {
            type: String,
            default: ''
        },
        maxEndDate: {
            type: String,
            default: ''
        },
        minNightsDisplay: {
            type: String,
            default: 'effective'
        }
    },
    data() {
        return {
            currentDate: moment(), // moment-jalaali automatically uses Jalali calendar
            weekDays: ['ش', 'ی', 'د', 'س', 'چ', 'پ', 'ج'],
            monthNames: [
                'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
                'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'
            ]
        }
    },
    computed: {
        currentYear() {
            return this.currentDate.format('jYYYY');
        },
        currentMonthName() {
            return this.monthNames[parseInt(this.currentDate.format('jM')) - 1];
        },
        currentMonth() {
            return parseInt(this.currentDate.format('jM'));
        },
        currentYearNum() {
            return parseInt(this.currentDate.format('jYYYY'));
        },
        calendarDays() {
            const selectionKey = `${this.startDate}|${this.endDate}|${this.minEndDate}`;
            void selectionKey;
            return this.buildDaysForMonth(this.currentYearNum, this.currentMonth);
        },
        stackedMonthList() {
            if (!this.stackedMonths || !this.minDate || !this.maxDate) {
                return [];
            }

            // Ensure checkout selection state triggers month grid refresh.
            const selectionKey = `${this.startDate}|${this.endDate}|${this.minEndDate}`;

            const months = [];
            let cursor = moment(this.minDate).startOf('jMonth');
            const endMonth = moment(this.maxDate).startOf('jMonth');

            while (!cursor.isAfter(endMonth, 'month')) {
                const jYear = parseInt(cursor.format('jYYYY'), 10);
                const jMonth = parseInt(cursor.format('jM'), 10);
                months.push({
                    key: `${cursor.format('jYYYY-jMM')}-${selectionKey}`,
                    title: `${this.monthNames[jMonth - 1]} ${this.toPersianNum(cursor.format('jYYYY'))}`,
                    days: this.buildDaysForMonth(jYear, jMonth)
                });
                cursor = cursor.add(1, 'jMonth');
            }

            return months;
        },
        startDateLabel() {
            return this.startDate ? this.formatJalaliLabel(this.startDate) : '—';
        },
        endDateLabel() {
            return this.endDate ? this.formatJalaliLabel(this.endDate) : '—';
        },
        canGoPrevious() {
            if (!this.minDate) return true;
            const minMoment = moment(this.minDate);
            const currentStart = moment(this.currentDate).startOf('jMonth');
            return currentStart.isAfter(minMoment, 'day');
        },
        canGoNext() {
            if (!this.maxDate) return true;
            const maxMoment = moment(this.maxDate);
            const currentEnd = moment(this.currentDate).endOf('jMonth');
            return currentEnd.isBefore(maxMoment, 'day');
        }
    },
    methods: {
        toPersianNum(num) {
            const persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return num.toString().replace(/\d/g, (digit) => persianDigits[parseInt(digit)]);
        },
        formatJalaliLabel(dateStr) {
            return this.toPersianNum(moment(dateStr).format('jYYYY-jMM-jDD'));
        },
        parseCalendarDate(dateStr) {
            if (!dateStr) {
                return null;
            }

            return moment(dateStr, ['YYYY/MM/DD', 'YYYY-MM-DD', 'jYYYY/jM/jD', 'jYYYY-jMM-jDD'], true);
        },
        isSameCalendarDay(dateA, dateB) {
            const first = this.parseCalendarDate(dateA);
            const second = this.parseCalendarDate(dateB);

            if (!first || !second || !first.isValid() || !second.isValid()) {
                return false;
            }

            return first.isSame(second, 'day');
        },
        dayCellClasses(day) {
            if (day.empty) {
                return { empty: true };
            }

            return {
                disabled: day.disabled,
                'checkout-blocked': day.checkoutBlocked,
                'checkout-available': day.checkoutAvailable,
                available: day.available && !day.disabled,
                selected: day.selected,
                'selected-multiple': this.selectionMode === 'multiple' && day.selected,
                reserved: day.isReserved,
                'custom-price': day.hasCustomPrice,
                'in-range': day.inRange,
                'check-in': day.isCheckIn,
                'check-out': day.isCheckOut,
                'is-friday': day.isFriday
            };
        },
        buildDaysForMonth(jYear, jMonth) {
            const days = [];
            const firstDayOfMonth = moment(`${jYear}/${jMonth}/1`, 'jYYYY/jM/jD').startOf('jMonth');
            const lastDayOfMonth = moment(firstDayOfMonth).endOf('jMonth');
            const daysInMonth = lastDayOfMonth.jDate();
            const firstDayWeek = firstDayOfMonth.day();
            const adjustedFirstDay = (firstDayWeek + 1) % 7;

            if (!this.stackedMonths) {
                for (let i = 0; i < adjustedFirstDay; i++) {
                    days.push({ empty: true });
                }
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const jalaliDate = moment(`${jYear}/${jMonth}/${day}`, 'jYYYY/jM/jD').startOf('day');

                const gregorianDate = jalaliDate.toDate();
                const dateStr = jalaliDate.format('YYYY/MM/DD');
                const datePrice = this.getDatePrice(gregorianDate);
                const minNightsOptions = {
                    maxDate: this.maxDate,
                    disableDates: this.disableDates,
                    reservedDates: this.reservedDates,
                    orderBlockedDates: this.disableDates,
                };
                const minNights = this.minNightsDisplay === 'configured'
                    ? this.getConfiguredMinNightsForDate(this.customMinNights, dateStr)
                    : this.getEffectiveMinNightsForDate(this.customMinNights, dateStr, minNightsOptions);

                const dayState = this.resolveDayState(dateStr, gregorianDate);

                const dayCell = {
                    day: this.toPersianNum(day),
                    date: dateStr,
                    jalaliDate,
                    gregorianDate,
                    disabled: dayState.disabled,
                    checkoutBlocked: dayState.checkoutBlocked,
                    checkoutAvailable: dayState.checkoutAvailable,
                    available: dayState.available,
                    price: datePrice,
                    minNights,
                    minNightsLabel: minNights > 1 ? `${this.toPersianNum(minNights)} شب` : '',
                    selected: this.isSelected(dateStr),
                    inRange: this.isInRange(dateStr),
                    isCheckIn: this.isSameCalendarDay(dateStr, this.startDate),
                    isCheckOut: this.isSameCalendarDay(dateStr, this.endDate),
                    isFriday: gregorianDate.getDay() === 5,
                    isReserved: this.isReservedDate(dateStr),
                    hasCustomPrice: this.hasCustomPrice(dateStr)
                };

                if (this.stackedMonths && day === 1 && adjustedFirstDay > 0) {
                    dayCell.gridColumnStart = adjustedFirstDay + 1;
                }

                days.push(dayCell);
            }

            return days;
        },
        clearSelection() {
            if (this.selectionMode === 'multiple') {
                this.$emit('dates-changed', []);
                return;
            }
            this.$emit('dates-cleared');
        },
        isReservedDate(dateStr) {
            if (!this.reservedDates || !this.reservedDates.length) {
                return false;
            }
            const formatted = moment(dateStr).format('YYYY/MM/DD');
            return this.reservedDates.some((d) => {
                if (!d) {
                    return false;
                }
                return moment(d).format('YYYY/MM/DD') === formatted
                    || String(d).replace(/-/g, '/') === formatted;
            });
        },
        hasCustomPrice(dateStr) {
            if (!this.customPrices) {
                return false;
            }
            const keyAlt = moment(dateStr).format('YYYY/MM/DD');
            return this.customPrices[dateStr] !== undefined
                || this.customPrices[keyAlt] !== undefined;
        },
        previousMonth() {
            if (this.canGoPrevious) {
                this.currentDate = moment(this.currentDate).subtract(1, 'jMonth');
            }
        },
        nextMonth() {
            if (this.canGoNext) {
                this.currentDate = moment(this.currentDate).add(1, 'jMonth');
            }
        },
        isSelectingCheckout() {
            return !!(this.startDate && !this.endDate && this.selectionMode === 'range');
        },
        isHostClosed(dateStr) {
            return this.isDateInDisabledList(dateStr, this.hostClosedDates);
        },
        isDisabledForCheckIn(dateStr, gregorianDate) {
            if (this.minDate) {
                const minMoment = moment(this.minDate);
                if (moment(dateStr).isBefore(minMoment, 'day')) {
                    return true;
                }
            }

            if (this.maxDate) {
                const maxMoment = moment(this.maxDate);
                if (moment(dateStr).isAfter(maxMoment, 'day')) {
                    return true;
                }
            }

            return this.isDateInDisabledList(dateStr, this.disableDates);
        },
        hasBlockedStayNightsBetween(startDateStr, endDateStr) {
            const start = this.parseCalendarDate(startDateStr);
            const end = this.parseCalendarDate(endDateStr);

            if (!start || !end || !start.isValid() || !end.isValid()) {
                return true;
            }

            let current = start.clone().startOf('day');

            while (current.isBefore(end, 'day')) {
                if (this.isDateInDisabledList(current.format('YYYY/MM/DD'), this.disableDates)) {
                    return true;
                }
                current.add(1, 'day');
            }

            return false;
        },
        canSelectAsCheckout(dateStr) {
            if (!this.startDate || this.endDate) {
                return false;
            }

            const selected = this.parseCalendarDate(dateStr);
            const start = this.parseCalendarDate(this.startDate);

            if (!selected || !start || !selected.isValid() || !start.isValid()) {
                return false;
            }

            if (selected.isSameOrBefore(start, 'day')) {
                return false;
            }

            if (this.minEndDate) {
                const minCheckout = this.parseCalendarDate(this.minEndDate);
                if (minCheckout && minCheckout.isValid() && selected.isBefore(minCheckout, 'day')) {
                    return false;
                }
            }

            // روز خروج شب اقامت محسوب نمی‌شود؛ بسته بودن میزبان فقط ورود/شب‌ها را مسدود می‌کند.
            return !this.hasBlockedStayNightsBetween(this.startDate, dateStr);
        },
        resolveDayState(dateStr, gregorianDate) {
            const disabledForCheckIn = this.isDisabledForCheckIn(dateStr, gregorianDate);
            const isCheckIn = this.isSameCalendarDay(dateStr, this.startDate);

            if (this.isSelectingCheckout()) {
                const validCheckout = this.canSelectAsCheckout(dateStr);
                const start = this.parseCalendarDate(this.startDate);
                const date = this.parseCalendarDate(dateStr);
                const afterStart = start && date && start.isValid() && date.isValid()
                    ? date.isAfter(start, 'day')
                    : false;

                return {
                    disabled: !validCheckout && !isCheckIn,
                    checkoutBlocked: afterStart && !validCheckout && !isCheckIn,
                    checkoutAvailable: validCheckout && disabledForCheckIn,
                    available: validCheckout,
                };
            }

            return {
                disabled: disabledForCheckIn,
                checkoutBlocked: false,
                checkoutAvailable: false,
                available: !disabledForCheckIn,
            };
        },
        isSelected(dateStr) {
            if (this.selectionMode === 'multiple') {
                return Array.isArray(this.selectedDates) && this.selectedDates.includes(dateStr);
            }
            return this.isSameCalendarDay(dateStr, this.startDate)
                || this.isSameCalendarDay(dateStr, this.endDate);
        },
        isInRange(dateStr) {
            if (!this.startDate || !this.endDate) return false;
            const date = this.parseCalendarDate(dateStr);
            const start = this.parseCalendarDate(this.startDate);
            const end = this.parseCalendarDate(this.endDate);

            if (!date || !start || !end) {
                return false;
            }

            return date.isAfter(start, 'day') && date.isBefore(end, 'day');
        },
        appliesLastMinuteOff(gregorianDate) {
            const target = moment(gregorianDate).startOf('day');
            if (!target.isSame(moment(), 'day')) {
                return false;
            }
            const key = target.format('YYYY/MM/DD');
            return !(this.disableDates && this.disableDates.includes(key));
        },
        getDatePrice(gregorianDate) {
            const dateKey = moment(gregorianDate).format('YYYY-MM-DD');
            const dateKeyAlt = moment(gregorianDate).format('YYYY/MM/DD');
            let price;

            if (this.customPrices) {
                const customPrice = this.customPrices[dateKey] !== undefined
                    ? this.customPrices[dateKey]
                    : this.customPrices[dateKeyAlt];
                if (customPrice !== undefined && customPrice !== null && customPrice !== '') {
                    price = parseInt(customPrice, 10);
                }
            }

            if (price === undefined) {
                const dayOfWeek = gregorianDate.getDay();
                const dateFormatted = moment(gregorianDate).format('YYYY/MM/DD');
                const isHoliday = this.holidays && Array.isArray(this.holidays) &&
                    this.holidays.some(h => moment(h).format('YYYY/MM/DD') === dateFormatted);

                const tomorrow = new Date(gregorianDate);
                tomorrow.setDate(tomorrow.getDate() + 1);
                const tomorrowFormatted = moment(tomorrow).format('YYYY/MM/DD');
                const isTomorrowHoliday = this.holidays && Array.isArray(this.holidays) &&
                    this.holidays.some(h => moment(h).format('YYYY/MM/DD') === tomorrowFormatted);

                price = parseInt(this.weekPrice, 10);

                if (isTomorrowHoliday || dayOfWeek === 4) {
                    price = parseInt(this.thuPrice, 10);
                } else if (isHoliday || dayOfWeek === 5) {
                    price = parseInt(this.friPrice, 10);
                } else if (dayOfWeek === 3) {
                    price = parseInt(this.wedPrice, 10);
                }
            }

            if (parseInt(this.off, 10) !== 0 && this.appliesLastMinuteOff(gregorianDate)) {
                price = price - (this.off * price / 100);
            }

            return price;
        },
        formatPrice(price) {
            if (!price) return '';
            let displayPrice = parseInt(price, 10);
            if (displayPrice > 999) {
                displayPrice = Math.round(displayPrice / 1000);
            }
            const formatted = this.$root.formatNumber
                ? this.$root.formatNumber(displayPrice)
                : displayPrice.toLocaleString('en-US');
            return this.toPersianNum(formatted);
        },
        selectDate(day) {
            if (day.empty) {
                return;
            }

            if (this.isSelectingCheckout()) {
                if (!this.canSelectAsCheckout(day.date)) {
                    return;
                }

                const startMoment = moment(this.startDate);
                const selectedMoment = moment(day.date);

                if (selectedMoment.isBefore(startMoment, 'day') || selectedMoment.isSame(startMoment, 'day')) {
                    this.$emit('date-selected', {
                        type: 'start',
                        date: day.date
                    });
                } else {
                    this.$emit('date-selected', {
                        type: 'end',
                        date: day.date
                    });
                }
                return;
            }

            if (day.disabled || !day.available) {
                return;
            }

            if (this.selectionMode === 'multiple') {
                const next = Array.isArray(this.selectedDates) ? [...this.selectedDates] : [];
                const index = next.indexOf(day.date);
                if (index >= 0) {
                    next.splice(index, 1);
                } else {
                    next.push(day.date);
                }
                next.sort();
                this.$emit('dates-changed', next);
                return;
            }

            if (!this.startDate || (this.startDate && this.endDate)) {
                // Start new selection
                this.$emit('date-selected', {
                    type: 'start',
                    date: day.date
                });
            } else if (this.startDate && !this.endDate) {
                // Select end date
                const startMoment = moment(this.startDate);
                const selectedMoment = moment(day.date);

                if (selectedMoment.isBefore(startMoment, 'day') || selectedMoment.isSame(startMoment, 'day')) {
                    // If selected date is before or same as start, make it the new start
                    this.$emit('date-selected', {
                        type: 'start',
                        date: day.date
                    });
                } else {
                    // Check if all dates in range are available
                    let allAvailable = true;
                    const datesInRange = [];
                    let current = moment(startMoment).add(1, 'day');
                    
                    while (current.isBefore(selectedMoment, 'day')) {
                        const dateStr = current.format('YYYY-MM-DD');
                        datesInRange.push(dateStr);
                        
                        if (this.isDisabledForCheckIn(dateStr, current.toDate())) {
                            allAvailable = false;
                            break;
                        }
                        current.add(1, 'day');
                    }

                    if (allAvailable) {
                        this.$emit('date-selected', {
                            type: 'end',
                            date: day.date
                        });
                    } else {
                        // Invalid range, start new selection
                        this.$emit('date-selected', {
                            type: 'start',
                            date: day.date
                        });
                    }
                }
            }
        }
    },
    watch: {
        startDate() {
            // Reset to show start date month if needed
            if (this.startDate) {
                const startMoment = moment(this.startDate);
                if (!this.currentDate.isSame(startMoment, 'jMonth')) {
                    this.currentDate = moment(startMoment);
                }
            }
        }
    }
}
</script>

<style scoped>
.persian-calendar {
    background: #ffffff;
    border-radius: 12px !important;
    padding: 12px !important;
    direction: rtl;
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    position: relative;
    margin: 0;
    padding-right: 0;
    padding-left: 0;
}

.calendar-header {
    margin-bottom: 12px !important;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding-right: 0;
    padding-left: 0;
}

.calendar-title {
    font-size: 24px !important;
    font-weight: 700 !important;
    color: #d4af37;
    margin: 0 0 16px 0 !important;
    text-align: right;
}

.calendar-month-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px !important;
    margin-bottom: 12px !important;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

.nav-arrow {
    background: transparent;
    border: none;
    font-size: 24px !important;
    color: #d4af37;
    cursor: pointer;
    padding: 4px 12px !important;
    transition: color 0.2s;
    font-weight: bold !important;
}

.nav-arrow:hover:not(:disabled) {
    color: #f4d03f;
}

.nav-arrow:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.month-year {
    font-size: 20px !important;
    font-weight: 600 !important;
    color: #d4af37;
    min-width: 150px !important;
    text-align: center;
    flex-shrink: 0;
}

.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px !important;
    margin-bottom: 8px !important;
    padding-bottom: 8px !important;
    border-bottom: 1px solid #e0e0e0;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    margin-left: 0;
    margin-right: 0;
    overflow: hidden;
}

.weekday {
    text-align: center;
    font-size: 12px !important;
    font-weight: 500 !important;
    color: #666666;
    padding: 6px 0 !important;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
    min-width: 0;
}

.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px !important;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    overflow: hidden;
}

.calendar-day {
    min-height: 0 !important;
    max-height: none !important;
    height: 0 !important;
    padding-bottom: calc(112% - 2px) !important;
    position: relative !important;
    aspect-ratio: unset !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer;
    border-radius: 6px !important;
    transition: all 0.2s;
    width: 100% !important;
    max-width: 100% !important;
    box-sizing: border-box !important;
    overflow: hidden !important;
    padding-top: 0 !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    min-width: 0 !important;
    flex-shrink: 0 !important;
    margin: 0 !important;
}

.calendar-day.empty {
    cursor: default;
}

.calendar-day.disabled {
    cursor: not-allowed;
    opacity: 0.3;
}

.calendar-day.disabled .day-number {
    color: #666666;
}

.calendar-day.available {
    border: 1px dashed #d0d0d0;
    background: #ffffff;
    box-sizing: border-box;
}

.calendar-day.available:hover {
    border-color: #d4af37;
    background: #fff5f0;
}

.calendar-day.selected {
    background: #d4af37;
    border: 1px solid #d4af37;
    color: #ffffff;
}

.calendar-day.selected .day-number {
    color: #ffffff;
    font-weight: 700;
}

.calendar-day.in-range {
    background: #fff5f0;
    border: 1px dashed #d4af37;
}

.calendar-day.check-in,
.calendar-day.check-out {
    background: #d4af37;
    border: 1px solid #d4af37;
    color: #ffffff;
}

.calendar-day.check-in .day-number,
.calendar-day.check-out .day-number {
    color: #ffffff;
    font-weight: 700;
}

.day-content {
    width: 100% !important;
    height: 100% !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 4px !important;
    box-sizing: border-box !important;
    margin: 0 !important;
}

.day-number {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #333333;
    margin-bottom: 4px !important;
    line-height: 1.3 !important;
}

.available-indicator {
    position: absolute;
    top: 2px !important;
    left: 2px !important;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 6px 6px 0 !important;
    border-color: transparent #d4af37 transparent transparent;
    border-radius: 0 0 0 2px !important;
}

.day-price {
    font-size: 11px !important;
    color: #666666 !important;
    margin-top: 0 !important;
    font-weight: 500 !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-word !important;
    overflow: visible !important;
    max-width: 100% !important;
    line-height: 1.3 !important;
    display: block !important;
    text-align: center !important;
}


.calendar-day.selected .day-price,
.calendar-day.check-in .day-price,
.calendar-day.check-out .day-price {
    color: rgba(255, 255, 255, 0.9);
}

/* Responsive */
@media (max-width: 767px) {
    .persian-calendar {
        padding: 8px !important;
        border-radius: 8px !important;
        margin: 0 !important;
        width: 100vw !important;
        max-width: 100vw !important;
        overflow-x: hidden !important;
        overflow-y: visible;
        padding-right: 0 !important;
        padding-left: 0 !important;
        box-sizing: border-box !important;
        position: relative;
        left: 0;
        right: 0;
    }

    .calendar-header {
        margin-bottom: 8px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .calendar-title {
        font-size: 18px !important;
        margin-bottom: 12px !important;
        padding: 0 !important;
        padding-right: 0 !important;
        text-align: right;
        line-height: 1.3 !important;
        font-weight: 700 !important;
        color: #d4af37 !important;
    }

    .calendar-month-nav {
        gap: 12px !important;
        margin-bottom: 12px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 !important;
        padding-right: 0 !important;
        padding-left: 0 !important;
    }

    .month-year {
        font-size: 16px !important;
        min-width: 120px !important;
        flex-shrink: 1;
        line-height: 1.3 !important;
        font-weight: 600 !important;
        color: #d4af37 !important;
    }

    .nav-arrow {
        font-size: 20px !important;
        padding: 4px 8px !important;
        flex-shrink: 0;
        line-height: 1 !important;
        font-weight: bold !important;
        color: #d4af37 !important;
    }

    .nav-arrow:hover:not(:disabled) {
        color: #f4d03f !important;
    }

    .calendar-weekdays {
        gap: 3px !important;
        margin-bottom: 6px !important;
        padding-bottom: 6px !important;
        grid-template-columns: repeat(7, 1fr) !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        overflow: hidden !important;
    }

    .weekday {
        font-size: 10px !important;
        padding: 4px 0 !important;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box !important;
        line-height: 1.2 !important;
        font-weight: 500 !important;
    }

    .calendar-grid {
        gap: 3px !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        grid-template-columns: repeat(7, 1fr) !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        display: grid !important;
    }

    .calendar-day {
        min-height: 0 !important;
        max-height: none !important;
        height: 0 !important;
        padding-bottom: calc(112% - 2px) !important;
        position: relative !important;
        aspect-ratio: unset !important;
        max-width: 100% !important;
        min-width: 0 !important;
        border-radius: 4px !important;
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .calendar-day.available {
        border-width: 1px !important;
    }

    .day-content {
        padding: 2px !important;
        width: 100% !important;
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
    }

    .day-number {
        font-size: 14px !important;
        line-height: 1.3 !important;
        margin-bottom: 3px !important;
        text-align: center !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        width: 100% !important;
        max-width: 100% !important;
        flex-shrink: 0 !important;
        padding: 0 !important;
        font-weight: 600 !important;
        display: block !important;
        color: #333333 !important;
    }

    .day-price {
        display: block !important;
        font-size: 10px !important;
        color: #666666 !important;
        margin-top: 0 !important;
        font-weight: 500 !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        overflow: visible !important;
        max-width: 100% !important;
        line-height: 1.3 !important;
        text-align: center !important;
    }

    .calendar-day.available {
        border: 1px dashed #d0d0d0 !important;
        background: #ffffff !important;
    }

    .calendar-day.available:hover {
        border-color: #d4af37 !important;
        background: #fff5f0 !important;
    }

    .calendar-day.selected {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .calendar-day.in-range {
        background: #fff5f0 !important;
        border: 1px dashed #d4af37 !important;
    }

    .calendar-day.check-in,
    .calendar-day.check-out {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .available-indicator {
        top: 0 !important;
        left: 0 !important;
        border-width: 0 3px 3px 0 !important;
    }
}

@media (max-width: 480px) {
    .persian-calendar {
        padding: 6px !important;
        border-radius: 8px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .calendar-header {
        margin-bottom: 6px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 !important;
    }

    .calendar-title {
        font-size: 16px !important;
        margin-bottom: 10px !important;
        padding: 0 !important;
        line-height: 1.3 !important;
        font-weight: 700 !important;
        color: #d4af37 !important;
    }

    .calendar-month-nav {
        gap: 10px !important;
        margin-bottom: 10px !important;
        padding: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .month-year {
        font-size: 14px !important;
        min-width: 100px !important;
        flex-shrink: 1;
        line-height: 1.3 !important;
        font-weight: 600 !important;
        color: #d4af37 !important;
    }

    .nav-arrow {
        font-size: 18px !important;
        padding: 3px 6px !important;
        flex-shrink: 0;
        line-height: 1 !important;
        font-weight: bold !important;
        color: #d4af37 !important;
    }

    .nav-arrow:hover:not(:disabled) {
        color: #f4d03f !important;
    }

    .calendar-weekdays {
        gap: 2px !important;
        margin-bottom: 4px !important;
        padding-bottom: 4px !important;
        grid-template-columns: repeat(7, 1fr) !important;
        width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        border-bottom: 1px solid #e0e0e0 !important;
    }

    .weekday {
        font-size: 9px !important;
        padding: 3px 0 !important;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100% !important;
        line-height: 1.2 !important;
        font-weight: 500 !important;
        color: #666666 !important;
    }

    .calendar-grid {
        gap: 2px !important;
        grid-template-columns: repeat(7, 1fr) !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .calendar-day {
        min-height: 0 !important;
        max-height: none !important;
        height: 0 !important;
        padding-bottom: calc(112% - 1px) !important;
        position: relative !important;
        aspect-ratio: unset !important;
        border-radius: 3px !important;
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        min-width: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .calendar-day.available {
        border: 1px dashed #d0d0d0 !important;
        background: #ffffff !important;
        border-width: 1px !important;
    }

    .calendar-day.available:hover {
        border-color: #d4af37 !important;
        background: #fff5f0 !important;
    }

    .calendar-day.selected {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .calendar-day.in-range {
        background: #fff5f0 !important;
        border: 1px dashed #d4af37 !important;
    }

    .calendar-day.check-in,
    .calendar-day.check-out {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .day-content {
        padding: 4px !important;
        width: 100% !important;
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
    }

    .day-number {
        font-size: 13px !important;
        line-height: 1.3 !important;
        margin-bottom: 3px !important;
        width: 100% !important;
        text-align: center !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        max-width: 100% !important;
        flex-shrink: 0 !important;
        padding: 0 !important;
        font-weight: 600 !important;
        display: block !important;
        color: #333333 !important;
    }

    .day-price {
        display: block !important;
        font-size: 9px !important;
        color: #666666 !important;
        margin-top: 0 !important;
        font-weight: 500 !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        overflow: visible !important;
        max-width: 100% !important;
        line-height: 1.3 !important;
        text-align: center !important;
    }

    .available-indicator {
        border-width: 0 2px 2px 0 !important;
        top: 0 !important;
        left: 0 !important;
    }
}

@media (max-width: 360px) {
    .persian-calendar {
        padding: 4px !important;
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .calendar-header {
        margin-bottom: 4px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        padding: 0 !important;
    }

    .calendar-title {
        font-size: 14px !important;
        margin-bottom: 8px !important;
        padding: 0 !important;
        line-height: 1.3 !important;
        font-weight: 700 !important;
        color: #d4af37 !important;
    }

    .calendar-month-nav {
        gap: 8px !important;
        margin-bottom: 8px !important;
        padding: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .month-year {
        font-size: 12px !important;
        min-width: 90px !important;
        flex-shrink: 1;
        line-height: 1.3 !important;
        font-weight: 600 !important;
        color: #d4af37 !important;
    }

    .nav-arrow {
        font-size: 16px !important;
        padding: 2px 5px !important;
        flex-shrink: 0;
        line-height: 1 !important;
        font-weight: bold !important;
        color: #d4af37 !important;
    }

    .nav-arrow:hover:not(:disabled) {
        color: #f4d03f !important;
    }

    .calendar-weekdays {
        gap: 2px !important;
        margin-bottom: 3px !important;
        padding-bottom: 3px !important;
        width: 100% !important;
        box-sizing: border-box !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        border-bottom: 1px solid #e0e0e0 !important;
    }

    .weekday {
        font-size: 8px !important;
        padding: 2px 0 !important;
        text-align: center;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100% !important;
        line-height: 1.2 !important;
        font-weight: 500 !important;
        color: #666666 !important;
    }

    .calendar-grid {
        gap: 2px !important;
        width: 100% !important;
        box-sizing: border-box !important;
    }

    .calendar-day {
        min-height: 0 !important;
        max-height: none !important;
        height: 0 !important;
        padding-bottom: calc(112% - 1px) !important;
        position: relative !important;
        aspect-ratio: unset !important;
        border-radius: 3px !important;
        padding-top: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        min-width: 0 !important;
        width: 100% !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .calendar-day.available {
        border: 1px dashed #d0d0d0 !important;
        background: #ffffff !important;
        border-width: 1px !important;
    }

    .calendar-day.available:hover {
        border-color: #d4af37 !important;
        background: #fff5f0 !important;
    }

    .calendar-day.selected {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .calendar-day.in-range {
        background: #fff5f0 !important;
        border: 1px dashed #d4af37 !important;
    }

    .calendar-day.check-in,
    .calendar-day.check-out {
        background: #d4af37 !important;
        border: 1px solid #d4af37 !important;
    }

    .day-content {
        padding: 2px !important;
        width: 100% !important;
        height: 100% !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        box-sizing: border-box !important;
        overflow: hidden !important;
        margin: 0 !important;
    }

    .day-number {
        font-size: 9px !important;
        line-height: 1.3 !important;
        margin-bottom: 1px !important;
        width: 100% !important;
        text-align: center !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        max-width: 100% !important;
        flex-shrink: 0 !important;
        padding: 0 !important;
        font-weight: 500 !important;
        display: block !important;
        color: #333333 !important;
    }

    .day-price {
        display: block !important;
        font-size: 6px !important;
        color: #666666 !important;
        margin-top: 0 !important;
        font-weight: 400 !important;
        white-space: normal !important;
        word-wrap: break-word !important;
        word-break: break-word !important;
        overflow: visible !important;
        max-width: 100% !important;
        line-height: 1.1 !important;
        text-align: center !important;
    }

    .available-indicator {
        border-width: 0 2px 2px 0 !important;
        top: 0 !important;
        left: 0 !important;
    }
}

/* Stacked mobile months (Jabama-style) */
.persian-calendar--stacked {
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    padding: 0 !important;
    border-radius: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
}

.persian-calendar--stacked .calendar-range-header {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 12px;
    padding: 0 4px;
}

.persian-calendar--stacked .calendar-range-cell {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 10px 12px;
    text-align: center;
    background: #fff;
}

.persian-calendar--stacked .calendar-range-cell.is-active {
    border-color: #1a73e8;
    box-shadow: 0 0 0 1px #1a73e8;
}

.persian-calendar--stacked .calendar-range-cell.has-value .calendar-range-value {
    color: #111827;
    font-weight: 700;
}

.persian-calendar--stacked .calendar-range-label {
    display: block;
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 4px;
}

.persian-calendar--stacked .calendar-range-value {
    display: block;
    font-size: 14px;
    color: #9ca3af;
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    font-variant-numeric: tabular-nums;
}

.persian-calendar--stacked .calendar-scroll-months {
    max-height: min(52vh, 420px);
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0 2px 8px;
}

.persian-calendar--stacked .calendar-month-block {
    margin-bottom: 20px;
}

.persian-calendar--stacked .calendar-month-title {
    text-align: center;
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 10px;
}

.persian-calendar--stacked .calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 6px;
    border-bottom: none;
    padding-bottom: 0;
}

.persian-calendar--stacked .weekday {
    font-size: 11px;
    color: #6b7280;
    text-align: center;
    font-weight: 600;
}

.persian-calendar--stacked .calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.persian-calendar--stacked .calendar-day {
    aspect-ratio: 1;
    height: auto !important;
    min-height: 52px !important;
    max-height: none !important;
    padding-bottom: 0 !important;
    padding-top: 0 !important;
    border-radius: 10px;
    border: none;
    background: transparent;
    position: relative;
    overflow: visible !important;
    display: flex !important;
    align-items: stretch !important;
    justify-content: center !important;
}

.persian-calendar--stacked .calendar-day.empty {
    visibility: hidden;
    pointer-events: none;
}

.persian-calendar--stacked .calendar-day.disabled .day-number {
    color: #d1d5db;
}

.persian-calendar--stacked .calendar-day.checkout-blocked .day-content {
    background: #f3f4f6;
}

.persian-calendar--stacked .calendar-day.checkout-blocked .day-number,
.persian-calendar--stacked .calendar-day.checkout-blocked .day-price {
    color: #d1d5db;
}

.persian-calendar--stacked .calendar-day.checkout-blocked {
    cursor: not-allowed;
}

.persian-calendar--stacked .calendar-day.checkout-available .day-content {
    background: #f9fafb;
    box-shadow: inset 0 0 0 1px #1d4ed8;
    border-radius: 10px;
}

.persian-calendar--stacked .calendar-day.checkout-available .day-number,
.persian-calendar--stacked .calendar-day.checkout-available .day-price {
    color: #111827;
}

.persian-calendar--stacked .calendar-day.available .day-content {
    background: #f9fafb;
    border-radius: 10px;
}

.persian-calendar--stacked .calendar-day.is-friday:not(.disabled):not(.selected):not(.check-in):not(.check-out) .day-number {
    color: #dc2626;
}

.persian-calendar--stacked .calendar-day.in-range .day-content {
    background: #dbeafe;
    border-radius: 0;
}

.persian-calendar--stacked .calendar-day.check-in .day-content {
    background: #1d4ed8;
    border-radius: 10px 0 0 10px;
}

.persian-calendar--stacked .calendar-day.check-out .day-content {
    background: #1d4ed8;
    border-radius: 0 10px 10px 0;
}

.persian-calendar--stacked .calendar-day.check-in.check-out .day-content {
    border-radius: 10px;
}

.persian-calendar--stacked .calendar-day.check-in .day-number,
.persian-calendar--stacked .calendar-day.check-out .day-number,
.persian-calendar--stacked .calendar-day.check-in .day-price,
.persian-calendar--stacked .calendar-day.check-out .day-price {
    color: #fff !important;
}

.persian-calendar--stacked .calendar-day.in-range .day-number,
.persian-calendar--stacked .calendar-day.in-range .day-price {
    color: #1e40af;
}

.persian-calendar--stacked .day-content {
    position: relative !important;
    top: auto !important;
    right: auto !important;
    bottom: auto !important;
    left: auto !important;
    inset: auto !important;
    width: 100% !important;
    height: 100% !important;
    min-height: 48px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4px 2px;
    box-sizing: border-box;
    overflow: visible !important;
}

.persian-calendar--stacked .day-number {
    font-size: 13px;
    font-weight: 700;
    line-height: 1.2;
    color: #111827;
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    font-variant-numeric: tabular-nums;
}

.persian-calendar--stacked .day-price {
    font-size: 9px;
    line-height: 1.15;
    margin-top: 2px;
    color: #6b7280;
    font-weight: 500;
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
}

.persian-calendar--stacked .calendar-stacked-footer {
    display: flex;
    justify-content: center;
    padding-top: 8px;
    border-top: 1px solid #f3f4f6;
}

.persian-calendar--stacked .calendar-clear-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px dashed #d1d5db;
    background: #fff;
    color: #374151;
    border-radius: 999px;
    padding: 8px 16px;
    font-size: 13px;
    font-family: inherit;
    cursor: pointer;
}

@media (max-width: 767px) {
    .persian-calendar--stacked .calendar-day {
        height: auto !important;
        min-height: 48px !important;
        padding-bottom: 0 !important;
    }

    .persian-calendar--stacked .day-content {
        position: relative !important;
        min-height: 44px;
    }

    .persian-calendar--stacked .day-number {
        font-size: 12px !important;
        display: block !important;
        overflow: visible !important;
    }

    .persian-calendar--stacked .day-price {
        font-size: 8px !important;
        display: block !important;
        overflow: visible !important;
    }
}

@media (max-width: 360px) {
    .persian-calendar--stacked .calendar-day {
        height: auto !important;
        min-height: 44px !important;
        padding-bottom: 0 !important;
    }
}

.persian-calendar--stacked .calendar-day.selected-multiple .day-content {
    background: #fffbeb;
    box-shadow: inset 0 0 0 2px #D39D1A;
    border-radius: 10px;
}

.persian-calendar--stacked .calendar-day.selected-multiple .day-number,
.persian-calendar--stacked .calendar-day.selected-multiple .day-price {
    color: #92400e;
}

.persian-calendar--stacked .calendar-day.reserved:not(.disabled) .day-content {
    background: #fef2f2;
}

.persian-calendar--stacked .calendar-day.reserved:not(.disabled) .day-content::after {
    content: 'پر';
    position: absolute;
    top: 2px;
    left: 4px;
    font-size: 8px;
    font-weight: 700;
    color: #fff;
    background: #dc2626;
    border-radius: 3px;
    padding: 0 3px;
    line-height: 1.3;
}

.persian-calendar--stacked .calendar-day.custom-price:not(.reserved):not(.selected-multiple) .day-content {
    background: #f0fdf4;
}

.persian-calendar--stacked .day-min-nights-badge,
.day-min-nights-badge {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -58%);
    font-size: 8px;
    line-height: 1.2;
    padding: 1px 4px;
    border-radius: 4px;
    background: #e67e22;
    color: #fff;
    font-weight: 700;
    white-space: nowrap;
    pointer-events: none;
    z-index: 2;
    font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    font-variant-numeric: tabular-nums;
}

.persian-calendar--stacked .calendar-day.selected-multiple .day-min-nights-badge,
.persian-calendar--stacked .calendar-day.check-in .day-min-nights-badge,
.persian-calendar--stacked .calendar-day.check-out .day-min-nights-badge {
    background: #f59e0b;
    color: #fff;
}

.persian-calendar--stacked .calendar-day.disabled .day-min-nights-badge {
    opacity: 0.45;
}
</style>
