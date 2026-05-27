document.addEventListener('DOMContentLoaded', function () {
    fetch('/api/province')
        .then(response => response.json())
        .then(data => {
            const villas = data.data || data;
            const container = document.getElementById('cities-list');
            if (!container) return;
            container.innerHTML = '';
            villas.forEach(city => {
                const slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.style.position = 'relative'; // برای قرار دادن badge
                slide.innerHTML = ` 
             
                    <a href="/homes?province=${city.province.id}">
                        <img src="${city.image}" 
                            alt="عکس ویلا" 
                            style="width:90px; height:90px; object-fit: cover; border-radius: 1rem;">
                            <div style="margin-top: 8px; line-height: 1.2;">
                                <p class="text-center" style="font-size: 12px;color:#1a1a1a;">${city.province.name}</p>
                            </div>
                    </a>
                `;

                container.appendChild(slide);
            });

            new Swiper('.cities-swiper', {
                slidesPerView: 3.8,
                spaceBetween: 16,
                freeMode: true,
            });
        });
});
