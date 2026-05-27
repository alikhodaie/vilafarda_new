<template>
    <div class="jabama-reserve-form">
        <!-- Persian Calendar - Hidden in sidebar/desktop -->
        <PersianCalendar
            v-if="!hide_calendar && !isSidebar"
            ref="calendar"
            id="main-calendar"
            :min-date="min_date"
            :max-date="max_date"
            :disable-dates="disable_dates"
            :custom-prices="custom_prices"
            :custom-min-nights="custom_min_nights"
            :holidays="holidays"
            :week-price="week_price"
            :wed-price="wed_price"
            :thu-price="thu_price"
            :fri-price="fri_price"
            :off="off"
            :start-date="start_date"
            :end-date="end_date"
            :min-end-date="min_end_date"
            :max-end-date="max_end_date"
            :host-closed-dates="host_closed_dates"
            :stacked-months="useStackedCalendar"
            @date-selected="handleDateSelected"
            @dates-cleared="clearDates"
        ></PersianCalendar>

        <!-- Minimum Nights Info - Hidden in sidebar -->
        <div v-if="!isSidebar && start_date && !end_date" class="info-box min-nights-box">
            <span class="info-text">حداقل شب رزرو: {{ minNightsLabel }}</span>
        </div>

        <!-- Fast Reserve Banner - Hidden in sidebar -->
        <div v-if="!isSidebar && hasFastReserveDates" class="info-box fast-reserve-banner">
            <span class="banner-icon">▲</span>
            <span class="banner-text">در این روزها «آنی و قطعی» رزرو کنید</span>
        </div>

        <!-- Guest Counter - Only show in sidebar -->
        <div v-if="isSidebar" class="guest-section sidebar-guest">
            <label class="section-label">{{ count_guest_text }}</label>
            <div class="guest-counter">
                <button 
                    type="button" 
                    class="counter-button minus" 
                    @click="decreaseGuest"
                    :disabled="guest <= 1">
                    <i class="bi bi-dash"></i>
                </button>
                <input 
                    type="number" 
                    v-model.number="guest" 
                    class="guest-input"
                    :min="1"
                    :max="parseInt(max_guest) + parseInt(max_extra_guest)"
                    readonly>
                <button 
                    type="button" 
                    class="counter-button plus" 
                    @click="increaseGuest"
                    :disabled="guest >= parseInt(max_guest) + parseInt(max_extra_guest)">
                    <i class="bi bi-plus"></i>
                </button>
            </div>
        </div>

        <!-- Dates Summary - Only show in sidebar -->
        <div v-if="isSidebar" class="dates-summary sidebar-summary">
            <div v-if="start_date && end_date">
                <div class="summary-item">
                    <span class="summary-label">تاریخ ورود</span>
                    <span class="summary-value">{{ formatPersianDateShort(start_date) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تاریخ خروج</span>
                    <span class="summary-value">{{ formatPersianDateShort(end_date) }}</span>
                </div>
                <div class="summary-item nights">
                    <span class="summary-label">تعداد شب</span>
                    <span class="summary-value">{{ nightsCount }}</span>
                </div>
            </div>
            <div v-else class="summary-empty">
                <span class="empty-text">لطفاً تاریخ را از تقویم انتخاب کنید</span>
            </div>
        </div>

        <!-- Price Display - Only show in sidebar -->
        <div v-if="isSidebar" class="sidebar-price-section">
            <div v-if="!start_date || !end_date" class="price-start">
                <span class="price-label">شروع از :</span>
                <span class="price-amount">{{ week_price | formatNumber }}</span>
                <span class="price-unit">تومان / هر شب</span>
            </div>
            <div v-else-if="total > 0" class="price-total">
                <span class="total-label">{{ total_payment_text }}</span>
                <span class="total-amount">{{ total | formatNumber }} تومان</span>
            </div>
        </div>

        <!-- Reserve Button for Sidebar - Always show -->
        <div v-if="isSidebar" class="sidebar-reserve-section">
            <button 
                type="button" 
                class="sidebar-reserve-btn"
                @click.prevent.stop="handleReserveClick"
                :disabled="!start_date || !end_date">
                {{ submit_reserve_text }}
            </button>
        </div>

        <!-- Warning Messages -->
        <div v-if="range_warning" class="alert-box alert-danger">
            {{ range_warning }}
        </div>

        <div v-if="start_date_text || end_date_text" class="alert-box alert-warning">
            <p v-if="start_date_text">{{ start_date_text }}</p>
            <p v-if="end_date_text">{{ end_date_text }}</p>
        </div>

        <!-- Hidden Form for Submission -->
        <form method="POST" :action="route" ref="reserveForm" style="display: none;">
            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="start_date" :value="start_date">
            <input type="hidden" name="end_date" :value="end_date">
            <input type="hidden" name="guests" :value="guest">
        </form>

        <transition name="reserve-sheet-fade">
            <div v-if="useMobileSheet && sheetOpen" class="reserve-sheet-root">
                <div class="reserve-sheet-backdrop" @click="closeReserveSheet"></div>

                <button
                    type="button"
                    class="reserve-sheet-close"
                    aria-label="بستن"
                    @click="closeReserveSheet">
                    <i class="bi bi-x-lg"></i>
                </button>

                <div class="reserve-sheet-panel" role="dialog" aria-modal="true" @click.stop>
                    <div v-show="sheetStep === 'form'" class="reserve-sheet-body">
                        <div class="reserve-sheet-field">
                            <label class="reserve-sheet-label">تاریخ سفر</label>
                            <div class="reserve-sheet-date-split">
                                <button
                                    type="button"
                                    class="reserve-sheet-date-cell"
                                    :class="{ 'is-active': sheetDateFocus === 'start', 'has-value': !!start_date }"
                                    @click="openSheetCalendar('start')">
                                    <span class="reserve-sheet-date-text" :class="{ 'is-placeholder': !start_date }">
                                        {{ startDateFieldLabel }}
                                    </span>
                                </button>
                                <span class="reserve-sheet-date-divider" aria-hidden="true"></span>
                                <button
                                    type="button"
                                    class="reserve-sheet-date-cell"
                                    :class="{ 'is-active': sheetDateFocus === 'end', 'has-value': !!end_date }"
                                    @click="openSheetCalendar('end')">
                                    <span class="reserve-sheet-date-text" :class="{ 'is-placeholder': !end_date }">
                                        {{ endDateFieldLabel }}
                                    </span>
                                </button>
                            </div>
                        </div>

                        <div class="reserve-sheet-field">
                            <label class="reserve-sheet-label" for="reserve-sheet-guest">تعداد نفرات</label>
                            <div class="reserve-sheet-select-wrap">
                                <select
                                    id="reserve-sheet-guest"
                                    v-model.number="guest"
                                    class="reserve-sheet-select">
                                    <option
                                        v-for="count in guestSelectOptions"
                                        :key="count"
                                        :value="count">
                                        {{ count }} نفر
                                    </option>
                                </select>
                                <i class="bi bi-chevron-down reserve-sheet-select-icon" aria-hidden="true"></i>
                            </div>
                            <p v-if="parseInt(max_extra_guest) > 0" class="reserve-sheet-hint">
                                تا {{ persianDigit(max_extra_guest) }} نفر اضافه با هزینه جداگانه قابل پذیرش است.
                            </p>
                        </div>

                        <div v-if="range_warning" class="alert-box alert-danger reserve-sheet-alert">
                            {{ range_warning }}
                        </div>

                        <button
                            type="button"
                            class="reserve-sheet-submit"
                            @click="submitFromSheet">
                            ثبت درخواست رزرو (رایگان)
                        </button>
                    </div>

                    <div v-show="sheetStep === 'calendar'" class="reserve-sheet-calendar">
                        <div class="reserve-sheet-calendar-header">
                            <button type="button" class="reserve-sheet-calendar-back" @click="sheetStep = 'form'">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            <span class="reserve-sheet-calendar-title">انتخاب تاریخ</span>
                        </div>
                        <div v-if="start_date && !end_date" class="info-box min-nights-box reserve-sheet-min-nights">
                            <span class="info-text">حداقل شب رزرو: {{ minNightsLabel }}</span>
                        </div>
                        <PersianCalendar
                            ref="sheetCalendar"
                            class="reserve-sheet-persian-calendar"
                            :min-date="min_date"
                            :max-date="max_date"
                            :disable-dates="disable_dates"
                            :custom-prices="custom_prices"
                            :custom-min-nights="custom_min_nights"
                            :holidays="holidays"
                            :week-price="week_price"
                            :wed-price="wed_price"
                            :thu-price="thu_price"
                            :fri-price="fri_price"
                            :off="off"
                            :start-date="start_date"
                            :end-date="end_date"
                            :min-end-date="min_end_date"
                            :max-end-date="max_end_date"
                            :host-closed-dates="host_closed_dates"
                            :stacked-months="useStackedCalendar"
                            @date-selected="handleDateSelected"
                            @dates-cleared="clearDates"
                        ></PersianCalendar>
                        <button
                            type="button"
                            class="reserve-sheet-submit reserve-sheet-submit--calendar"
                            :disabled="!start_date || !end_date"
                            @click="sheetStep = 'form'">
                            تایید تاریخ
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import moment from "moment";

export default {
    name: "ReserveHome",
    props: [
        'date_text_start', 
        'date_text_end', 
        'count_guest_text', 
        'submit_reserve_text', 
        'off', 
        'daily_off',
        'total_payment_text', 
        'week_price', 
        'wed_price', 
        'thu_price', 
        'fri_price', 
        'price_per_surplus',
        'min_date', 
        'max_date', 
        'disable_dates_prop',
        'order_blocked_dates_prop',
        'host_closed_dates_prop',
        'max_guest', 
        'max_extra_guest', 
        'price_text',
        'price_per_surplus_text', 
        'csrf', 
        'route', 
        'prop_holidays', 
        'inline', 
        'custom_prices_prop',
        'custom_min_nights_prop',
        'text_start_date', 
        'text_end_date', 
        'daily_off_percent', 
        'fast_reserve_dates',
        'hide_calendar',
        'mobile_bottom_sheet',
        'stacked_calendar'
    ],
    data() {
        return {
            sheetOpen: false,
            sheetStep: 'form',
            sheetDateFocus: 'start',
            start_date_text: '',
            end_date_text: '',
            range_warning: '',
            dates: [],
            guest: 2,
            extra_guest: 0,
            extra_guest_price: 0,
            daily_off_price: 0,
            total: 0,
            min_end_date: this.min_date,
            max_end_date: this.max_date,
            holidays: [],
            custom_prices: [],
            custom_min_nights: {},
            required_min_nights: 1,
            disable_dates: [],
            order_blocked_dates: [],
            host_closed_dates: [],
            disable_end_dates: [],
            start_date: '',
            end_date: '',
            dateRange: [],
            isSidebar: false,
            isInternalUpdate: false // Flag to prevent infinite loops
        }
    },
    computed: {
        nightsCount() {
            return this.dates.length || 0;
        },
        canReserve() {
            return this.start_date && this.end_date && this.total > 0;
        },
        hasFastReserveDates() {
            return this.fast_reserve_dates && Array.isArray(this.fast_reserve_dates) && this.fast_reserve_dates.length > 0;
        },
        useMobileSheet() {
            return this.mobile_bottom_sheet && !this.isSidebar;
        },
        useStackedCalendar() {
            return this.stacked_calendar === true || this.stacked_calendar === 'true' || this.stacked_calendar === '1';
        },
        maxGuestsTotal() {
            return parseInt(this.max_guest, 10) + parseInt(this.max_extra_guest, 10);
        },
        guestSelectOptions() {
            const options = [];
            const max = this.maxGuestsTotal;
            for (let i = 1; i <= max; i++) {
                options.push(i);
            }
            return options;
        },
        startDateFieldLabel() {
            return this.start_date ? this.formatPersianDateShort(this.start_date) : this.date_text_start;
        },
        endDateFieldLabel() {
            return this.end_date ? this.formatPersianDateShort(this.end_date) : this.date_text_end;
        },
        minNightsLabel() {
            const count = this.start_date ? this.required_min_nights : 1;
            return `${this.toPersianNum(count)} شب`;
        },
    },
    created() {
        this.initializeDates();
        this.initializeCustomPrices();
        this.initializeCustomMinNights();
        this.initializeHolidays();
        this.checkIfSidebar();
        this.setupEventListeners();
    },
    mounted() {
        this.setupReserveButton();
        this.setupSidebarReserveButton();
    },
    beforeDestroy() {
        this.$root.$off('reserve-dates-updated', this.handleDatesUpdated);
        this.$root.$off('reserve-guest-updated', this.handleGuestUpdated);
        document.body.classList.remove('reserve-sheet-open');
    },
    watch: {
        guest(newVal) {
            const guestNum = parseInt(newVal);
            this.extra_guest = guestNum > parseInt(this.max_guest) ? guestNum - parseInt(this.max_guest) : 0;
            // Sync guest count with other instances (only if not internal update)
            if (!this.isInternalUpdate) {
                this.$root.$emit('reserve-guest-updated', {
                    guest: this.guest
                });
            }
        },
        extra_guest() {
            this.calcTotal();
        },
        dateRange(newVal) {
            if (Array.isArray(newVal) && newVal.length > 0) {
                this.start_date = newVal[0] || '';
                this.end_date = newVal[1] || '';
            } else {
                this.start_date = '';
                this.end_date = '';
            }
        },
        start_date(newVal) {
            this.updateDateRange();
            this.handleStartDateChange(newVal);
        },
        end_date(newVal) {
            this.updateDateRange();
            this.handleEndDateChange(newVal);
        }
    },
    methods: {
        setupReserveButton() {
            // Try multiple times to find the button (for mobile fixed button)
            const trySetup = (attempts = 0) => {
                const reserveBtn = document.getElementById('reserveBtn');
                if (reserveBtn) {
                    // Remove existing listener if any (to prevent duplicates)
                    const newBtn = reserveBtn.cloneNode(true);
                    reserveBtn.parentNode.replaceChild(newBtn, reserveBtn);
                    
                    // Add click event listener
                    newBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.handleReserveClick();
                    });
                } else if (attempts < 10) {
                    // Try again after a short delay
                    setTimeout(() => trySetup(attempts + 1), 200);
                }
            };
            
            this.$nextTick(() => {
                trySetup();
            });
        },
        setupSidebarReserveButton() {
            // Setup sidebar reserve button (for desktop)
            this.$nextTick(() => {
                const sidebarBtn = this.$el.querySelector('.sidebar-reserve-btn');
                if (sidebarBtn && !sidebarBtn.hasAttribute('data-listener-setup')) {
                    sidebarBtn.setAttribute('data-listener-setup', 'true');
                    
                    // Ensure click handler is attached (Vue @click should work, but this is a fallback)
                    sidebarBtn.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.handleReserveClick();
                    });
                }
            });
        },
        setupEventListeners() {
            // Listen for date updates from other instances
            this.$root.$on('reserve-dates-updated', this.handleDatesUpdated);
            // Listen for guest updates from other instances
            this.$root.$on('reserve-guest-updated', this.handleGuestUpdated);
        },
        handleGuestUpdated(data) {
            // Skip if this update was triggered by this instance
            if (this.isInternalUpdate) {
                return;
            }
            // Update guest count if different (avoid infinite loop)
            if (data.guest !== this.guest) {
                this.isInternalUpdate = true;
                this.guest = data.guest || 2;
                this.$nextTick(() => {
                    this.isInternalUpdate = false;
                });
            }
        },
        handleDatesUpdated(data) {
            // Skip if this update was triggered by this instance
            if (this.isInternalUpdate) {
                return;
            }
            
            // Update dates if they're different (avoid infinite loop)
            if (data.start_date !== this.start_date || data.end_date !== this.end_date) {
                this.isInternalUpdate = true;
                this.start_date = data.start_date || '';
                this.end_date = data.end_date || '';
                // Recalculate totals
                if (this.start_date && this.end_date) {
                    this.dates = this.$root.datePeriod(this.start_date, this.end_date);
                    this.calcTotal();
                } else {
                    this.dates = [];
                    this.total = 0;
                }
                this.$nextTick(() => {
                    this.isInternalUpdate = false;
                });
            }
        },
        handleReserveClick(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            if (this.useMobileSheet) {
                this.openReserveSheet();
                return;
            }
            
            if (!this.start_date || !this.end_date) {
                this.scrollToCalendar();
                if (this.$root.showAlert) {
                    this.$root.showAlert('لطفاً ابتدا تاریخ ورود و خروج را انتخاب کنید', 'warning', true);
                } else {
                    alert('لطفاً ابتدا تاریخ ورود و خروج را انتخاب کنید');
                }
                return;
            }

            this.submitReserve();
        },
        openReserveSheet() {
            this.sheetStep = 'form';
            this.sheetOpen = true;
            document.body.classList.add('reserve-sheet-open');
        },
        closeReserveSheet() {
            this.sheetOpen = false;
            this.sheetStep = 'form';
            document.body.classList.remove('reserve-sheet-open');
        },
        openSheetCalendar(focus) {
            this.sheetDateFocus = focus === 'end' ? 'end' : 'start';
            this.sheetStep = 'calendar';
        },
        submitFromSheet() {
            if (!this.start_date || !this.end_date) {
                this.openSheetCalendar('start');
                if (this.$root.showAlert) {
                    this.$root.showAlert('لطفاً تاریخ ورود و خروج را انتخاب کنید', 'warning', true);
                } else {
                    alert('لطفاً تاریخ ورود و خروج را انتخاب کنید');
                }
                return;
            }

            if (!this.guest || this.guest < 1) {
                if (this.$root.showAlert) {
                    this.$root.showAlert('لطفاً تعداد نفرات را مشخص کنید', 'warning', true);
                } else {
                    alert('لطفاً تعداد نفرات را مشخص کنید');
                }
                return;
            }

            this.closeReserveSheet();
            this.submitReserve();
        },
        persianDigit(value) {
            const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return String(value).replace(/\d/g, (digit) => digits[parseInt(digit, 10)]);
        },
        scrollToCalendar() {
            // Try to find calendar in the page
            const calendarElement = document.getElementById('main-calendar');
            if (calendarElement) {
                calendarElement.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                // Highlight the calendar briefly
                calendarElement.style.transition = 'box-shadow 0.3s ease';
                calendarElement.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.5)';
                setTimeout(() => {
                    calendarElement.style.boxShadow = '';
                }, 2000);
                return;
            }
            
            // Fallback: try to find calendar by class
            const calendarByClass = document.querySelector('.persian-calendar');
            if (calendarByClass) {
                calendarByClass.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                calendarByClass.style.transition = 'box-shadow 0.3s ease';
                calendarByClass.style.boxShadow = '0 0 20px rgba(255, 107, 53, 0.5)';
                setTimeout(() => {
                    calendarByClass.style.boxShadow = '';
                }, 2000);
                return;
            }
            
            // If calendar not found, show alert
            this.$root.showAlert('لطفاً تاریخ را از تقویم انتخاب کنید', 'warning', true);
        },
        clearDates() {
            this.isInternalUpdate = true;
            this.start_date = '';
            this.end_date = '';
            this.dates = [];
            this.total = 0;
            this.sheetDateFocus = 'start';
            this.$nextTick(() => {
                this.$root.$emit('reserve-dates-updated', {
                    start_date: '',
                    end_date: ''
                });
                this.isInternalUpdate = false;
            });
        },
        handleDateSelected(event) {
            this.isInternalUpdate = true;
            if (event.type === 'start') {
                this.start_date = event.date;
                this.end_date = '';
                this.sheetDateFocus = 'end';
            } else if (event.type === 'end') {
                this.end_date = event.date;
            }
            this.$nextTick(() => {
                this.$root.$emit('reserve-dates-updated', {
                    start_date: this.start_date,
                    end_date: this.end_date
                });
                this.isInternalUpdate = false;

                if (this.sheetOpen && this.sheetStep === 'calendar' && this.start_date && this.end_date) {
                    this.sheetStep = 'form';
                }
            });
        },
        checkIfSidebar() {
            // Check if we're in a sidebar (desktop view) or hide_calendar prop is set
            if (this.hide_calendar) {
                this.isSidebar = true;
                return;
            }
            this.$nextTick(() => {
                const parent = this.$el.closest('.property-sidebar, .side-booking-body, .side_stiky');
                if (parent) {
                    this.isSidebar = true;
                } else {
                    // Also check by window width
                    if (window.innerWidth >= 768) {
                        const container = this.$el.closest('.col-lg-4, .col-md-4');
                        if (container) {
                            this.isSidebar = true;
                        }
                    }
                }
            });
        },
        openCalendarModal() {
            // Emit event to open calendar modal or show calendar
            this.$root.$emit('open-calendar-modal', {
                minDate: this.min_date,
                maxDate: this.max_date,
                disableDates: this.disable_dates,
                customPrices: this.custom_prices,
                holidays: this.holidays,
                weekPrice: this.week_price,
                wedPrice: this.wed_price,
                thuPrice: this.thu_price,
                friPrice: this.fri_price,
                off: this.off,
                startDate: this.start_date,
                endDate: this.end_date
            });
            // For now, show alert to select dates
            this.$root.showAlert('لطفاً از صفحه اصلی تاریخ را انتخاب کنید', 'info', true);
        },
        submitReserve() {
            // Validate dates and guest count
            if (!this.start_date || !this.end_date) {
                this.$root.showAlert && this.$root.showAlert('لطفاً تاریخ ورود و خروج را انتخاب کنید', 'warning', true);
                this.scrollToCalendar();
                return;
            }
            
            if (!this.guest || this.guest < 1) {
                this.$root.showAlert && this.$root.showAlert('لطفاً تعداد مهمان را مشخص کنید', 'warning', true);
                return;
            }

            // Submit form
            if (this.$refs.reserveForm) {
                this.$refs.reserveForm.submit();
            } else {
                // Fallback: create and submit form manually
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.route;
                
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = this.csrf;
                form.appendChild(csrfInput);
                
                const startDateInput = document.createElement('input');
                startDateInput.type = 'hidden';
                startDateInput.name = 'start_date';
                startDateInput.value = this.start_date;
                form.appendChild(startDateInput);
                
                const endDateInput = document.createElement('input');
                endDateInput.type = 'hidden';
                endDateInput.name = 'end_date';
                endDateInput.value = this.end_date;
                form.appendChild(endDateInput);
                
                const guestInput = document.createElement('input');
                guestInput.type = 'hidden';
                guestInput.name = 'guests';
                guestInput.value = this.guest;
                form.appendChild(guestInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        },
        initializeDates() {
            this.disable_dates = [...(this.disable_dates_prop || [])];
            this.order_blocked_dates = [...(this.order_blocked_dates_prop || [])];
            this.host_closed_dates = [...(this.host_closed_dates_prop || [])];
            this.disable_end_dates = [...this.disable_dates];
        },
        initializeCustomPrices() {
            const prices = {};
            $.each(this.custom_prices_prop, (index, price) => {
                // Convert date to YYYY-MM-DD format
                const dateKey = moment(index).format('YYYY-MM-DD');
                prices[dateKey] = price;
            });
            this.custom_prices = prices;
        },
        initializeCustomMinNights() {
            const minNights = {};
            if (!this.custom_min_nights_prop || typeof this.custom_min_nights_prop !== 'object') {
                this.custom_min_nights = minNights;
                return;
            }

            Object.keys(this.custom_min_nights_prop).forEach((key) => {
                const value = Math.max(1, parseInt(this.custom_min_nights_prop[key], 10) || 1);
                if (/^\d{1,2}$/.test(String(key))) {
                    return;
                }

                const parsed = moment(key, ['YYYY-MM-DD', 'YYYY/MM/DD'], true);

                if (!parsed.isValid()) {
                    return;
                }

                minNights[parsed.format('YYYY-MM-DD')] = value;
                minNights[parsed.format('YYYY/MM/DD')] = value;
            });
            this.custom_min_nights = minNights;
        },
        getMinNightsForDate(dateValue) {
            return this.getEffectiveMinNightsForDate(this.custom_min_nights, dateValue, {
                maxDate: this.max_date,
                disableDates: this.disable_dates,
                reservedDates: this.host_closed_dates,
                orderBlockedDates: this.order_blocked_dates,
                autoReduceMinNights: false,
            });
        },
        toPersianNum(num) {
            const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return String(num).replace(/\d/g, (d) => digits[parseInt(d, 10)]);
        },
        initializeHolidays() {
            const holidays = [];
            $.each(this.prop_holidays, (index, date) => {
                holidays.push(moment(date).format('YYYY/MM/DD'));
            });
            this.holidays = holidays.sort();
        },
        updateDateRange() {
            if (this.start_date && this.end_date) {
                this.dateRange = [this.start_date, this.end_date];
            } else if (this.start_date) {
                this.dateRange = [this.start_date];
            } else {
                this.dateRange = [];
            }
        },
        handleStartDateChange(startDate) {
            this.dates = [];
            this.calcTotal();
            this.start_date_text = '';
            this.end_date_text = '';
            this.disable_end_dates = [...this.disable_dates];
            this.max_end_date = this.max_date;
            this.min_end_date = this.min_date;

            if (!startDate) {
                return;
            }

            if (!this.end_date || new Date(this.end_date) <= new Date(startDate)) {
                this.end_date = '';
            }

            this.required_min_nights = this.getMinNightsForDate(startDate);
            this.min_end_date = moment(startDate, ['YYYY/MM/DD', 'YYYY-MM-DD'])
                .add(this.required_min_nights, 'days')
                .format('YYYY-MM-DD');

            const sortedDisabled = [...this.disable_dates].sort((a, b) => {
                return moment(a, ['YYYY/MM/DD', 'YYYY-MM-DD']).valueOf()
                    - moment(b, ['YYYY/MM/DD', 'YYYY-MM-DD']).valueOf();
            });

            const startMoment = moment(startDate, ['YYYY/MM/DD', 'YYYY-MM-DD']);
            const filteredDisabled = sortedDisabled.filter((disableDate) => {
                return moment(disableDate, ['YYYY/MM/DD', 'YYYY-MM-DD']).isAfter(startMoment, 'day');
            });

            if (filteredDisabled.length > 0) {
                const minDisabled = filteredDisabled.shift();
                this.disable_end_dates = filteredDisabled;
                this.max_end_date = moment(minDisabled, ['YYYY/MM/DD', 'YYYY-MM-DD']).format('YYYY-MM-DD');
            } else {
                this.max_end_date = moment(this.max_date, ['YYYY/MM/DD', 'YYYY-MM-DD']).format('YYYY-MM-DD');
            }
        },
        handleEndDateChange(endDate) {
            this.dates = [];
            this.calcTotal();
            this.start_date_text = '';
            this.end_date_text = '';
            this.range_warning = '';

            if (this.start_date && endDate) {
                const startDateFa = new Date(this.start_date).toLocaleDateString('fa-IR');
                const endDateFa = new Date(endDate).toLocaleDateString('fa-IR');

                this.start_date_text = this.text_start_date.replace(':date', startDateFa);
                this.end_date_text = this.text_end_date.replace(':date', endDateFa);

                this.dates = this.$root.datePeriod(this.start_date, endDate);
                const requiredNights = this.getMinNightsForDate(this.start_date);
                if (this.dates.length < requiredNights) {
                    this.range_warning = `حداقل ${this.toPersianNum(requiredNights)} شب برای رزرو از این تاریخ لازم است.`;
                    this.end_date = '';
                    this.dates = [];
                    this.total = 0;
                    return;
                }

                this.calcTotal();
                
                // Emit to sync with other instances when dates are complete
                if (!this.isInternalUpdate) {
                    this.$nextTick(() => {
                        this.$root.$emit('reserve-dates-updated', {
                            start_date: this.start_date,
                            end_date: this.end_date
                        });
                    });
                }
            }
        },
        decreaseGuest() {
            if (this.guest > 1) {
                this.guest--;
            }
        },
        increaseGuest() {
            const max = parseInt(this.max_guest) + parseInt(this.max_extra_guest);
            if (this.guest < max) {
                this.guest++;
            }
        },
        formatPersianDateShort(date) {
            const dateObj = new Date(date);
            return dateObj.toLocaleDateString('fa-IR', {
                day: '2-digit',
                month: 'long'
            });
        },
        normalize(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        calcExtraGuestPrice() {
            this.extra_guest_price = this.extra_guest * this.price_per_surplus * this.dates.length;
        },
        calcTotal() {
            this.calcExtraGuestPrice();
            this.daily_off_price = 0;

            let total = this.extra_guest_price;
            this.dates.forEach((date) => {
                total += parseInt(this.getPrice(date, false));
            });

            if (this.daily_off !== 0 && this.daily_off_percent !== 0 && this.dates.length > this.daily_off) {
                this.daily_off_price = (this.daily_off_percent * total) / 100;
                total -= this.daily_off_price;
            }

            this.total = total;
        },
        getPrice(date, formatted = true) {
            date.setHours(0, 0, 0, 0);
            const day = parseInt(date.getDay());
            const tomorrow = new Date(date);
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);

            let price = this.week_price;
            const dateKey = moment(date).format('YYYY-MM-DD');

            if (this.custom_prices[dateKey] !== undefined && this.custom_prices[dateKey] !== null && this.custom_prices[dateKey] !== '') {
                price = this.custom_prices[dateKey];
            } else if (this.$root.isHoliday(this.holidays, tomorrow) || day === 4) {
                price = this.thu_price;
            } else if (this.$root.isHoliday(this.holidays, date) || day === 5) {
                price = this.fri_price;
            } else if (day === 3) {
                price = this.wed_price;
            }

            if (parseInt(this.off) !== 0 && this.appliesLastMinuteOff(date)) {
                price = price - (this.off * price / 100);
            }

            return formatted ? this.$root.formatNumber(price) : price;
        },
        appliesLastMinuteOff(date) {
            const target = moment(date).startOf('day');
            if (!target.isSame(moment(), 'day')) {
                return false;
            }
            const key = target.format('YYYY/MM/DD');
            return !this.disable_dates.includes(key);
        },
        formatPriceShort(price) {
            if (price > 999) {
                price = price / 1000;
            }
            return this.$root.formatNumber(price);
        }
    }
}
</script>

