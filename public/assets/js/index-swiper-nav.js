(function (window) {
    function isDesktop() {
        return window.matchMedia('(min-width: 992px)').matches;
    }

    function slideStep(instance, direction) {
        if (!instance || !instance.slides || !instance.slides.length) {
            return;
        }

        instance.update();

        if (direction < 0) {
            instance.slidePrev();
        } else {
            instance.slideNext();
        }
    }

    function updateNavState(instance, prevEl, nextEl) {
        if (!prevEl || !nextEl) {
            return;
        }

        instance.update();

        var slideCount = instance.slides ? instance.slides.length : 0;
        var canScroll = slideCount > 1
            && !instance.isLocked
            && Math.abs(instance.maxTranslate() - instance.minTranslate()) > 1;

        prevEl.setAttribute('aria-disabled', !canScroll || instance.isBeginning ? 'true' : 'false');
        nextEl.setAttribute('aria-disabled', !canScroll || instance.isEnd ? 'true' : 'false');
    }

    function bindNavButtons(instance, wrap) {
        var prevEl = wrap.querySelector('[data-index-carousel-nav="prev"]');
        var nextEl = wrap.querySelector('[data-index-carousel-nav="next"]');

        if (!prevEl || !nextEl) {
            return;
        }

        prevEl.onclick = function (event) {
            event.preventDefault();
            event.stopPropagation();
            slideStep(instance, -1);
            updateNavState(instance, prevEl, nextEl);
        };

        nextEl.onclick = function (event) {
            event.preventDefault();
            event.stopPropagation();
            slideStep(instance, 1);
            updateNavState(instance, prevEl, nextEl);
        };

        instance.on('init', function () {
            updateNavState(instance, prevEl, nextEl);
        });
        instance.on('resize', function () {
            updateNavState(instance, prevEl, nextEl);
        });
        instance.on('slideChange', function () {
            updateNavState(instance, prevEl, nextEl);
        });
        instance.on('transitionEnd', function () {
            updateNavState(instance, prevEl, nextEl);
        });

        updateNavState(instance, prevEl, nextEl);
    }

    function initInWrap(wrap, overrides) {
        if (!wrap || typeof Swiper === 'undefined') {
            return null;
        }

        var swiperEl = wrap.querySelector('.swiper');

        if (!swiperEl) {
            return null;
        }

        if (swiperEl.swiper) {
            try {
                swiperEl.swiper.destroy(true, true);
            } catch (error) {
                try {
                    swiperEl.swiper.destroy();
                } catch (ignored) {}
            }
        }

        var desktop = isDesktop();
        var hasNav = desktop && wrap.querySelector('[data-index-carousel-nav]');
        var config = {
            slidesPerView: 'auto',
            spaceBetween: desktop ? 20 : 16,
            freeMode: !desktop,
            watchOverflow: false,
            observer: true,
            observeParents: true,
            rtl: true,
            slidesPerGroup: 1,
        };

        if (overrides) {
            for (var key in overrides) {
                if (Object.prototype.hasOwnProperty.call(overrides, key)) {
                    config[key] = overrides[key];
                }
            }
        }

        var instance = new Swiper(swiperEl, config);

        instance.update();

        if (hasNav) {
            bindNavButtons(instance, wrap);
        }

        wrap._indexSwiper = instance;

        return instance;
    }

    function initCategorySwipers() {
        if (typeof Swiper === 'undefined') {
            return false;
        }

        var initialized = 0;

        document.querySelectorAll('.index-category-section .index-swiper-wrap').forEach(function (wrap) {
            var swiperEl = wrap.querySelector('.index-category-swiper');
            var slides = wrap.querySelectorAll('.swiper-slide');

            if (!swiperEl || !slides.length) {
                return;
            }

            try {
                var instance = initInWrap(wrap, { spaceBetween: 16 });

                if (instance && swiperEl.swiper) {
                    initialized += 1;
                }
            } catch (error) {
                console.error('initIndexCategorySwipers failed:', error);
            }
        });

        return initialized > 0;
    }

    function scheduleCategorySwipers() {
        initCategorySwipers();

        window.requestAnimationFrame(function () {
            initCategorySwipers();
        });
    }

    window.IndexSwiperNav = {
        initInWrap: initInWrap,
        initCategorySwipers: initCategorySwipers,
    };

    window.initIndexCategorySwipers = initCategorySwipers;

    if (document.querySelector('.index-category-section')) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', scheduleCategorySwipers);
        } else {
            scheduleCategorySwipers();
        }

        window.addEventListener('load', function () {
            scheduleCategorySwipers();
            [200, 600, 1200].forEach(function (delay) {
                window.setTimeout(initCategorySwipers, delay);
            });
        });
    }
})(window);
