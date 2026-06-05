@if($homes->isNotEmpty())
    @php
        $sectionId = $sectionId ?? 'index-category-' . uniqid();
        $showDesktopNav = $showDesktopNav ?? false;
    @endphp

    <section class="index-section index-category-section" id="{{ $sectionId }}" aria-labelledby="{{ $sectionId }}-heading">
        <div class="index-section__header">
            <h2 class="index-section__title" id="{{ $sectionId }}-heading">{{ $title }}</h2>
            <a href="{{ $link }}" class="index-section__more text-decoration-none" aria-label="مشاهده همه {{ $title }}">مشاهده همه</a>
        </div>
        @if(! empty($description))
            <p class="index-section__description">{{ strip_tags($description) }}</p>
        @endif

        {{-- Same structure as open-tomorrow (ویلافردا) --}}
        <div class="index-swiper-wrap index-swiper-wrap--overlay-nav @if($showDesktopNav) index-swiper-wrap--with-nav @endif" data-category-swiper="{{ $sectionId }}">
            <div class="swiper index-category-swiper">
                <div class="swiper-wrapper">
                    @foreach($homes as $home)
                        @include('main.homes.partials.index-villa-card', [
                            'home' => $home,
                            'is_today' => $is_today ?? false,
                            'is_tomorrow' => $is_tomorrow ?? false,
                        ])
                    @endforeach
                </div>
            </div>
            @if($showDesktopNav)
                @include('main.partials.index-swiper-nav', ['navId' => $sectionId])
            @endif
        </div>
    </section>
@endif
