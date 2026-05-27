@if($homes->isNotEmpty())
    @php
        $is_off = $is_off ?? false;
    @endphp
<!-- ============================ Section Homes ================================== -->
<section class="@if(!$is_off) gray-simple @endif  min py-2 pt-md-5 pb-md-3" @if($is_off) style="background-color: rgb(211, 157, 26)" @endif >
    <div class="container">
   
        <div class="row">
            <div class="col-8 col-md-10 text-right">
                <div class="sec-heading">
                    <h2 @if($is_off) class="text-light" @endif>{{ $title }}</h2>
                    <p @if($is_off) class="text-light" @endif>{!! $description !!}</p>
                </div>
            </div>
            <div class="col-4 col-md-2 text-center w-100">
                <a href="{{ $link }}" class="btn @if(!$is_off) btn-theme-light-2 @else btn-light @endif text-center seen-more-btn">@lang('title.see_more')</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="d-block thin-scroll" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
                    @foreach($homes as $home)
 
                        <div class="slide-item d-inline-block mx-md-3 mx-1 property-listing property-2">
                            <div class="listing-img-wrapper">
                                <div class="list-img-slide">
                                    <div class="click">
                                        @foreach($home->limit_covers as $image)
                                            <div><a href="{{ $home->link }}"><x-main.home-card-image :home="$home" :src="$image" width="320" height="195" /></a></div>
                                        @endforeach
                                    </div>
                                </div>

                                <div style="position: absolute; top: 15px; left: 15px;">
                                    <favorite
                                        button_class="shadow-button d-flex align-items-center justify-content-center"
                                        auth_check="{{ auth()->check() }}"
                                        route="{{ route('main.homes.favorites.store', $home) }}"
                                        old="{{ $home->isFavorite() }}"
                                        event="home_{{ $home->id }}"
                                        text_please_login="{{ __('text.please_login') }}"
                                    ></favorite>
                                </div>

                                <div style="position: absolute; top: 15px; right: 15px;">
                                    <share-button style="box-shadow: none;" button_class="shadow-button d-flex align-items-center justify-content-center" title="{{ $home->name }}" url="{{ $home->link }}"></share-button>
                                </div>

                                @if($home->has_fast_reserve)
                                    <div style="position: absolute; bottom: 15px; left: 15px;">
                                        <span class="badge badge-warning p-2">رزور سریع</span>
                                    </div>
                                @endif
                            </div>

                            <div class="listing-detail-wrapper">
                                <div class="listing-short-detail-wrap">
                                    <div class="_card_list_flex">
                                        <div class="_card_flex_01">
                                            <p class="text-dark">
                                                {{ $home->typeLabel() }},
                                                {{ __('title.sleep_room', ['count' => number_format($home->sleep_places_count)]) }},
                                                {{ $home->province->name }},
                                                {{ $home->city->name }},
                                                {{ number_format($home->total_guest) }} نفر
                                            </p>
                                            <h4 class="listing-name verified"><a href="{{ $home->link }}" class="prt-link-detail">{{ $home->name }}</a></h4>

                                            @if(!$is_off)
                                                @include('main.homes.partials.guest-rating-card', [
                                                    'home' => $home,
                                                    'class' => 'mt-2',
                                                    'starsWrapperClass' => 'd-none d-md-inline-block',
                                                    'commentFontSize' => '10px',
                                                ])
                                                @if($home->hasGuestReviews())
                                                    <i class="d-inline-block d-md-none _rate_stio fa fa-star"></i>
                                                @endif
                                                <h6 class="listing-card-info-price mt-2 mb-0 p-0 d-inline-block">{{ $home->price($is_today ?? false, $is_tomorrow ?? false) }} @lang('title.toman')</h6>
                                            @else
                                                @php
                                                    $off_price = $home->getPrice(now()->startOfDay());
                                                    $price = $home->getPrice(now()->startOfDay(), true);
                                                    $percent = 100 - (($off_price * 100) / $price);

                                                @endphp
                                                <div class="foot-rates mt-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="listing-card-info-price" style="color: gray; text-decoration: line-through; font-size: 9px">
                                                            {{ number_format($price) }}
                                                            @lang('title.toman')
                                                        </h6>
                                                        <h6 class="listing-card-info-price" style="font-size: 10px">
                                                            {{ number_format($off_price) }}
                                                            @lang('title.toman')
                                                        </h6>
                                                        <h6 class="badge badge-danger text-white p-1 font-bold" style="font-size: 14px">%{{ round($percent) }}</h6>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ============================ Section Homes End ================================== -->
@endif
