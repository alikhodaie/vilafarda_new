@php
    $indexCities = $cities ?? indexPageCities();
@endphp

@if(! empty($indexCities))
    <section class="index-section index-destinations">
        <div class="index-section__header">
            <h2 class="index-section__title mb-0">
                {{ setting('index:position-title') ?: 'مقاصد پرطرفدار' }}
            </h2>
        </div>

        <div class="index-destinations__grid">
            @foreach($indexCities as $item)
                @php
                    $landingUrl = \App\Models\LandingPage::findActiveUrlFor(
                        $item['province']['id'] ?? null,
                        $item['city']['id'] ?? null,
                        \App\Models\Home::VILAIY
                    );
                @endphp
                <a href="{{ $landingUrl ?? route('main.homes.index', ['province' => $item['province']['id'], 'city' => $item['city']['id']]) }}"
                   class="index-destination-card">
                    <img src="{{ $item['image'] }}"
                         alt="{{ $item['city']['name'] ?? '' }}"
                         class="index-destination-card__image"
                         loading="lazy"
                         decoding="async">
                    <div class="index-destination-card__content">
                        <span class="index-destination-card__label">اجاره ویلا در</span>
                        <strong class="index-destination-card__city">{{ $item['city']['name'] ?? '' }}</strong>
                        @if(! empty($item['count_homes']))
                            <span class="index-destination-card__count">
                                {{ persianNumber($item['count_homes']) }} @lang('title.residence')
                            </span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endif
