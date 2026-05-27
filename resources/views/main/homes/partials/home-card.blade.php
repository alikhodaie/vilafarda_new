<div class="property-listing list_view">
    <div class="listing-img-wrapper">
        <div class="list-img-slide">
            <div class="click">
                @foreach($home->limit_covers as $image)
                    <div><a href="{{ $home->link }}"><x-main.home-card-image :home="$home" :src="$image" width="400" height="200" /></a></div>
                @endforeach
            </div>
        </div>

        <div style="position: absolute; top: 5px; left: 7px;">
            <favorite
                button_class="shadow-button d-flex align-items-center justify-content-center"
                auth_check="{{ auth()->check() }}"
                route="{{ route('main.homes.favorites.store', $home) }}"
                old="{{ $home->isFavorite() }}"
                event="home_{{ $home->id }}"
                text_please_login="{{ __('text.please_login') }}"
            ></favorite>
        </div>

        <div style="position: absolute; top: 5px; right: 7px;">
            <share-button style="box-shadow: none;" button_class="shadow-button d-flex align-items-center justify-content-center" title="{{ $home->name }}" url="{{ $home->link }}"></share-button>
        </div>

        @if($home->has_fast_reserve)
        <div style="position: absolute; bottom: 6px; left: 6px;">
            <span class="badge badge-warning p-2">رزور سریع</span>
        </div>
        @endif
    </div>

    <div class="list_view_flex">

        <div class="listing-detail-wrapper mt-1">
            <div class="listing-short-detail-wrap">
                <div class="_card_list_flex justify-content-end mb-2">
                    <h6 class="listing-card-info-price mb-0">{{ $home->price($is_today ?? false) }} @lang('title.toman')</h6>
                </div>
                <h4 class="listing-name verified" style="width: 400px">
                    <a href="{{ $home->link }}" class="prt-link-detail">{{ $home->name }}</a>
                </h4>
                <p class="text-muted">
                    {{ $home->typeLabel() }},
                    {{ __('title.sleep_room', ['count' => number_format($home->sleep_places_count)]) }},
                    {{ $home->province->name }},
                    {{ $home->city->name }},
                    {{ number_format($home->total_guest) }} نفر
                </p>
            </div>
        </div>

        <div class="listing-detail-footer">
            <div class="footer-first">
                @include('main.homes.partials.guest-rating-card', ['home' => $home])
            </div>
            <div class="footer-flex">
                <a href="{{ $home->link }}" class="prt-view">@lang('title.detail')</a>
            </div>
        </div>
    </div>
</div>
