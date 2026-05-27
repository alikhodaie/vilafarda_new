document.addEventListener('DOMContentLoaded', function () {
    fetch(`/api/homes/${villaType}?limit=6`)
        .then(response => response.json())
        .then(data => {
            const villas = data.data || data;
            const container = document.getElementById('tomorrow-villas-list');
            if (!container) return;
            container.innerHTML = '';
            villas.forEach(home => {
                const discount = home.discount_percent > 0 ? home.discount_percent : null;
                const badge = discount ? `<div class="discount-badge" style="position:absolute;top:10px;left:10px;z-index:2;">${discount}%</div>` : '';
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.style.position = 'relative'; // برای قرار دادن badge

                slide.innerHTML = ` 
                    ${badge}
                    <a href="/homes/${home.id}">
                        
                        <img src="https://www.falasarnaluxuryvillas.gr/wp-content/uploads/2024/01/profile-2.jpg" 
                            style="width: 100%; height: 150px; object-fit: cover; border-radius: 1.2rem;">
                            <div style="margin-top: 8px; line-height: 1.2;">
                                <p class="m-0 fw-bold" style="font-size: 16px;color:#1a1a1a;">${home.name || ''}</p>
                                <p class="m-0" style="font-size: 14px; color: #666;">${home.city?.name || home.province?.name || ''}</p>
                                <p class="m-0" style="font-size: 12px; color: #000;"> ${home.off_price ? Number(home.off_price).toLocaleString() + ' تومان' : ''}</p>
                            </div>
                    </a>
                `;

                container.appendChild(slide);
            });

            new Swiper('.tomorrow-villas-swiper', {
                slidesPerView: 1.5,
                spaceBetween: 20,
                freeMode: false,
            });
        });
});
