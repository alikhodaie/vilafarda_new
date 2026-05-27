document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('last-minute-off-section');
    const listContainer = document.getElementById('last-minute-off-list');
    const tabs = document.querySelectorAll('.last-minute-off__tab');
    const countdownEl = document.getElementById('last-minute-countdown');

    if (!section || !listContainer) {
        return;
    }

    let swiperInstance = null;
    let fetchController = null;

    function toPersianDigits(value) {
        return String(value).replace(/\d/g, function (d) {
            return '۰۱۲۳۴۵۶۷۸۹'[d];
        });
    }

    function formatPrice(amount) {
        if (!amount) {
            return '';
        }

        return Number(amount).toLocaleString('fa-IR');
    }

    function normalizeHomes(data) {
        if (Array.isArray(data)) {
            return data;
        }

        if (data && Array.isArray(data.data)) {
            return data.data;
        }

        return [];
    }

    function buildSpecs(home) {
        const parts = [];

        if (home.bedroom_count > 0) {
            parts.push(toPersianDigits(home.bedroom_count) + ' خوابه');
        }

        if (home.infrastructure_meter) {
            parts.push(toPersianDigits(home.infrastructure_meter) + ' متر');
        }

        const guests = home.max_guests || home.main_guest;

        if (guests) {
            parts.push('تا ' + toPersianDigits(guests) + ' مهمان');
        }

        return parts.join(' . ');
    }

    function buildCard(home) {
        const imgSrc = home.cover_path || home.cover || '';
        const cityName = home.city?.name || home.province?.name || '';
        const typeLabel = home.type_label || 'اقامتگاه';
        const title = 'اجاره ' + typeLabel + (cityName ? ' در ' + cityName : '');
        const specs = buildSpecs(home);
        const scoreLabel = home.score_label
            ? '<span class="last-minute-off-card__badge-top"><i class="bi bi-star-fill"></i>' + home.score_label + '</span>'
            : '';
        const discount = home.discount_percent > 0
            ? '<span class="last-minute-off-card__discount">' + toPersianDigits(home.discount_percent) + '٪</span>'
            : '';
        const original = home.original_price > home.off_price
            ? '<span class="last-minute-off-card__original">' + formatPrice(home.original_price) + '</span>'
            : '';
        const offPrice = home.off_price
            ? '<span class="last-minute-off-card__off-price">از ' + formatPrice(home.off_price) + ' تومان</span>'
            : '';
        const rating = typeof formatGuestRatingHtml === 'function'
            ? formatGuestRatingHtml(home)
            : '';

        var favoriteBtn = window.HomeFavorite
            ? window.HomeFavorite.buildButtonHtml(home)
            : '';

        return (
            '<div class="swiper-slide">' +
                '<div class="home-favorite-card-wrap">' +
                    favoriteBtn +
                    '<a href="/homes/' + home.id + '" class="last-minute-off-card">' +
                        '<div class="last-minute-off-card__image-wrap">' +
                            '<img src="' + imgSrc + '" alt="' + (window.SeoImage ? window.SeoImage.escapeAttr(window.SeoImage.altForHome(home)) : '') + '" class="last-minute-off-card__image" width="320" height="200" loading="lazy" decoding="async">' +
                            scoreLabel +
                            '<div class="last-minute-off-card__price-overlay">' +
                                discount + original + offPrice +
                            '</div>' +
                        '</div>' +
                        '<div class="last-minute-off-card__body">' +
                            '<h3 class="last-minute-off-card__name">' + title + '</h3>' +
                            (specs ? '<p class="last-minute-off-card__specs">' + specs + '</p>' : '') +
                            rating +
                        '</div>' +
                    '</a>' +
                '</div>' +
            '</div>'
        );
    }

    function initSwiper() {
        if (typeof Swiper === 'undefined' || !document.querySelector('.last-minute-off-swiper')) {
            return;
        }

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperInstance = new Swiper('.last-minute-off-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 14,
            freeMode: true,
        });
    }

    function showEmpty(cityId) {
        const message = cityId
            ? 'برای این شهر در حال حاضر تخفیف لحظه‌آخری ثبت نشده است.'
            : 'در حال حاضر تخفیف لحظه‌آخری فعالی وجود ندارد.';

        listContainer.innerHTML = '<div class="last-minute-off__empty">' + message + '</div>';

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }
    }

    function renderHomes(homes, cityId) {
        if (!homes || !homes.length) {
            showEmpty(cityId);
            return;
        }

        listContainer.innerHTML = homes.map(buildCard).join('');
        initSwiper();
    }

    function fetchHomes(cityId) {
        if (fetchController) {
            fetchController.abort();
        }

        fetchController = new AbortController();
        listContainer.innerHTML = '<div class="last-minute-off__loading">در حال بارگذاری...</div>';

        let url = '/api/homes/off?limit=10';

        if (cityId) {
            url += '&city_id=' + encodeURIComponent(cityId);
        }

        return fetch(url, { signal: fetchController.signal })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('bad response');
                }

                return response.json();
            })
            .then(function (data) {
                renderHomes(normalizeHomes(data), cityId);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                showEmpty(cityId);
            });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });

            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');

            const cityId = this.getAttribute('data-city-id') || '';
            fetchHomes(cityId);
        });
    });

    function updateCountdown() {
        if (!countdownEl) {
            return;
        }

        const now = new Date();
        const midnight = new Date(now);
        midnight.setHours(24, 0, 0, 0);
        let diff = midnight - now;

        if (diff < 0) {
            diff = 0;
        }

        const hours = Math.floor(diff / 3600000);
        const minutes = Math.floor((diff % 3600000) / 60000);
        const seconds = Math.floor((diff % 60000) / 1000);

        const parts = {
            hours: String(hours).padStart(2, '0'),
            minutes: String(minutes).padStart(2, '0'),
            seconds: String(seconds).padStart(2, '0'),
        };

        countdownEl.querySelectorAll('[data-part]').forEach(function (el) {
            const part = el.getAttribute('data-part');

            if (parts[part] !== undefined) {
                el.textContent = toPersianDigits(parts[part]);
            }
        });
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    const initialHomes = normalizeHomes(window.lastMinuteOffInitial || []);

    if (initialHomes.length) {
        renderHomes(initialHomes, '');
    } else {
        fetchHomes('');
    }
});
