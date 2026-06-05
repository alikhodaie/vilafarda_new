@php
    $cover = $home->limit_covers[0] ?? $home->cover_path;
    $cityName = $home->city->name ?? $home->province->name ?? '';
    $typeLabel = $home->typeLabel();
    $cardTitle = 'اجاره ' . $typeLabel . ($cityName ? ' در ' . $cityName : '');

    $specParts = [];
    if (($home->sleep_places_count ?? 0) > 0) {
        $specParts[] = persianNumber($home->sleep_places_count) . ' خوابه';
    }
    if ($home->infrastructure_meter) {
        $specParts[] = persianNumber($home->infrastructure_meter) . ' متر';
    }
    if ($home->total_guest) {
        $specParts[] = 'تا ' . persianNumber($home->total_guest) . ' مهمان';
    }
    $specs = implode(' . ', $specParts);

    $ratingPayload = $home->guestRatingPayload();
    $scoreLabel = $ratingPayload['score_label'] ?? null;
@endphp

<div class="swiper-slide index-category-slide">
    <div class="home-favorite-card-wrap index-listing-card-wrap">
        <x-home-favorite-button :home="$home" />
        <a href="{{ $home->link }}" class="last-minute-off-card index-listing-card">
            <div class="last-minute-off-card__image-wrap">
                <x-main.home-card-image
                    :home="$home"
                    :src="$cover"
                    width="320"
                    height="240"
                    class="last-minute-off-card__image"
                />
                @if($scoreLabel)
                    <span class="last-minute-off-card__badge-top">
                        <i class="bi bi-star-fill" aria-hidden="true"></i>{{ $scoreLabel }}
                    </span>
                @endif
                <div class="last-minute-off-card__price-overlay">
                    <span class="last-minute-off-card__off-price">
                        از {{ $home->price($is_today ?? false, $is_tomorrow ?? false) }} @lang('title.toman')
                    </span>
                </div>
            </div>
            <div class="last-minute-off-card__body">
                <h3 class="last-minute-off-card__name">{{ $cardTitle }}</h3>
                @if($specs)
                    <p class="last-minute-off-card__specs">{{ $specs }}</p>
                @endif
                @if($home->hasGuestReviews())
                    <span class="last-minute-off-card__rating">
                        <i class="bi bi-star-fill" aria-hidden="true"></i>
                        {{ $home->guestRatingScoreForDisplay() }}
                    </span>
                @endif
            </div>
        </a>
    </div>
</div>
