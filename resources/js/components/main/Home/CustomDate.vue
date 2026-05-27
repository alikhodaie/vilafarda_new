<template>
    <div>
        <form method="POST" :action="route" ref="customDateForm" @submit="onFormSubmit">

            <div v-if="hasSelection && !useStackedCalendar" class="d-flex justify-content-center mb-2 mb-md-3">
                <button type="button" @click="clearSelection" class="btn btn-info mx-2 rounded">{{ text_remove_selected }}</button>
            </div>

            <input type="hidden" name="_token" :value="csrf">
            <input type="hidden" name="min_nights" :value="min_nights">

            <div class="d-flex justify-content-center home-custom-date-picker">
                <persian-calendar
                    v-if="useStackedCalendar"
                    :stacked-months="true"
                    selection-mode="multiple"
                    min-nights-display="configured"
                    :selected-dates="selectedDates"
                    :min-date="min_date"
                    :max-date="max_date"
                    :disable-dates="disable_dates"
                    :reserved-dates="reserved_dates"
                    :custom-prices="custom_prices_map"
                    :custom-min-nights="custom_min_nights"
                    :holidays="holidays"
                    :week-price="week_price"
                    :wed-price="wed_price"
                    :thu-price="thu_price"
                    :fri-price="fri_price"
                    @dates-changed="onDatesChanged"
                    @dates-cleared="clearSelection"
                />

                <date-picker
                    v-else
                    :placeholder="placeholder"
                    :highlight="highlight"
                    :multiple="true"
                    :inline="true"
                    input-class="vpd-hidden-input"
                    v-model="date"
                    :min="min_date"
                    type="date"
                    :disable="disable_dates"
                    :max="max_date">
                    <template #day-item="{ vm, day, color }">
                        <span class="vpd-day-effect" :style="{ 'background-color': color }"/>
                        <span class="vpd-day-text" v-text="day.formatted"/>
                        <strong class="vpd-day-price" v-text="getFormattedPrice(getPrice(day.date, false))"/>
                    </template>
                </date-picker>
            </div>

            <input v-for="item in selectedDates" :key="item" type="hidden" :name="`${date_name}[]`" :value="formatDateForSubmit(item)">

            <div v-if="hasSelection" class="d-flex justify-content-center my-3">
                <button type="button" @click="openEditSheet" class="btn btn-primary mx-2 rounded">{{ text_edit }}</button>
            </div>

            <!-- Bottom sheet — موبایل -->
            <div
                v-if="useStackedCalendar && editSheetMounted"
                class="mobile-reserve-sheet home-calendar-edit-sheet"
                :class="{ 'is-open': editSheetOpen, 'is-closing': editSheetClosing }"
                aria-hidden="false">
                <div class="mobile-reserve-sheet__backdrop" @click="closeEditSheet"></div>

                <div class="mobile-reserve-sheet__dock">
                    <button
                        type="button"
                        class="mobile-reserve-sheet__close"
                        aria-label="بستن"
                        @click="closeEditSheet">
                        <i class="bi bi-x-lg" aria-hidden="true"></i>
                    </button>

                    <div class="mobile-reserve-sheet__panel" role="dialog" aria-modal="true" @click.stop>
                        <div class="home-calendar-edit-sheet__head">
                            <h2 class="home-calendar-edit-sheet__title">تغییر مورد نظر رو انتخاب و اعمال کن</h2>
                            <p class="home-calendar-edit-sheet__selection">{{ selectionSummary }}</p>
                        </div>

                        <div class="home-calendar-edit-sheet__body">
                            <div
                                v-for="section in accordionSections"
                                :key="section.id"
                                class="calendar-edit-accordion"
                                :class="{ 'is-open': openAccordion === section.id }">
                                <button
                                    type="button"
                                    class="calendar-edit-accordion__trigger"
                                    :aria-expanded="openAccordion === section.id"
                                    @click="toggleAccordion(section.id)">
                                    <span class="calendar-edit-accordion__label">
                                        {{ section.label }}
                                        <i
                                            v-if="section.info"
                                            class="bi bi-info-circle calendar-edit-accordion__info"
                                            aria-hidden="true"></i>
                                    </span>
                                    <i class="bi bi-chevron-down calendar-edit-accordion__chevron" aria-hidden="true"></i>
                                </button>

                                <div v-show="openAccordion === section.id" class="calendar-edit-accordion__panel">
                                    <template v-if="section.id === 'availability'">
                                        <div class="calendar-edit-field calendar-edit-field--switch">
                                            <div>
                                                <div class="calendar-edit-field__label">{{ text_active_or_deactivate_days }}</div>
                                                <p class="calendar-edit-field__hint">{{ text_is_active_description }}</p>
                                            </div>
                                            <input type="hidden" :name="is_active_name" :value="is_active ? 'true' : 'false'">
                                            <switches theme="bootstrap" color="warning" v-model="is_active" />
                                        </div>
                                    </template>

                                    <template v-else-if="section.id === 'pricing'">
                                        <input type="hidden" :name="price_name" v-model="price">
                                        <label class="calendar-edit-field__label">{{ text_price }}</label>
                                        <money
                                            v-model="price"
                                            inputmode="numeric"
                                            class="form-control calendar-edit-input"
                                            min="1000"
                                        ></money>
                                        <p class="calendar-edit-field__hint">{{ text_price_set_based_on_selected_first_date }}</p>
                                    </template>

                                    <template v-else-if="section.id === 'min_stay'">
                                        <p class="calendar-edit-field__hint">حداقل تعداد شب برای رزرو روزهای انتخاب‌شده</p>
                                        <div class="mobile-qty-stepper calendar-min-nights-stepper">
                                            <button
                                                type="button"
                                                class="qty-btn qty-minus"
                                                aria-label="کم کردن"
                                                :disabled="min_nights <= 1"
                                                @click="adjustMinNights(-1)">−</button>
                                            <span class="qty-input qty-display" aria-live="polite">{{ toPersianNum(min_nights) }}</span>
                                            <button
                                                type="button"
                                                class="qty-btn qty-plus"
                                                aria-label="زیاد کردن"
                                                :disabled="min_nights >= maxMinNightsCap"
                                                @click="adjustMinNights(1)">+</button>
                                        </div>
                                        <p class="calendar-edit-field__hint">{{ minNightsHint }}</p>
                                        <div v-if="minNightsIssueList.length" class="calendar-edit-min-nights-alert" role="alert">
                                            <p class="calendar-edit-min-nights-alert__title">{{ text_min_nights_warning_intro }}</p>
                                            <ul class="calendar-edit-min-nights-alert__list">
                                                <li v-for="(issue, index) in minNightsIssueList" :key="'min-nights-issue-' + index">
                                                    {{ issue.message }}
                                                </li>
                                            </ul>
                                        </div>
                                    </template>

                                    <template v-else-if="section.id === 'instant_off'">
                                        <label class="calendar-edit-field__label">{{ text_off }}</label>
                                        <div class="calendar-edit-off-btns">
                                            <button
                                                v-for="item in off_percentages"
                                                :key="String(item)"
                                                type="button"
                                                class="calendar-edit-off-btn"
                                                :class="{ 'is-active': off == item }"
                                                @click="off = item">
                                                <template v-if="!item">{{ text_no_off }}</template>
                                                <template v-else>{{ item }} {{ text_percentage }}</template>
                                            </button>
                                        </div>
                                        <p class="calendar-edit-field__hint">برای محاسبه قیمت نهایی روزهای انتخاب‌شده</p>
                                    </template>

                                    <template v-else-if="section.link">
                                        <p class="calendar-edit-field__hint">{{ section.hint }}</p>
                                        <a :href="section.link" class="calendar-edit-link-btn">{{ section.linkLabel }}</a>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="mobile-reserve-sheet__footer home-calendar-edit-sheet__footer">
                            <p class="home-calendar-edit-sheet__footer-hint">
                                بعد از اعمال همه تغییرات «ذخیره» رو کلیک کن.
                            </p>
                            <div class="home-calendar-edit-sheet__actions">
                                <button type="button" class="home-calendar-edit-sheet__btn home-calendar-edit-sheet__btn--cancel" @click="closeEditSheet">
                                    {{ button_cancel_text }}
                                </button>
                                <button
                                    type="submit"
                                    class="home-calendar-edit-sheet__btn home-calendar-edit-sheet__btn--save"
                                    :disabled="!canSubmit">
                                    {{ text_submit }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- مودال دسکتاپ -->
            <div v-if="showModal && !useStackedCalendar" class="modal fade show d-block" style="background-color: rgba(0, 0, 0, .5); transition: opacity .3s ease; overflow: auto" tabindex="-1">
                <div class="modal-dialog modal-lg mt-6" role="document">
                    <div class="modal-content border-0">
                        <div class="modal-header d-block border-bottom-0">
                            <div class="p-4">
                                <button type="button" class="close" @click="showModal = false">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="px-4 mt-3 d-flex justify-content-between">
                                <h6 class="font-weight-bold text-black" style="font-size: 17px;">{{ text_edit }}</h6>
                                <h6 class="font-weight-bold text-muted" style="font-size: 17px;">{{ selectedDates.length }} {{ text_day_selected }}</h6>
                            </div>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group d-flex justify-content-between px-5">
                                <div>
                                    <label class="form-label text-black" @click="is_active = !is_active">{{ text_active_or_deactivate_days }}</label>
                                    <p class="text-muted">{{ text_is_active_description }}</p>
                                </div>
                                <input type="hidden" :name="is_active_name" :value="is_active ? 'true' : 'false'">
                                <switches theme="bootstrap" color="warning" v-model="is_active" />
                            </div>
                            <div class="form-group">
                                <input type="hidden" :name="price_name" v-model="price">
                                <label style="color: black">{{ text_price }}</label>
                                <money v-model="price" inputmode="numeric" class="form-control" min="1000"></money>
                                <p class="text-muted">{{ text_price_set_based_on_selected_first_date }}</p>
                            </div>
                            <div class="form-group mt-4">
                                <label style="color: black">حداقل مدت رزرو (شب)</label>
                                <div class="d-flex align-items-center justify-content-center my-2">
                                    <button type="button" class="btn btn-outline-secondary mx-2" :disabled="min_nights <= 1" @click="adjustMinNights(-1)">−</button>
                                    <strong style="font-size: 20px; min-width: 40px; text-align: center;">{{ min_nights }}</strong>
                                    <button type="button" class="btn btn-outline-secondary mx-2" :disabled="min_nights >= maxMinNightsCap" @click="adjustMinNights(1)">+</button>
                                </div>
                                <p class="text-muted text-center">{{ minNightsHint }}</p>
                                <div v-if="minNightsIssueList.length" class="alert alert-warning mt-3 mb-0" role="alert">
                                    <p class="mb-2"><strong>{{ text_min_nights_warning_intro }}</strong></p>
                                    <ul class="mb-0 ps-3">
                                        <li v-for="(issue, index) in minNightsIssueList" :key="'desktop-min-nights-' + index">
                                            {{ issue.message }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group mt-4">
                                <label style="color: black">{{ text_off }}</label>
                                <input min="1" max="99" type="number" class="form-control" v-model="off">
                                <div class="d-flex mt-2" style="overflow: auto">
                                    <button @click="off = item" v-for="item in off_percentages" :class="{'btn mr-2 rounded p-1': true, 'btn-secondary': item != off, 'btn-dark': item == off}" type="button">
                                        <template v-if="!item">{{ text_no_off }}</template>
                                        <template v-else>{{ item }} {{ text_percentage }}</template>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around mt-4">
                                <button class="btn btn-success">{{ text_submit }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import moment from "moment-jalaali";

export default {
    name: "CustomDate",
    props: ['min_date', 'max_date_prop', 'disable_dates_prop', 'holidays_prop', 'date_name', 'text_submit', 'custom_dates',
            'week_price', 'wed_price', 'thu_price', 'fri_price', 'price_name', 'csrf', 'route', 'all_custom_dates',
            'text_set_custom_price', 'text_set_custom_reserve', 'button_cancel_text', 'custom_prices_prop', 'select_range_days',
            'text_delete_changes', 'placeholder', 'text_edit', 'text_remove_selected', 'text_day_selected', 'text_price',
            'text_active_or_deactivate_days', 'is_active_name', 'text_is_active_description', 'text_off',
            'text_price_set_based_on_selected_first_date', 'text_percentage', 'text_no_off', 'stacked_calendar', 'home_edit_url', 'custom_min_nights_prop',
            'text_min_nights_warning_intro', 'text_min_nights_confirm_save', 'text_min_nights_blocked_order_night',
            'text_min_nights_blocked_host_closed_checkin', 'text_min_nights_blocked_order_checkin',
            'text_min_nights_blocked_max_date', 'text_min_nights_saved_with_limits'],
    data(){
        return {
            max_min_nights: 30,
            min_nights: 1,
            skipMinNightsSync: false,
            custom_min_nights: {},
            off_percentages: [null, 10, 20, 25, 50],
            holidays: [],
            disable_dates: [],
            reserved_dates: [],
            custom_prices: [],
            custom_prices_map: {},
            showModal: false,
            editSheetMounted: false,
            editSheetOpen: false,
            editSheetClosing: false,
            openAccordion: 'availability',
            is_custom: false,
            is_active: true,
            off: null,
            price: 0,
            default_price: 0,
            max_date: null,
            date: '',
            selectedDates: [],
        }
    },
    computed: {
        useStackedCalendar() {
            return this.stacked_calendar === true
                || this.stacked_calendar === 'true'
                || this.stacked_calendar === 1
                || this.stacked_calendar === '1';
        },
        hasSelection() {
            return this.selectedDates.length > 0;
        },
        selectionSummary() {
            const count = this.selectedDates.length;
            const label = count === 1 ? 'شب' : 'شب';
            return `${this.toPersianNum(count)} ${label} انتخاب شد`;
        },
        canSubmit() {
            if (!this.hasSelection) {
                return false;
            }
            if (!this.is_active) {
                return true;
            }
            return parseInt(this.price, 10) >= 1000;
        },
        maxMinNightsCap() {
            if (!this.selectedDates.length) {
                return this.max_min_nights;
            }

            let cap = 1;
            this.selectedDates.forEach((dateValue) => {
                const nights = this.getMaxConfigurableMinNightsForDate(dateValue, {
                    ...this.minNightsCheckOptions,
                    maxNights: this.max_min_nights,
                });
                cap = Math.max(cap, nights);
            });

            return Math.min(this.max_min_nights, cap);
        },
        minNightsHint() {
            const nights = this.toPersianNum(this.min_nights);
            const nightLabel = this.min_nights === 1 ? 'شب' : 'شب';
            const capHint = this.maxMinNightsCap < this.max_min_nights
                ? ` (حداکثر ${this.toPersianNum(this.maxMinNightsCap)} شب به‌خاطر روزهای پر بعدی)`
                : '';
            return `برای رزرو از روزهای انتخاب‌شده، حداقل ${nights} ${nightLabel} باید انتخاب شود.${capHint}`;
        },
        minNightsCheckOptions() {
            return {
                maxDate: this.max_date,
                disableDates: [...this.disable_dates, ...this.reserved_dates],
                reservedDates: this.reserved_dates,
                orderBlockedDates: this.disable_dates,
                messages: {
                    order_night: this.text_min_nights_blocked_order_night || '',
                    host_closed_checkin: this.text_min_nights_blocked_host_closed_checkin || '',
                    order_checkin: this.text_min_nights_blocked_order_checkin || '',
                    max_date: this.text_min_nights_blocked_max_date || '',
                    unknown: this.text_min_nights_saved_with_limits || '',
                },
            };
        },
        minNightsIssueList() {
            return this.getMinNightsIssuesForSelection();
        },
        accordionSections() {
            const editBase = this.home_edit_url || '';
            const tab = (name) => editBase ? `${editBase}?open_tab=${name}` : '';

            return [
                { id: 'availability', label: 'پر و خالی کردن تقویم' },
                { id: 'pricing', label: 'نرخ‌گذاری روزهای خاص' },
                { id: 'min_stay', label: 'حداقل مدت رزرو' },
                {
                    id: 'period_discount',
                    label: 'تخفیف دوره‌ای',
                    link: tab('tab-discount'),
                    linkLabel: 'تنظیم تخفیف دوره‌ای',
                    hint: 'تخفیف رزرو چندشبه از بخش تخفیف اقامتگاه تنظیم می‌شود.',
                },
                { id: 'instant_off', label: 'تخفیف لحظه آخری' },
                {
                    id: 'fast_reserve',
                    label: 'رزرو فوری',
                    info: true,
                    link: tab('tab-pricing'),
                    linkLabel: 'مشاهده تنظیمات',
                    hint: 'برای فعال‌سازی رزرو آنی و قطعی، به بخش ویرایش اقامتگاه مراجعه کنید.',
                },
            ];
        },
    },
    created() {
        this.max_date = this.max_date_prop || moment().add(8, 'month').format('YYYY-MM-DD');

        const dates = [];
        $.each(this.disable_dates_prop, function( index, date ) {
            dates.push(date)
        });
        this.disable_dates = dates;

        const reserved = [];
        if (typeof this.custom_dates === 'object' && this.custom_dates !== null) {
            Object.values(this.custom_dates).forEach((date) => reserved.push(date));
        } else if (Array.isArray(this.custom_dates)) {
            this.custom_dates.forEach((date) => reserved.push(date));
        }
        this.reserved_dates = reserved;

        const prices = {};
        $.each(this.custom_prices_prop, function( index, price ) {
            prices[new Date(index)] = price;
            prices[moment(index).format('YYYY-MM-DD')] = price;
        });
        this.custom_prices = prices;
        this.custom_prices_map = { ...this.custom_prices_prop };

        this.custom_min_nights = this.buildCustomMinNightsMap(
            this.custom_min_nights_prop,
            this.all_custom_dates
        );

        const holidayDates = [];
        $.each(this.holidays_prop, function( index, date ) {
            holidayDates.push(moment(date).format('YYYY/MM/DD'))
        });
        this.holidays = holidayDates;

        if (!this.useStackedCalendar) {
            setInterval(function (){
                const input = $(".vpd-hidden-input").parent(".vpd-input-group");
                input.addClass("d-none");
                input.next().children().first().addClass('d-flex justify-content-center');
            }, 500);
        }
    },
    beforeDestroy() {
        document.body.classList.remove('home-calendar-edit-sheet-open');
    },
    watch: {
        selectedDates() {
            this.applyMinNightsCap();
        },
        min_nights() {
            if (this.skipMinNightsSync) {
                return;
            }
            this.syncCustomMinNightsForSelection();
        },
        date: function (date){
            if (this.useStackedCalendar) {
                return;
            }
            this.syncSelectedFromLegacyDate(date);
        },
        showModal: function (show_modal){
            if (!this.useStackedCalendar && show_modal) {
                this.initEditValues();
            }
        },
        off: function (){
            if (this.default_price) {
                const offVal = parseInt(this.off, 10) || 0;
                this.price = this.default_price - ((offVal * this.default_price) / 100);
            }
        }
    },
    methods: {
        toPersianNum(num) {
            const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            return String(num).replace(/\d/g, (d) => digits[parseInt(d, 10)]);
        },
        formatDateForSubmit(item) {
            if (!item) {
                return '';
            }

            const parsed = this.parseCalendarMoment(item);

            if (!parsed) {
                return String(item).trim().replace(/-/g, '/');
            }

            // ارسال شمسی تا سرور یک‌بار تبدیل کند (YYYY/MM/DD میلادی از moment-jalaali اشتباه است)
            return parsed.format('jYYYY/jMM/jDD');
        },
        buildCustomMinNightsMap(source, records) {
            const minNightsMap = {};

            const assignDate = (dateValue, nights) => {
                const value = Math.max(1, parseInt(nights, 10) || 1);
                const keys = this.resolveDateKeys(dateValue);

                if (!keys.length) {
                    return;
                }

                if (value <= 1) {
                    keys.forEach((key) => {
                        delete minNightsMap[key];
                    });
                    return;
                }

                keys.forEach((key) => {
                    minNightsMap[key] = Math.max(minNightsMap[key] || 1, value);
                });
            };

            if (source && typeof source === 'object') {
                Object.keys(source).forEach((key) => {
                    if (/^\d{1,2}$/.test(String(key))) {
                        return;
                    }
                    assignDate(key, source[key]);
                });
            }

            const list = Array.isArray(records)
                ? records
                : (records && typeof records === 'object' ? Object.values(records) : []);

            list.forEach((record) => {
                if (!record || !record.date) {
                    return;
                }
                assignDate(record.date, record.min_nights);
            });

            return minNightsMap;
        },
        initEditValues() {
            if (!this.selectedDates.length) {
                return;
            }
            this.price = this.getPrice(new Date(this.selectedDates[0]), false);
            this.default_price = this.price;
            this.off = null;
            this.skipMinNightsSync = true;
            this.min_nights = this.getInitialMinNightsForSelection();
            this.applyMinNightsCap();
            this.$nextTick(() => {
                this.skipMinNightsSync = false;
            });
        },
        getInitialMinNightsForSelection() {
            if (!this.selectedDates.length) {
                return 1;
            }

            let value = 1;
            this.selectedDates.forEach((dateValue) => {
                value = Math.max(value, this.getMinNightsForDate(dateValue));
            });

            return value;
        },
        getMinNightsForDate(dateValue) {
            return this.getConfiguredMinNightsForDate(this.custom_min_nights, dateValue);
        },
        syncCustomMinNightsForSelection() {
            if (!this.selectedDates.length) {
                return;
            }

            const nights = Math.max(1, parseInt(this.min_nights, 10) || 1);
            const nextMap = { ...this.custom_min_nights };

            this.selectedDates.forEach((dateValue) => {
                const keys = this.resolveDateKeys(dateValue);

                keys.forEach((key) => {
                    if (nights <= 1) {
                        delete nextMap[key];
                    } else {
                        nextMap[key] = nights;
                    }
                });
            });

            this.custom_min_nights = { ...nextMap };
        },
        applyMinNightsCap() {
            const cap = this.maxMinNightsCap;
            const current = Math.max(1, parseInt(this.min_nights, 10) || 1);

            if (current > cap) {
                this.min_nights = cap;
            }
        },
        adjustMinNights(delta) {
            const current = Math.max(1, parseInt(this.min_nights, 10) || 1);
            const next = current + delta;
            this.min_nights = Math.min(this.maxMinNightsCap, Math.max(1, next));
        },
        openEditSheet() {
            if (!this.useStackedCalendar) {
                this.showModal = true;
                return;
            }

            this.initEditValues();
            this.openAccordion = 'availability';
            this.editSheetMounted = true;
            this.editSheetClosing = false;
            document.body.classList.add('home-calendar-edit-sheet-open');

            this.$nextTick(() => {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        this.editSheetOpen = true;
                    });
                });
            });
        },
        closeEditSheet() {
            if (!this.editSheetMounted) {
                return;
            }

            this.editSheetOpen = false;
            this.editSheetClosing = true;

            window.setTimeout(() => {
                if (this.editSheetOpen || !this.editSheetClosing) {
                    return;
                }
                this.editSheetMounted = false;
                this.editSheetClosing = false;
                document.body.classList.remove('home-calendar-edit-sheet-open');
            }, 480);
        },
        toggleAccordion(id) {
            this.openAccordion = this.openAccordion === id ? null : id;
        },
        onDatesChanged(dates) {
            this.selectedDates = Array.isArray(dates) ? dates : [];
            this.syncLegacyDate();
        },
        clearSelection() {
            this.selectedDates = [];
            this.date = this.useStackedCalendar ? [] : '';
            this.closeEditSheet();
        },
        syncLegacyDate() {
            this.date = this.useStackedCalendar ? [...this.selectedDates] : this.selectedDates;
        },
        syncSelectedFromLegacyDate(date) {
            if (Array.isArray(date)) {
                this.selectedDates = [...date];
            } else if (date) {
                this.selectedDates = [date];
            } else {
                this.selectedDates = [];
            }
        },
        highlight(formatted) {
            let attributes = {};

            if (typeof this.custom_dates === 'object') {
                if (Object.values(this.custom_dates).includes(formatted)){
                    attributes['class'] = 'reserved'
                }
            } else if (this.custom_dates.includes(formatted)) {
                attributes['class'] = 'reserved'
            }

            formatted = formatted.replaceAll('/', '-') + ' 00:00:00'
            if (formatted in this.custom_prices_prop){
                attributes['class'] = 'custom-price'
            }

            return attributes;
        },
        getPrice(date, formatted = true) {
            date.setHours(0,0,0,0)
            let day = parseInt(date.getDay());
            let tomorrow = new Date();
            tomorrow.setMonth(date.getMonth());
            tomorrow.setDate(date.getDate() + 1);
            tomorrow.setHours(0,0,0,0)

            let price = this.week_price;
            if (date in this.custom_prices){
                price = this.custom_prices[date]
            }
            else if (this.$root.isHoliday(this.holidays, tomorrow) || day === 4) {
                price = this.thu_price
            }
            else if (this.$root.isHoliday(this.holidays, date) || day === 5){
                price = this.fri_price
            }
            else if (day === 3){
                price = this.wed_price
            }

            if (parseInt(this.off, 10) !== 0 && this.appliesLastMinuteOff(date)){
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
        getFormattedPrice(price) {
            if (price > 999) {
                price = price / 1000;
            }
            return this.$root.formatNumber(price);
        },
        getMinNightsIssuesForSelection() {
            if (!this.is_active || this.min_nights <= 1 || !this.selectedDates.length) {
                return [];
            }

            const issues = [];

            this.selectedDates.forEach((dateValue) => {
                const issue = this.explainMinNightsLimitation(
                    dateValue,
                    this.min_nights,
                    this.minNightsCheckOptions
                );

                if (issue) {
                    issues.push(issue);
                }
            });

            return issues;
        },
        onFormSubmit(event) {
            this.min_nights = Math.max(1, parseInt(this.min_nights, 10) || 1);
            this.syncCustomMinNightsForSelection();

            if (!this.is_active || this.min_nights <= 1) {
                return true;
            }

            const issues = this.getMinNightsIssuesForSelection();
            const blocking = issues.filter((issue) => ['host_closed_checkin', 'order_checkin'].includes(issue.code));

            if (blocking.length) {
                event.preventDefault();
                this.$root.showAlert(blocking.map((issue) => issue.message).join('\n'), 'error');
                return false;
            }

            if (issues.length) {
                event.preventDefault();
                const html = issues.map((issue) => issue.message).join('<br><br>');

                Vue.swal({
                    icon: 'warning',
                    title: this.text_min_nights_warning_intro,
                    html,
                    showCancelButton: true,
                    confirmButtonText: this.text_submit,
                    cancelButtonText: this.button_cancel_text,
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$refs.customDateForm.submit();
                    }
                });

                return false;
            }

            return true;
        },
    }
}
</script>