<style scoped>
/* ============================================
   Base Styles
   ============================================ */
.jabama-reserve-form {
    width: 100%;
    max-width: 100%;
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Vazirmatn', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    direction: rtl;
    text-align: right;
    overflow-x: hidden;
    position: relative;
}

/* Reduce padding for sidebar */
.property-sidebar .jabama-reserve-form,
.side-booking-body .jabama-reserve-form {
    padding: 12px;
    border-radius: 8px;
}

/* ============================================
   Info Boxes
   ============================================ */
.info-box {
    width: 100%;
    padding: 12px 16px;
    border-radius: 8px;
    margin: 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    line-height: 1.6;
    word-spacing: 0.05em;
    letter-spacing: -0.01em;
}

.min-nights-box {
    background-color: #f5f5f5;
    color: #333333;
    text-align: center;
    justify-content: center;
}

.fast-reserve-banner {
    background: linear-gradient(135deg, #d4af37 0%, #f4d03f 100%);
    color: #1a1a1a;
    font-weight: 600;
}

.banner-icon {
    font-size: 12px;
    line-height: 1;
}

.banner-text {
    flex: 1;
}

/* ============================================
   Guest Section
   ============================================ */
.guest-section {
    margin: 12px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.guest-section.sidebar-guest {
    margin: 0 0 10px 0;
    padding: 8px;
}

.section-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 8px;
    line-height: 1.5;
    letter-spacing: -0.01em;
}

.guest-counter {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background-color: #ffffff;
    border-radius: 6px;
    padding: 6px;
}

.counter-button {
    width: 32px;
    height: 32px;
    border: 1px solid #e0e0e0;
    background-color: #ffffff;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
}

.counter-button:hover:not(:disabled) {
    background-color: #f5f5f5;
    border-color: #d4af37;
}

.counter-button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.counter-button i {
    font-size: 16px;
    color: #666666;
    font-weight: bold;
}

.guest-input {
    border: none;
    text-align: center;
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
    width: 50px;
    background: transparent;
    outline: none;
    -moz-appearance: textfield;
}

.guest-input::-webkit-inner-spin-button,
.guest-input::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* ============================================
   Dates Summary
   ============================================ */
.dates-summary {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin: 10px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.dates-summary.sidebar-summary {
    grid-template-columns: 1fr;
    gap: 6px;
    margin: 8px 0;
    padding: 8px;
}

.summary-empty {
    padding: 12px;
    text-align: center;
}

.empty-text {
    font-size: 13px;
    color: #999999;
    font-style: italic;
}

.summary-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 6px;
}

.summary-item.nights {
    background-color: #fff7e6;
    border: 1px solid #ffe0b2;
    border-radius: 6px;
    padding: 6px;
}

.summary-label {
    font-size: 11px;
    color: #666666;
    margin-bottom: 4px;
    line-height: 1.4;
    display: block;
    text-align: center;
}

.summary-value {
    font-size: 13px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.4;
    display: block;
    text-align: center;
}

/* ============================================
   Price Section
   ============================================ */
.price-section {
    margin: 20px 0;
    padding: 16px;
    background-color: #ffffff;
    border-radius: 12px;
}

.price-start {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
}

.price-label {
    font-size: 13px;
    color: #666666;
    line-height: 1.5;
    display: inline-block;
}

.price-amount {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.3;
    display: inline-block;
    letter-spacing: -0.02em;
}

.price-unit {
    font-size: 13px;
    color: #666666;
    line-height: 1.5;
    display: inline-block;
}

.price-total {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.total-label {
    font-size: 12px;
    color: #666666;
    line-height: 1.5;
    display: block;
    text-align: center;
}

.total-amount {
    font-size: 22px;
    font-weight: 700;
    color: #d4af37;
    line-height: 1.3;
    display: block;
    text-align: center;
    letter-spacing: -0.02em;
}

/* ============================================
   Alert Boxes
   ============================================ */
.alert-box {
    width: 100%;
    padding: 12px 16px;
    border-radius: 8px;
    margin: 12px 0;
    text-align: center;
    font-size: 13px;
    line-height: 1.6;
    word-spacing: 0.05em;
    letter-spacing: -0.01em;
}

.alert-danger {
    background-color: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.alert-warning {
    background-color: #fff8e1;
    color: #e65100;
    border: 1px solid #ffe0b2;
}

.alert-box p {
    margin: 4px 0;
    color: inherit;
}

.alert-box p:first-child {
    margin-top: 0;
}

.alert-box p:last-child {
    margin-bottom: 0;
}

/* ============================================
   Responsive Styles - Mobile
   ============================================ */
@media (max-width: 767px) {
    .jabama-reserve-form {
        padding: 16px;
        border-radius: 12px;
    }

    .info-box {
        padding: 10px 12px;
        font-size: 12px;
        margin: 10px 0;
    }

    .min-nights-box {
        padding: 8px 12px;
        font-size: 11px;
    }

    .fast-reserve-banner {
        padding: 10px 12px;
        font-size: 12px;
    }

    .guest-section {
        margin: 10px 0;
        padding: 8px;
    }
    
    .guest-section.sidebar-guest {
        margin: 0 0 8px 0;
        padding: 6px;
    }

    .section-label {
        font-size: 13px;
        margin-bottom: 6px;
    }

    .guest-counter {
        gap: 8px;
        padding: 4px;
    }

    .counter-button {
        width: 28px;
        height: 28px;
    }

    .counter-button i {
        font-size: 14px;
    }

    .guest-input {
        font-size: 14px;
        width: 40px;
    }

    .dates-summary {
        grid-template-columns: 1fr;
        gap: 6px;
        margin: 8px 0;
        padding: 8px;
    }
    
    .dates-summary.sidebar-summary {
        margin: 6px 0;
        padding: 6px;
    }

    .summary-item {
        padding: 10px;
        background-color: #ffffff;
        border-radius: 8px;
    }

    .summary-item.nights {
        background-color: #fff7e6;
    }

    .summary-label {
        font-size: 10px;
        margin-bottom: 4px;
    }

    .summary-value {
        font-size: 13px;
    }

    .price-section {
        margin: 16px 0;
        padding: 12px;
    }

    .price-start {
        gap: 4px;
    }

    .price-label,
    .price-unit {
        font-size: 12px;
    }

    .price-amount {
        font-size: 16px;
    }

    .total-label {
        font-size: 11px;
    }

    .total-amount {
        font-size: 20px;
    }

    .alert-box {
        padding: 10px 12px;
        font-size: 12px;
        margin: 10px 0;
    }
}

/* ============================================
   Responsive Styles - Small Mobile
   ============================================ */
@media (max-width: 480px) {
    .jabama-reserve-form {
        padding: 12px;
    }

    .info-box {
        padding: 8px 10px;
        font-size: 11px;
    }

    .fast-reserve-banner {
        font-size: 11px;
    }

    .price-amount {
        font-size: 15px;
    }

    .total-amount {
        font-size: 18px;
    }
}

/* ============================================
   Responsive Styles - Tablet
   ============================================ */
@media (min-width: 768px) and (max-width: 1024px) {
    .jabama-reserve-form {
        padding: 20px;
    }

    .dates-summary {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>

<style>
.reserve-calendar-main {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    margin: 20px 0;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    overflow: hidden;
    overflow-x: hidden;
}

.sidebar-date-selector {
    margin-bottom: 16px;
}

.sidebar-date-btn {
    width: 100%;
    padding: 12px 16px;
    background: #f8f9fa;
    color: #333333;
    border: 1px dashed #d0d0d0;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.sidebar-date-btn:hover {
    background: #fef9e7;
    border-color: #d4af37;
    color: #d4af37;
}

.sidebar-date-btn i {
    font-size: 16px;
}

.sidebar-price-section {
    margin: 10px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.sidebar-reserve-section {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #e0e0e0;
}

.dates-summary.sidebar-summary {
    margin-bottom: 10px;
}

.sidebar-reserve-btn {
    width: 100%;
    padding: 10px 16px;
    background: #B8860B !important;
    border-color: #B8860B !important;
    color: #ffffff !important;
    border: 2px solid #B8860B !important;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.sidebar-reserve-btn:hover:not(:disabled) {
    background: #996f0a !important;
    border-color: #996f0a !important;
    color: #ffffff !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(184, 134, 11, 0.4);
}

.sidebar-reserve-btn:active:not(:disabled) {
    transform: translateY(0);
}

.sidebar-reserve-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    background: #cccccc !important;
    border-color: #cccccc !important;
}

@media (max-width: 767px) {
    .reserve-calendar-main {
        padding: 0;
        margin: 0;
        border-radius: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }
}

@media (max-width: 480px) {
    .reserve-calendar-main {
        padding: 0;
        margin: 0;
        border-radius: 0;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        overflow-x: hidden;
    }
}

/* Mobile reserve bottom sheet */
.reserve-sheet-root {
    position: fixed;
    inset: 0;
    z-index: 1080;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    pointer-events: none;
}

.reserve-sheet-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    pointer-events: auto;
}

.reserve-sheet-close {
    position: absolute;
    top: calc(34vh - 52px);
    left: 16px;
    z-index: 1082;
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    color: #1a1a1a;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    pointer-events: auto;
}

.reserve-sheet-panel {
    position: relative;
    z-index: 1081;
    width: 100%;
    max-width: 100%;
    max-height: 66vh;
    background: #ffffff;
    border-radius: 20px 20px 0 0;
    padding: 20px 16px calc(20px + env(safe-area-inset-bottom, 0px));
    overflow-y: auto;
    pointer-events: auto;
    box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.18);
}

.reserve-sheet-body,
.reserve-sheet-calendar {
    direction: rtl;
}

.reserve-sheet-field {
    margin-bottom: 18px;
}

.reserve-sheet-label {
    display: block;
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 8px;
}

.reserve-sheet-date-split {
    display: flex;
    align-items: stretch;
    border: 1px solid #d9d9d9;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.reserve-sheet-date-cell {
    flex: 1;
    border: none;
    background: transparent;
    padding: 14px 12px;
    text-align: center;
    cursor: pointer;
}

.reserve-sheet-date-cell.is-active {
    background: #faf6ea;
}

.reserve-sheet-date-cell.has-value .reserve-sheet-date-text {
    color: #1a1a1a;
    font-weight: 600;
}

.reserve-sheet-date-divider {
    width: 1px;
    background: #e5e5e5;
    flex-shrink: 0;
}

.reserve-sheet-date-text {
    font-size: 14px;
    color: #1a1a1a;
}

.reserve-sheet-date-text.is-placeholder {
    color: #9a9a9a;
    font-weight: 400;
}

.reserve-sheet-select-wrap {
    position: relative;
}

.reserve-sheet-select {
    width: 100%;
    appearance: none;
    -webkit-appearance: none;
    border: 1px solid #d9d9d9;
    border-radius: 12px;
    padding: 14px 40px 14px 12px;
    font-size: 14px;
    color: #1a1a1a;
    background: #fff;
    direction: rtl;
}

.reserve-sheet-select-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
    pointer-events: none;
    font-size: 14px;
}

.reserve-sheet-hint {
    margin: 8px 0 0;
    font-size: 12px;
    color: #8a8a8a;
    line-height: 1.6;
}

.reserve-sheet-alert {
    margin-bottom: 12px;
}

.reserve-sheet-submit {
    width: 100%;
    border: none;
    border-radius: 999px;
    background: #f5c518;
    color: #1a1a1a;
    font-size: 15px;
    font-weight: 700;
    padding: 14px 16px;
    margin-top: 8px;
}

.reserve-sheet-submit:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}

.reserve-sheet-submit--calendar {
    margin-top: 12px;
}

.reserve-sheet-calendar-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}

.reserve-sheet-calendar-back {
    border: none;
    background: transparent;
    font-size: 20px;
    color: #1a1a1a;
    padding: 0;
    line-height: 1;
}

.reserve-sheet-calendar-title {
    font-size: 16px;
    font-weight: 700;
    color: #1a1a1a;
}

.reserve-sheet-min-nights {
    margin-top: 0;
    margin-bottom: 10px;
}

.reserve-sheet-persian-calendar .calendar-title {
    display: none;
}

.reserve-sheet-persian-calendar.persian-calendar {
    padding: 0;
}

.reserve-sheet-fade-enter-active,
.reserve-sheet-fade-leave-active {
    transition: opacity 0.25s ease;
}

.reserve-sheet-fade-enter-active .reserve-sheet-panel,
.reserve-sheet-fade-leave-active .reserve-sheet-panel {
    transition: transform 0.28s ease;
}

.reserve-sheet-fade-enter,
.reserve-sheet-fade-leave-to {
    opacity: 0;
}

.reserve-sheet-fade-enter .reserve-sheet-panel,
.reserve-sheet-fade-leave-to .reserve-sheet-panel {
    transform: translateY(100%);
}

body.reserve-sheet-open {
    overflow: hidden;
}
</style>
