document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/homes/last')
        .then(response => response.json())
        .then(data => {
            const villas = data.data || data; // handle both {data:[]} and []
            const container = document.getElementById('discounted-villas-list');
            if (!container) return;
            container.innerHTML = '';
            villas.forEach(home => {
                const discount = home.discount_percent > 0 ? home.discount_percent : null;
                const badge = discount ? `<div class="discount-badge" style="position:absolute;top:10px;left:10px;z-index:2;">${discount}%</div>` : '';
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.style.minWidth = '200px';
                slide.style.maxWidth = '260px';
                slide.innerHTML = `
                <div class="villa-card p-2" style="width:100%; position:relative; height:360px; display:flex; flex-direction:column; justify-content:flex-end;">
                    <div style="background:#eee; border-radius: 3rem; overflow:hidden; height:100%; width:100%; position:relative; display:flex; flex-direction:column; justify-content:flex-end;">
                        <img src="https://cf.bstatic.com/xdata/images/hotel/max1024x768/698156760.jpg?k=67e1be27243fedc6c6562a7b47493c98e0cb1fdd692cd9ef81f57fa1e665f16a&o=" style="width:100%; height:100%; object-fit:cover; border-radius:3rem;">
                        ${badge}
                        <div class="position-absolute bottom-0 start-0 w-100" style="height:80px; border-bottom-left-radius:3rem; border-bottom-right-radius:3rem; pointer-events:none; background:linear-gradient(0deg,rgba(0,0,0,0.45) 70%,rgba(0,0,0,0) 100%);"></div>
                        <div class="position-absolute bottom-0 start-0 w-100 px-4 pb-3" style="z-index:2;">
                            <div class="fw-bold" style="font-size:1.18rem; color:#fff; text-shadow:0 1px 4px rgba(0,0,0,0.25); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${home.name || '---'}</div>
                            <div style="font-size:1.02rem; color:#eee; text-shadow:0 1px 4px rgba(0,0,0,0.18); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                ${(home.city && home.city.name) || (home.province && home.province.name) || '---'}
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <span style="font-size:1.05rem; color:#fff; text-shadow:0 1px 4px rgba(0,0,0,0.18);">
                                    ${home.off_price || '---'} <span style="font-size:0.85rem; color:#ddd;">تومان/هر شب</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                container.appendChild(slide);
            });
            new Swiper('.discounted-villas-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 16,
                freeMode: true,
            });
        });
}); 