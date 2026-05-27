(function () {
    var PRICE_WORD_ONES = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
    var PRICE_WORD_TENS = ['', 'ده', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
    var PRICE_WORD_TEENS = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
    var PRICE_WORD_HUNDREDS = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
    var PRICE_WORD_SCALES = ['', 'هزار', 'میلیون', 'میلیارد', 'تریلیون'];

    function toPersianDigits(str) {
        return String(str).replace(/\d/g, function (d) {
            return '۰۱۲۳۴۵۶۷۸۹'[parseInt(d, 10)];
        });
    }

    function priceChunkToWords(n) {
        var parts = [];
        var hundred = Math.floor(n / 100);
        var ten = Math.floor((n % 100) / 10);
        var one = n % 10;

        if (hundred) {
            parts.push(PRICE_WORD_HUNDREDS[hundred]);
        }
        if (ten === 1) {
            parts.push(PRICE_WORD_TEENS[one]);
        } else {
            if (ten) {
                parts.push(PRICE_WORD_TENS[ten]);
            }
            if (one) {
                parts.push(PRICE_WORD_ONES[one]);
            }
        }

        return parts.join(' و ');
    }

    function numberToPersianWords(num) {
        var value = Math.floor(Number(num) || 0);
        if (value <= 0) {
            return '';
        }

        var chunks = [];
        var remaining = value;
        var scale = 0;

        while (remaining > 0 && scale < PRICE_WORD_SCALES.length) {
            var part = remaining % 1000;
            if (part) {
                var words = priceChunkToWords(part);
                if (PRICE_WORD_SCALES[scale]) {
                    words += ' ' + PRICE_WORD_SCALES[scale];
                }
                chunks.unshift(words);
            }
            remaining = Math.floor(remaining / 1000);
            scale += 1;
        }

        return chunks.join(' و ') + ' تومان';
    }

    function parsePriceFieldValue(input) {
        if (!input) {
            return 0;
        }
        var raw = String(input.value || '')
            .replace(/[۰-۹]/g, function (d) {
                return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
            })
            .replace(/[٬,\s]/g, '');
        var num = parseFloat(raw.replace(/[^\d.]/g, ''));
        if (isNaN(num) || num < 0) {
            return 0;
        }
        return Math.trunc(num);
    }

    function formatPriceFieldDisplay(input, withGrouping) {
        if (!input) {
            return;
        }
        var value = parsePriceFieldValue(input);
        if (value <= 0) {
            input.value = '';
            return;
        }
        var str = String(value);
        if (withGrouping) {
            str = str.replace(/\B(?=(\d{3})+(?!\d))/g, '٬');
        }
        input.value = toPersianDigits(str);
    }

    function updatePriceWords(inputId) {
        var input = document.getElementById(inputId);
        var wordsEl = document.getElementById(inputId + '_words');
        if (!input || !wordsEl) {
            return;
        }

        var value = parsePriceFieldValue(input);
        if (value > 0) {
            wordsEl.textContent = numberToPersianWords(value);
            wordsEl.style.display = 'block';
        } else {
            wordsEl.textContent = '';
            wordsEl.style.display = 'none';
        }
    }

    window.adminRefreshAllPriceWords = function () {
        document.querySelectorAll('.admin-home-edit-wrap .price-field').forEach(function (input) {
            formatPriceFieldDisplay(input, true);
            updatePriceWords(input.id);
        });
    };

    window.adminNormalizeAllPriceFieldsForSubmit = function () {
        document.querySelectorAll('.admin-home-edit-wrap .price-field').forEach(function (input) {
            var value = parsePriceFieldValue(input);
            input.value = value > 0 ? String(value) : '';
        });
    };

    function initAdminPriceFields() {
        document.querySelectorAll('.admin-home-edit-wrap .price-field').forEach(function (input) {
            if (input.dataset.priceBound === '1') {
                return;
            }
            input.dataset.priceBound = '1';

            input.addEventListener('focus', function () {
                var value = parsePriceFieldValue(input);
                if (value > 0) {
                    formatPriceFieldDisplay(input, false);
                }
            });

            input.addEventListener('input', function () {
                formatPriceFieldDisplay(input, false);
                updatePriceWords(input.id);
            });

            input.addEventListener('blur', function () {
                formatPriceFieldDisplay(input, true);
                updatePriceWords(input.id);
            });

            input.addEventListener('change', function () {
                formatPriceFieldDisplay(input, true);
                updatePriceWords(input.id);
            });
        });

        window.adminRefreshAllPriceWords();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminPriceFields);
    } else {
        initAdminPriceFields();
    }
})();
