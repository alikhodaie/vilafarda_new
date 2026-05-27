document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('discounted-villas-list');
    if (!container) return;

    fetch('/api/homes/open-tomorrow?limit=10')
        .then(response => response.json())
        .then(data => {
            const villas = data.data || data;
            if (window.IndexSectionVisibility?.hideIfEmpty(container, villas)) {
                return;
            }

            container.innerHTML = '';
            villas.forEach(home => {
                const imgSrc = home.cover_path || home.cover || '';
                const discount = home.discount_percent > 0 ? home.discount_percent : null;
                const badge = discount ? `<div class="discount-badge" style="position:absolute;top:10px;left:25px;z-index:2;">${discount}%</div>` : '';
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.style.minWidth = '200px';
                slide.style.maxWidth = '260px';
                const favoriteBtn = window.HomeFavorite
                    ? window.HomeFavorite.buildButtonHtml(home)
                    : '';

                slide.innerHTML = `
                    <div class="home-favorite-card-wrap" style="width:100%;">
                        ${favoriteBtn}
                        <a href="/homes/${home.id}">
                            <div class="villa-card p-2" style="width:100%; position:relative; height:360px; display:flex; flex-direction:column; justify-content:flex-end;">
                                <div class="home-card-image-wrap" style="background:#eee; border-radius: 3rem; overflow:hidden; height:100%; width:100%; position:relative; display:flex; flex-direction:column; justify-content:flex-end;">
                                    <img src="${imgSrc}" style="width:100%; height:100%; object-fit:cover; border-radius:3rem;" alt="${window.SeoImage ? window.SeoImage.escapeAttr(window.SeoImage.altForHome(home)) : ''}" width="260" height="360" loading="lazy" decoding="async">
                                    ${badge}
                                    <div class="villa-card__image-gradient" aria-hidden="true"></div>
                                    <div class="villa-card__caption">
                                        <div class="villa-card__caption-title">${home.name || '---'}</div>
                                        <div class="villa-card__caption-location">
                                            ${(home.city && home.city.name) || (home.province && home.province.name) || '---'}
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <span class="villa-card__caption-price">
                                                ${home.off_price ? Number(home.off_price).toLocaleString() + ' تومان' : ''}
                                                <span class="villa-card__caption-price-unit">تومان/هر شب</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `;

                container.appendChild(slide);
            });
            if (document.querySelector('.discounted-villas-swiper')) {
                new Swiper('.discounted-villas-swiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 16,
                    freeMode: true,
                });
            }
        })
        .catch(() => window.IndexSectionVisibility?.hide(container));
}); 