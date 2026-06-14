<script src="{{ asset('assets/js/index-section-visibility.js') }}"></script>
<section class="index-section">
    <div class="index-section__header">
        <span class="index-section__title">آخرین مقالات</span>
        <a href="/articles" class="text-decoration-none black-outline-border" style="color:#343434; font-size:0.95rem;">مشاهده همه</a>
    </div>
    <div class="articles-swiper-wrap">
        <div class="swiper articles-swiper">
            <div class="swiper-wrapper" id="articles-list">
                <!-- Articles will be injected here by JS -->
            </div>
            <button type="button" class="articles-swiper-nav articles-swiper-nav--prev swiper-button-prev" aria-label="مقاله قبلی">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </button>
            <button type="button" class="articles-swiper-nav articles-swiper-nav--next swiper-button-next" aria-label="مقاله بعدی">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>

<script>
async function fetchArticles() {
    try {
        const response = await fetch('{{ url("api/post") }}?limit=5'); // Fetch 5 articles for 5 slides
        const data = await response.json();
        const articlesList = document.getElementById('articles-list');
        const articles = data.data || [];

        if (!articles.length) {
            window.IndexSectionVisibility?.hide(articlesList);
            return;
        }

        articlesList.innerHTML = '';

        articles.forEach(article => {
            const articleSlide = `
                <div class="swiper-slide">
                    <a href="/articles/${article.id}/${article.slug}" class="text-decoration-none text-dark">
                        <div class="card shadow-sm">
                            <img src="${article.image_path || '/assets/images/placeholder.jpg'}" 
                                 alt="${article.title}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title" style="font-size: 1rem;">${article.title}</h5>
                                <p class="card-text text-muted" style="font-size: 0.9rem; max-height: 3.6em; overflow: hidden;">${article.summary}</p>
                            </div>
                        </div>
                    </a>
                </div>
            `;
            articlesList.innerHTML += articleSlide;
        });

        // Initialize Swiper
        new Swiper('.articles-swiper', {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            rtl: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.articles-swiper .swiper-button-next',
                prevEl: '.articles-swiper .swiper-button-prev',
            },
            breakpoints: {
                576: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                992: { slidesPerView: 4 },
                1200: { slidesPerView: 5 },
            }
        });

    } catch (error) {
        console.error('Error fetching articles:', error);
        window.IndexSectionVisibility?.hide(document.getElementById('articles-list'));
    }
}

document.addEventListener('DOMContentLoaded', fetchArticles);
</script>
