document.addEventListener('DOMContentLoaded', function () {
    const section = document.getElementById('similar-homes-section');
    const listContainer = document.getElementById('similar-homes-list');
    const tabs = document.querySelectorAll('.home-similar-tab');
    const dataEl = document.getElementById('similarHomesData');

    if (!section || !listContainer || !tabs.length || !dataEl) {
        section?.remove();
        return;
    }

    let homesByGroup = {};
    let swiperInstance = null;

    try {
        const parsed = JSON.parse(dataEl.textContent || '{}');
        homesByGroup = parsed.homes || {};
    } catch (e) {
        section.remove();
        return;
    }

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

        const favoriteBtn = window.HomeFavorite
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
        if (typeof Swiper === 'undefined' || !document.querySelector('.home-similar-swiper')) {
            return;
        }

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperInstance = new Swiper('.home-similar-swiper', {
            slidesPerView: 'auto',
            spaceBetween: 14,
            freeMode: true,
        });
    }

    function renderGroup(slug) {
        const items = homesByGroup[slug] || [];
        const activeTab = document.querySelector('.home-similar-tab[data-value="' + slug + '"]');
        const moreUrl = activeTab?.getAttribute('data-more-url') || '/homes';

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

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (item) {
                item.classList.remove('active');
                item.setAttribute('aria-selected', 'false');
            });

            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            renderGroup(this.getAttribute('data-value'));
        });
    });

    const firstTab = document.querySelector('.home-similar-tab.active') || tabs[0];

    if (firstTab) {
        firstTab.classList.add('active');
        renderGroup(firstTab.getAttribute('data-value'));
    }
});
