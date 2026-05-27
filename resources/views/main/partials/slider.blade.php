{{-- موبایل: فقط اسلایدهای تنظیمات (index:slider). اگر خالی باشد چیزی نمایش داده نمی‌شود. --}}
@php
    $indexSlider = $slider ?? indexPageSlider();
@endphp

@if(! empty($indexSlider))
    <section class="index-hero-slider">
        <div class="swiper index-home-swiper">
            <div class="swiper-wrapper">
                @foreach($indexSlider as $slideIndex => $slide)
                    <div class="swiper-slide">
                        <a href="{{ $slide['link'] }}" class="index-hero-slider__link d-block">
                            <img src="{{ $slide['image'] }}"
                                 alt="{{ sliderSlideAlt($slide, $slideIndex) }}"
                                 class="index-hero-slider__image"
                                 width="1200"
                                 height="480"
                                 @if($slideIndex === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                 decoding="async">
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination index-hero-slider__pagination"></div>
        </div>
    </section>

    <script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Swiper === 'undefined' || !document.querySelector('.index-home-swiper')) {
                return;
            }

            new Swiper('.index-home-swiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: {{ count($indexSlider) > 1 ? 'true' : 'false' }},
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.index-hero-slider__pagination',
                    clickable: true,
                },
            });
        });
    </script>
@endif
