import moment from "moment-jalaali";

Vue.mixin({
    methods: {
        formatErrors(errors) {
            let errorsFormatted = [];
            for (const [field_name, error_array] of Object.entries(errors.response.data.errors)) {
                errorsFormatted.push(error_array[0]);
            }
            return errorsFormatted;
        },

        formatNumber(number){
            number = parseInt(number).toFixed(3).replace(/\d(?=(\d{3})+\.)/g, '$&,')
            return number.substring(0, number.length - 4);
        },

        showAlert(message, type, is_toast = false) {
            Vue.swal({
                toast: is_toast,
                position: 'top',
                showConfirmButton: false,
                icon: type,
                showCloseButton: true,
                allowOutsideClick: false,
                timer: 5000,
                timerProgressBar: true,
                type: type,
                text: message,
                customClass: {
                    popup: 'swal2-popup-small',
                    title: 'swal2-title-small',
                    htmlContainer: 'swal2-html-container-small'
                }
            });
        },

        datePeriod(start, end){
            start = new Date(start);
            end = new Date(end);
            let dates = [start];
            let Difference_In_Time = end.getTime() - start.getTime();
            let Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
            for (var i = 0; i < (Difference_In_Days - 1); i++) {
                let tomorrow = new Date();
                tomorrow.setMonth(start.getMonth());
                tomorrow.setDate(start.getDate() + i + 1);
                dates.push(tomorrow)
            }

            return dates
        },

        isHoliday(holidays, date){
            date = moment(date).format('YYYY/MM/DD');

            return holidays.includes(date);
        },

        /**
         * یک تاریخ تقویمی را به moment معتبر (میلادی) تبدیل می‌کند.
         * تاریخ‌های اشتباه ذخیره‌شده مثل 1405-03-03 (شمسی به‌جای میلادی) هم اصلاح می‌شوند.
         */
        parseCalendarMoment(dateValue) {
            if (!dateValue) {
                return null;
            }

            if (dateValue instanceof Date) {
                const parsed = moment(dateValue);
                return parsed.isValid() ? parsed : null;
            }

            const raw = String(dateValue).trim();

            // Laravel @json date cast: "2026-05-24T20:30:00.000000Z" — strict YYYY-MM-DD ignores TZ and shifts a day
            if (/^\d{4}-\d{2}-\d{2}T/.test(raw)) {
                const isoParsed = moment.parseZone(raw);

                if (isoParsed.isValid()) {
                    return isoParsed.local().startOf('day');
                }
            }

            const isoMatch = raw.match(/^(\d{4})-(\d{1,2})-(\d{1,2})/);

            if (isoMatch) {
                const year = parseInt(isoMatch[1], 10);
                const month = isoMatch[2];
                const day = isoMatch[3];

                if (year >= 1300 && year < 1500) {
                    const asJalali = moment(`${year}/${month}/${day}`, 'jYYYY/jMM/jDD', true);
                    if (asJalali.isValid()) {
                        return asJalali;
                    }
                }
            }

            let parsed = moment(raw, 'jYYYY/jMM/jDD', true);

            if (!parsed.isValid() && /^\d{4}\/\d{1,2}\/\d{1,2}$/.test(raw)) {
                const year = parseInt(raw.split('/')[0], 10);
                if (year >= 1300 && year < 1500) {
                    parsed = moment(raw, 'jYYYY/jMM/jDD', true);
                }
            }

            if (!parsed.isValid()) {
                parsed = moment(raw, ['YYYY-MM-DD', 'YYYY/MM/DD'], true);
            }

            if (!parsed.isValid()) {
                parsed = moment(raw);
            }

            return parsed.isValid() ? parsed : null;
        },

        /**
         * کلیدهای معادل یک تاریخ (شمسی، میلادی، ISO) برای نقشه min_nights.
         */
        resolveDateKeys(dateValue) {
            const parsed = this.parseCalendarMoment(dateValue);

            if (!parsed) {
                return dateValue ? [String(dateValue).trim()] : [];
            }

            const raw = String(dateValue).trim();

            return [
                raw,
                parsed.format('jYYYY/jMM/jDD'),
                parsed.format('YYYY-MM-DD'),
                parsed.format('YYYY/MM/DD'),
            ].filter((key, index, keys) => key && keys.indexOf(key) === index);
        },

        getConfiguredMinNightsForDate(customMinNights, dateValue) {
            if (!dateValue || !customMinNights || typeof customMinNights !== 'object') {
                return 1;
            }

            const keys = this.resolveDateKeys(dateValue);

            for (const key of keys) {
                if (customMinNights[key] !== undefined) {
                    return Math.max(1, parseInt(customMinNights[key], 10) || 1);
                }
            }

            return 1;
        },

        isDateInDisabledList(dateValue, disableDates) {
            if (!disableDates || !disableDates.length) {
                return false;
            }

            const formatted = moment(dateValue).format('YYYY/MM/DD');

            return disableDates.includes(formatted)
                || disableDates.some((disabledDate) => moment(disabledDate).format('YYYY/MM/DD') === formatted);
        },

        isDateInReservedList(dateValue, reservedDates) {
            if (!reservedDates || !reservedDates.length) {
                return false;
            }

            const formatted = moment(dateValue).format('YYYY/MM/DD');

            return reservedDates.some((reservedDate) => {
                if (!reservedDate) {
                    return false;
                }

                return moment(reservedDate).format('YYYY/MM/DD') === formatted
                    || String(reservedDate).replace(/-/g, '/') === formatted;
            });
        },

        isNightUnavailableForMinStay(dateValue, options = {}) {
            if (options.isNightUnavailable) {
                return options.isNightUnavailable(dateValue);
            }

            const formatted = moment(dateValue).format('YYYY/MM/DD');
            const checkIn = moment(options.checkInDate || dateValue).startOf('day');
            const night = moment(dateValue).startOf('day');
            const orderBlockedDates = options.orderBlockedDates || options.disableDates || [];
            const isCheckInNight = night.isSame(checkIn, 'day');

            if (isCheckInNight) {
                return this.isDateInDisabledList(formatted, options.disableDates || [])
                    || this.isDateInReservedList(formatted, options.reservedDates || []);
            }

            const subsequentBlocked = options.disableDates || orderBlockedDates;

            return this.isDateInDisabledList(formatted, subsequentBlocked)
                || this.isDateInReservedList(formatted, options.reservedDates || []);
        },

        getEffectiveMinNightsForDate(customMinNights, dateValue, options = {}) {
            const configured = this.getConfiguredMinNightsForDate(customMinNights, dateValue);

            if (configured <= 1) {
                return 1;
            }

            const checkInDate = moment(dateValue).startOf('day');
            const maxDate = options.maxDate ? moment(options.maxDate).startOf('day') : null;
            const nightOptions = {
                ...options,
                checkInDate: checkInDate.format('YYYY/MM/DD'),
            };

            let consecutive = 0;
            let cursor = checkInDate.clone();

            while (consecutive < configured) {
                if (maxDate && cursor.isAfter(maxDate, 'day')) {
                    break;
                }

                if (this.isNightUnavailableForMinStay(cursor.format('YYYY/MM/DD'), nightOptions)) {
                    break;
                }

                consecutive++;
                cursor.add(1, 'day');
            }

            if (options.autoReduceMinNights === false) {
                return configured;
            }

            return Math.max(1, Math.min(configured, consecutive));
        },

        countConsecutiveBookableNights(dateValue, limit, options = {}) {
            const checkIn = moment(dateValue).startOf('day');
            const maxNights = limit || 30;
            const maxDate = options.maxDate ? moment(options.maxDate).startOf('day') : null;
            const nightOptions = {
                ...options,
                checkInDate: checkIn.format('YYYY/MM/DD'),
            };

            let consecutive = 0;
            let cursor = checkIn.clone();

            while (consecutive < maxNights) {
                if (maxDate && cursor.isAfter(maxDate, 'day')) {
                    break;
                }

                if (this.isNightUnavailableForMinStay(cursor.format('YYYY/MM/DD'), nightOptions)) {
                    break;
                }

                consecutive++;
                cursor.add(1, 'day');
            }

            return consecutive;
        },

        /**
         * بیشترین حداقل شب قابل تنظیم برای یک روز در تقویم میزبان.
         * روز ممکن است شب دوم (یا بعدتر) یک اقامت چندشبه باشد، نه لزوماً روز ورود.
         */
        getMaxConfigurableMinNightsForDate(dateValue, options = {}) {
            const maxNights = options.maxNights || 30;
            let cap = 1;
            const target = moment(dateValue).startOf('day');

            for (let offset = 0; offset < maxNights; offset++) {
                const checkIn = target.clone().subtract(offset, 'day');
                const available = this.countConsecutiveBookableNights(
                    checkIn.format('YYYY/MM/DD'),
                    maxNights,
                    options
                );

                if (available >= offset + 1) {
                    cap = Math.max(cap, Math.min(maxNights, available));
                }
            }

            return cap;
        },

        findMinNightsBlockReason(dateValue, configured, options = {}) {
            const messages = options.messages || {};
            const checkIn = moment(dateValue).startOf('day');
            const maxDate = options.maxDate ? moment(options.maxDate).startOf('day') : null;
            const nightOptions = {
                ...options,
                checkInDate: checkIn.format('YYYY/MM/DD'),
            };

            let consecutive = 0;
            let cursor = checkIn.clone();

            while (consecutive < configured) {
                if (maxDate && cursor.isAfter(maxDate, 'day')) {
                    return {
                        code: 'max_date',
                        message: (messages.max_date || '')
                            .replace(':date', this.formatJalaliDateLabel(cursor))
                            .replace(':configured', this.toPersianNum(configured)),
                    };
                }

                const formatted = cursor.format('YYYY/MM/DD');
                const isCheckInNight = cursor.isSame(checkIn, 'day');

                if (isCheckInNight) {
                    if (this.isDateInReservedList(formatted, options.reservedDates || [])) {
                        return {
                            code: 'host_closed_checkin',
                            message: (messages.host_closed_checkin || '').replace(':date', this.formatJalaliDateLabel(cursor)),
                        };
                    }

                    if (this.isDateInDisabledList(formatted, options.orderBlockedDates || options.disableDates || [])) {
                        return {
                            code: 'order_checkin',
                            message: (messages.order_checkin || '').replace(':date', this.formatJalaliDateLabel(cursor)),
                        };
                    }
                } else if (
                    this.isDateInDisabledList(formatted, options.disableDates || options.orderBlockedDates || [])
                    || this.isDateInReservedList(formatted, options.reservedDates || [])
                ) {
                    return {
                        code: 'order_night',
                        message: (messages.order_night || '')
                            .replace(':checkin_date', this.formatJalaliDateLabel(checkIn))
                            .replace(':blocking_date', this.formatJalaliDateLabel(cursor))
                            .replace(':configured', this.toPersianNum(configured))
                            .replace(':effective', this.toPersianNum(Math.max(1, consecutive))),
                    };
                }

                consecutive++;
                cursor.add(1, 'day');
            }

            return {
                code: 'unknown',
                message: messages.unknown || '',
            };
        },

        formatJalaliDateLabel(momentDate) {
            if (typeof momentDate === 'string') {
                momentDate = moment(momentDate);
            }

            const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
            const formatted = momentDate.format('jYYYY/jMM/jDD');

            return formatted.replace(/\d/g, (digit) => digits[parseInt(digit, 10)]);
        },

        toPersianNum(value) {
            const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

            return String(value).replace(/\d/g, (digit) => digits[parseInt(digit, 10)]);
        },

        explainMinNightsLimitation(dateValue, requestedMinNights, options = {}) {
            const configured = Math.max(1, parseInt(requestedMinNights, 10) || 1);

            if (configured <= 1) {
                return null;
            }

            const bookable = this.countConsecutiveBookableNights(dateValue, configured, options);

            if (bookable >= configured) {
                return null;
            }

            const configurableCap = this.getMaxConfigurableMinNightsForDate(dateValue, {
                ...options,
                maxNights: configured,
            });

            if (configurableCap >= configured) {
                return null;
            }

            const reason = this.findMinNightsBlockReason(dateValue, configured, options);

            return {
                date: moment(dateValue).format('YYYY/MM/DD'),
                configured,
                effective: Math.max(1, bookable),
                code: reason.code,
                message: reason.message,
            };
        },
    }
})
