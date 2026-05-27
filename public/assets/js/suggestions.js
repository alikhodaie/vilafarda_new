document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('suggestions-section');
    const listContainer = document.getElementById('suggestion-homes-list');
    const badges = document.querySelectorAll('.suggestion-badge');

    if (!listContainer || !badges.length) {
        section?.remove();
        return;
    }

    const MAX_CARDS = 6;
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

    function buildMoreCard(moreUrl) {
        return (
            '<div class="swiper-slide">' +
                '<a href="' + moreUrl + '" class="suggestion-more-card">' +
                    '<div class="suggestion-more-card__inner">' +
                        '<span class="suggestion-more-card__icon" aria-hidden="true">+</span>' +
                        '<span class="suggestion-more-card__text">نمایش موارد بیشتر</span>' +
                    '</div>' +
                '</a>' +
            '</div>'
        );
    }

    function initSwiper() {
        if (typeof Swiper === 'undefined' || !document.querySelector('.suggestions-swiper')) {
            return;
        }

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperInstance = new Swiper('.suggestions-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 14,
            freeMode: true,
        });
    }

    function renderHomes(homes, moreUrl) {
        const items = (homes || []).slice(0, MAX_CARDS);

        if (!items.length) {
            listContainer.innerHTML = '<div class="suggestions__empty swiper-slide">اقامتگاهی برای نمایش وجود ندارد.</div>';
            initSwiper();
            return;
        }

        let html = items.map(buildCard).join('');

        if (moreUrl) {
            html += buildMoreCard(moreUrl);
        }

        listContainer.innerHTML = html;
        initSwiper();
    }

    function getActiveMoreUrl() {
        const active = document.querySelector('.suggestion-badge.active') || badges[0];

        return active?.getAttribute('data-more-url') || '/homes';
    }

    function fetchHomes(slug) {
        if (fetchController) {
            fetchController.abort();
        }

        fetchController = new AbortController();
        listContainer.innerHTML = '<div class="suggestions__loading">در حال بارگذاری...</div>';

        const moreUrl = document.querySelector('.suggestion-badge[data-value="' + slug + '"]')?.getAttribute('data-more-url') || getActiveMoreUrl();

        return fetch('/api/homes/' + encodeURIComponent(slug) + '?limit=' + MAX_CARDS, {
            signal: fetchController.signal,
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('bad response');
                }

                return response.json();
            })
            .then(function (data) {
                const homes = normalizeHomes(data);

                if (!homes.length) {
                    const badge = document.querySelector('.suggestion-badge[data-value="' + slug + '"]');
                    badge?.remove();

                    const remaining = document.querySelectorAll('.suggestion-badge');

                    if (!remaining.length) {
                        section?.remove();
                        return;
                    }

                    remaining[0].classList.add('active');
                    fetchHomes(remaining[0].getAttribute('data-value'));
                    return;
                }

                renderHomes(homes, moreUrl);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                listContainer.innerHTML = '<div class="suggestions__empty">خطا در دریافت اطلاعات</div>';
            });
    }

    badges.forEach(function (badge) {
        badge.addEventListener('click', function () {
            badges.forEach(function (b) {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });

            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            fetchHomes(this.getAttribute('data-value'));
        });
    });

    const firstBadge = document.querySelector('.suggestion-badge.active') || badges[0];

    if (firstBadge) {
        firstBadge.classList.add('active');
        fetchHomes(firstBadge.getAttribute('data-value'));
    }
});
