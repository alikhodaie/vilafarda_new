/**
 * @deprecated Use IndexSwiperNav.initCategorySwipers() from index-swiper-nav.js
 */
(function (window) {
    function boot() {
        if (window.IndexSwiperNav && typeof window.IndexSwiperNav.initCategorySwipers === 'function') {
            window.IndexSwiperNav.initCategorySwipers();
        }
    }

    window.initIndexCategorySwipers = function () {
        if (window.IndexSwiperNav && typeof window.IndexSwiperNav.initCategorySwipers === 'function') {
            return window.IndexSwiperNav.initCategorySwipers();
        }

        return false;
    };

    boot();
})(window);
