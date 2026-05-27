/**
 * محدوده قیمت — اسلایدر دوگانه + ورودی با فرمت تومان
 */
(function () {
    'use strict';

    function toPersianDigits(value) {
        return String(value).replace(/\d/g, function (d) {
            return '۰۱۲۳۴۵۶۷۸۹'[d];
        });
    }

    function formatPrice(num) {
        if (!Number.isFinite(num)) {
            return '';
        }
        return toPersianDigits(Number(num).toLocaleString('en-US'));
    }

    function parsePrice(str) {
        var latin = String(str)
            .replace(/[۰-۹]/g, function (d) {
                return '۰۱۲۳۴۵۶۷۸۹'.indexOf(d);
            })
            .replace(/[^\d]/g, '');
        return parseInt(latin, 10) || 0;
    }

    function MobilePriceRange(root) {
        this.root = root;
        this.boundsMin = parseInt(root.getAttribute('data-bounds-min'), 10) || 0;
        this.boundsMax = parseInt(root.getAttribute('data-bounds-max'), 10) || 1000000;
        if (this.boundsMax <= this.boundsMin) {
            this.boundsMax = this.boundsMin + 1000000;
        }

        this.step = parseInt(root.getAttribute('data-step'), 10)
            || Math.max(10000, Math.round((this.boundsMax - this.boundsMin) / 120));

        this.minSlider = root.querySelector('[data-mpr-min-range]');
        this.maxSlider = root.querySelector('[data-mpr-max-range]');
        this.fill = root.querySelector('[data-mpr-fill]');
        this.minDisplay = root.querySelector('[data-mpr-min-display]');
        this.maxDisplay = root.querySelector('[data-mpr-max-display]');
        this.minHidden = root.querySelector('[data-mpr-min-hidden]');
        this.maxHidden = root.querySelector('[data-mpr-max-hidden]');

        var initMin = parseInt(root.getAttribute('data-value-min'), 10);
        var initMax = parseInt(root.getAttribute('data-value-max'), 10);
        this.minVal = Number.isFinite(initMin) ? initMin : this.boundsMin;
        this.maxVal = Number.isFinite(initMax) ? initMax : this.boundsMax;

        this.bind();
        this.syncFromValues();
    }

    MobilePriceRange.prototype.bind = function () {
        var self = this;

        this.minSlider.min = this.boundsMin;
        this.minSlider.max = this.boundsMax;
        this.minSlider.step = this.step;
        this.maxSlider.min = this.boundsMin;
        this.maxSlider.max = this.boundsMax;
        this.maxSlider.step = this.step;

        this.minSlider.addEventListener('input', function () {
            self.minVal = parseInt(self.minSlider.value, 10);
            if (self.minVal > self.maxVal) {
                self.maxVal = self.minVal;
            }
            self.syncFromValues();
        });

        this.maxSlider.addEventListener('input', function () {
            self.maxVal = parseInt(self.maxSlider.value, 10);
            if (self.maxVal < self.minVal) {
                self.minVal = self.maxVal;
            }
            self.syncFromValues();
        });

        this.minDisplay.addEventListener('change', function () {
            self.minVal = self.clamp(parsePrice(self.minDisplay.value), self.boundsMin, self.maxVal);
            self.syncFromValues();
        });

        this.minDisplay.addEventListener('blur', function () {
            self.syncFromValues();
        });

        this.maxDisplay.addEventListener('change', function () {
            self.maxVal = self.clamp(parsePrice(self.maxDisplay.value), self.minVal, self.boundsMax);
            self.syncFromValues();
        });

        this.maxDisplay.addEventListener('blur', function () {
            self.syncFromValues();
        });
    };

    MobilePriceRange.prototype.clamp = function (v, lo, hi) {
        return Math.min(hi, Math.max(lo, v));
    };

    MobilePriceRange.prototype.syncFromValues = function () {
        this.minVal = this.clamp(this.minVal, this.boundsMin, this.boundsMax);
        this.maxVal = this.clamp(this.maxVal, this.boundsMin, this.boundsMax);
        if (this.minVal > this.maxVal) {
            this.maxVal = this.minVal;
        }

        this.minSlider.value = String(this.minVal);
        this.maxSlider.value = String(this.maxVal);

        var pad = 10;
        var wrap = this.root.querySelector('.mobile-price-range__slider-wrap');
        var innerWidth = Math.max(0, (wrap ? wrap.clientWidth : 0) - pad * 2);
        var range = this.boundsMax - this.boundsMin || 1;
        var leftPx = pad + Math.round(((this.minVal - this.boundsMin) / range) * innerWidth);
        var rightPx = pad + Math.round(((this.boundsMax - this.maxVal) / range) * innerWidth);
        this.fill.style.left = leftPx + 'px';
        this.fill.style.right = rightPx + 'px';

        this.minDisplay.value = formatPrice(this.minVal);
        this.maxDisplay.value = formatPrice(this.maxVal);

        if (this.minHidden) {
            this.minHidden.value = this.minVal === this.boundsMin ? '' : String(this.minVal);
        }
        if (this.maxHidden) {
            this.maxHidden.value = this.maxVal === this.boundsMax ? '' : String(this.maxVal);
        }
    };

    MobilePriceRange.prototype.setValues = function (min, max) {
        if (min !== '' && min !== null && min !== undefined) {
            this.minVal = parseInt(min, 10) || this.boundsMin;
        }
        if (max !== '' && max !== null && max !== undefined) {
            this.maxVal = parseInt(max, 10) || this.boundsMax;
        }
        this.syncFromValues();
    };

    function init() {
        document.querySelectorAll('[data-mobile-price-range]').forEach(function (root) {
            if (root._mprInstance) {
                return;
            }
            root._mprInstance = new MobilePriceRange(root);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    window.MobilePriceRange = MobilePriceRange;
    window.initMobilePriceRanges = init;
})();
