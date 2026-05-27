document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/homes/open-tomorrow?limit=10')
        .then(response => response.json())
        .then(data => {
            const villas = data.data || data;
            const container = document.getElementById('home-slider'); // هماهنگ با HTML
            if (!container) return;
            container.innerHTML = '';

            villas.forEach(home => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                const imgSrc = home.cover_path || home.cover || '';
                slide.innerHTML = `
                    <a href="/homes/${home.id}">
                        <div style="width:100%; height:220px; overflow:hidden;background:#f5f5f5;">
                            <img src="${imgSrc}" alt="${window.SeoImage ? window.SeoImage.escapeAttr(window.SeoImage.altForHome(home)) : ''}"
                                 width="400" height="220" loading="lazy" decoding="async"
                                 style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    </a>
                `;
                container.appendChild(slide);
            });

            // init Swiper
            new Swiper('.home-swiper', {
                slidesPerView: 1,
                spaceBetween: 8,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        });
});
