@php use App\Models\Order; @endphp
@extends('layouts.main.main', ['title' => $home->name, 'show_fixed_buttons' => false])

@section('meta')
    <meta property="og:title" content="{{ $home->name }}"/>
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($home->description, 300) }}"/>
    <meta property="og:image" content="{{ $home->cover_path }}"/>
    <meta property="og:url" content="{{ $home->link }}"/>
    <meta property="og:type" content="website"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:title" content="{{ $home->name }}"/>
    <meta name="twitter:description" content="{{ \Illuminate\Support\Str::limit($home->description, 300) }}"/>
    <meta name="twitter:image" content="{{ $home->cover_path }}"/>
@endsection

@section('top-assets')
    <link href="{{ public_asset_version('assets/css/home-favorite.css') }}" rel="stylesheet">
    <link href="{{ public_asset_version('assets/css/home-gallery-desktop.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php
        $galleryItems = [];
        $gallerySeen = [];
        if ($home->cover) {
            $coverUrl = $home->cover_path;
            $galleryItems[] = ['url' => $coverUrl, 'alt' => homeImageAlt($home)];
            $gallerySeen[$coverUrl] = true;
        }
        foreach ($home->images as $galleryImage) {
            $imageUrl = $galleryImage->image_path;
            if (isset($gallerySeen[$imageUrl])) {
                continue;
            }
            $gallerySeen[$imageUrl] = true;
            $galleryItems[] = [
                'url' => $imageUrl,
                'alt' => homeImageAlt($home, null, $galleryImage),
            ];
        }
        $galleryTotal = count($galleryItems);
        $galleryVisible = array_slice($galleryItems, 0, 5);
        $galleryRest = array_slice($galleryItems, 5);
        $galleryLayoutCount = count($galleryVisible);
        $galleryHasMore = $galleryTotal > $galleryLayoutCount;
        $galleryScore = $home->guestRatingScore();
        $galleryScoreLabel = null;
        if ($galleryScore !== null) {
            if ($galleryScore >= 5) {
                $galleryScoreLabel = 'ممتاز';
            } elseif ($galleryScore >= 4) {
                $galleryScoreLabel = 'عالی';
            }
        }
    @endphp

    @if($galleryTotal > 0)
        <!-- ============================ Hero Banner  Start================================== -->
        <section class="home-gallery-desktop d-none d-lg-block">
            <div class="container">
                <div class="home-gallery-desktop__frame">
                    <div class="home-gallery-mosaic" data-count="{{ $galleryLayoutCount }}">
                        @foreach($galleryVisible as $index => $item)
                            <a href="{{ $item['url'] }}"
                               class="home-gallery-mosaic__item mfp-gallery @if($index === 0) home-gallery-mosaic__item--hero @endif"
                               aria-label="{{ $item['alt'] }}">
                                <img src="{{ $item['url'] }}"
                                     alt="{{ $item['alt'] }}"
                                     loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                @if($index === 0 && $galleryScoreLabel)
                                    <span class="home-gallery-mosaic__badge">
                                        <i class="bi bi-star-fill" aria-hidden="true"></i>
                                        {{ $galleryScoreLabel }}
                                    </span>
                                @endif
                                @if($galleryHasMore && $index === $galleryLayoutCount - 1)
                                    <span class="home-gallery-mosaic__more">
                                        <span class="home-gallery-mosaic__more-label">
                                            <i class="bi bi-images" aria-hidden="true"></i>
                                            مشاهده بیشتر
                                        </span>
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>

                    <div class="home-gallery-mosaic__actions">
                        <button type="button"
                                class="home-gallery-mosaic__icon-btn"
                                id="homeGalleryShareBtn"
                                aria-label="اشتراک‌گذاری">
                            <i class="bi bi-share" aria-hidden="true"></i>
                        </button>
                        <x-home-favorite-button :home="$home" />
                    </div>

                    @if($galleryRest !== [])
                        <div class="home-gallery-mosaic__rest" aria-hidden="true">
                            @foreach($galleryRest as $item)
                                <a href="{{ $item['url'] }}" class="mfp-gallery">{{ $item['alt'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <div class="featured_slick_gallery gray d-block d-lg-none">
            <div class="featured_slick_gallery-slide">
                @foreach($galleryItems as $image)
                    <div class="featured_slick_padd" style="height: 300px">
                        <a href="{{ $image['url'] }}" class="mfp-gallery-slick">
                            <img src="{{ $image['url'] }}" class="img-fluid mx-auto" alt="{{ $image['alt'] }}"/></a>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- ============================ Hero Banner End ================================== -->
    @endif

    <!-- ============================ Property Name Start================================== -->
    <section class="gallery_bottom_block">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-md-12">
                    <div class="align-items-end">
                        <h3>{{ $home->name }}</h3>
                        <div class="text-muted mt-2"><i class="fas fa-star text-warning"></i> {{ $home->fake_score }}
                            ({{ number_format($home->count_comments) }} دیدگاه)
                        </div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">{{ $home->province->name }} - {{ $home->city->name }}</span>
                                <div class="home-code text-center">کد اقامتگاه: {{ $home->code }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================ property Name End ================================== -->

    <!-- ============================ Property Detail Start ================================== -->
    <section class="gray pt-1">
        <div class="container">
            <div class="row home-show-detail-row">

                <!-- property main detail -->
                <div class="col-12 mb-1">

                    <!-- Single Block Wrap -->
                    <div class="property_block_wrap">
                        <div class="block-body">
                            <div class="row p-0 px-md-3 py-md-2">
                                <div class="col-12 col-md-6">
                                    <div class="row">
                                        <div class="col-2 col-md-1 align-self-center">
                                            <i class="fa fa-home" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="col-10 col-md-11">
                                            {{ $home->detail_text }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 mt-3 mt-md-0">
                                    <div class="row">
                                        <div class="col-2 col-md-1 align-self-center">
                                            <i class="fa fa-user" style="font-size: 24px;"></i>
                                        </div>
                                        <div class="col-10 col-md-11">
                                            {{ $home->guest_text }}
                                            <br>
                                            {{ $home->bedroom_text }}
                                        </div>
                                    </div>
                                </div>
                                @if($home->extra_guest)
                                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                                        <div class="row">
                                            <div class="col-2 col-md-1 align-self-center">
                                                <i class="fa fa-user-plus" style="font-size: 24px;"></i>
                                            </div>
                                            <div class="col-10 col-md-11">
                                                {{ number_format($home->price_per_surplus) }}
                                                تومن به ازای هر نفر مازاد
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- property main detail -->
                <div class="col-lg-8 col-md-12 col-sm-12">

                    <!-- Single Block Wrap -->
                    <div class="property_block_wrap mb-1">

                        <div class="property_block_wrap_header">
                            <h4 class="property_block_title">@lang('title.about') @lang('title.home')</h4>
                        </div>

                        <div class="block-body">
                            <p style="white-space: break-spaces">{!! $home->description !!}</p>
                        </div>

                    </div>

                    @include('main.homes.partials.options', compact('home'))

                    @include('main.homes.partials.sleep-place', compact('home'))

                    @include('main.homes.partials.safeties', compact('home'))

                    @include('main.homes.partials.healths', compact('home'))

                    @include('main.homes.partials.rules', compact('home'))

                    @include('main.homes.partials.cancel-policy', ['home' => $home, 'layout' => 'desktop'])

                    @include('main.homes.partials.video', compact('home'))

                    <!-- Single Block Wrap -->
                    <div class="property_block_wrap mb-1">
                        <div class="d-none d-md-block block-body">
                            <reserve-home
                                :inline="true"
                                csrf="{{ csrf_token() }}"
                                route="{{ route('main.homes.reserve', $home) }}"
                                date_text_start="@lang('title.start_date')"
                                date_text_end="@lang('title.end_date')"
                                count_guest_text="@lang('title.guest_count')"
                                submit_reserve_text="@lang('title.submit_reserve')"
                                total_payment_text="@lang('title.total_payment')"
                                price_text="@lang('title.price')"
                                price_per_surplus_text="@lang('title.total_price_per_surplus')"
                                total_payment_text="@lang('title.total_payment')"
                                max_guest="{{ $home->main_guest }}"
                                max_extra_guest="{{ $home->extra_guest }}"
                                price_per_surplus="{{ $home->price_per_surplus }}"
                                :custom_prices_prop="{{ $home->custom_prices->pluck('price', 'date') }}"
                                off="{{ $home->off }}"
                                daily_off="{{ $home->daily_off }}"
                                daily_off_percent="{{ $home->daily_off_amount }}"
                                week_price="{{ $home->week_price }}"
                                wed_price="{{ $home->wed_price }}"
                                thu_price="{{ $home->thu_price }}"
                                fri_price="{{ $home->fri_price }}"
                                min_date="{{ Order::getMinReserveDate() }}"
                                max_date="{{ \App\Models\Order::getMaxReserveDate() }}"
                                :disable_dates_prop='@json($home->disable_dates)'
                                :fast_reserve_dates="{{ $home->fast_reserve_dates }}"
                                :prop_holidays="{{ \App\Classes\Date::holidayList() }}"
                                text_start_date="{{ __('text.start_date_text', ['hour' => Order::START_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                text_end_date="{{ __('text.end_date_text', ['hour' => \App\Models\Order::END_DATE_HOUR, 'time' => __('title.noon')]) }}"
                            ></reserve-home>
                        </div>
                    </div>

                    @if($home->latitude && $home->longitude)
                        <!-- Single Block Wrap -->
                        <div class="property_block_wrap mb-1">
                            <div class="property_block_wrap_header">
                                <h4 class="property_block_title">@lang('title.position')</h4>
                            </div>
                            <div class="block-body">
                                <div class="map-container">
                                    <leaftlet-map :zoom="13" :layer="true" :readonly="true" :height="450"
                                                  :latitude="{{ $home->latitude }}"
                                                  :longitude="{{ $home->longitude }}"></leaftlet-map>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(! auth()->check())
                        <div class="alert alert-danger text-center">
                            برای ثبت نظر حتما باید وارد حساب خود شوید!
                        </div>
                    @elseif(! auth()->user()->isRent($home))
                        <div class="alert alert-danger text-center">
                            برای ثبت نظر حتما باید یکبار این ملک را رزرو کرده باشید!
                        </div>
                    @else
                        <!-- Single Write a Review -->
                        <div class="property_block_wrap mb-1">

                            <div class="property_block_wrap_header">
                                <h4 class="property_block_title">@lang('title.send_comment')</h4>
                            </div>

                            <div class="block-body">
                                <form action="{{ route('main.homes.comments.store', $home->id) }}" method="POST">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <score-stars></score-stars>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <input value="{{ old('name', auth()->user()->full_name ?? '') }}"
                                                       name="name" type="text" class="form-control"
                                                       placeholder="@lang('title.your_name')">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <input value="{{ old('email', auth()->user()->email ?? '') }}"
                                                       name="email" type="email" class="form-control"
                                                       placeholder="@lang('title.your_email')">
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <textarea name="comment" class="form-control" cols="30" rows="6"
                                                          placeholder="@lang('text.type_your_comment')"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <button type="submit"
                                                        class="btn search-btn">@lang('title.send_comment')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    @endif

                    @if($home->count_comments)
                        <!-- Single Reviews Block -->
                        <div class="property_block_wrap mb-1">

                            <div class="property_block_wrap_header">
                                <h4 class="property_block_title">{{ number_format($home->count_comments) }} @lang('title.comment')</h4>
                            </div>

                            <div class="block-body">
                                <div class="author-review">
                                    <div class="comment-list">
                                        <ul>
                                            @foreach($home->activeComments as $comment)
                                                <li class="article_comments_wrap">
                                                    <article>
                                                        @if($comment->user)
                                                            <div class="article_comments_thumb">
                                                                <img src="{{ $comment->user->avatar_path }}" class="rounded-circle"
                                                                     alt="{{ $comment->full_name }}">
                                                            </div>
                                                        @endif
                                                        <div class="comment-details">
                                                            <div class="comment-meta">
                                                                <div class="comment-left-meta">
                                                                    <h4 class="author-name">{{ $comment->full_name }}</h4>
                                                                    <div
                                                                        class="comment-date">{{ $comment->persianCreatedAt('d F Y') }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="comment-text">
                                                                <p>{{ $comment->comment }}</p>
                                                            </div>
                                                        </div>
                                                    </article>
                                                </li>

                                                @foreach($comment->activeChildren as $child)
                                                    <li class="article_comments_wrap child">
                                                        <article>
                                                            @if($child->user)
                                                                <div class="article_comments_thumb">
                                                                    <img src="{{ $child->user->avatar_path }}" class="rounded-circle"
                                                                         alt="{{ $child->full_name }}">
                                                                </div>
                                                            @endif
                                                            <div class="comment-details">
                                                                <div class="comment-meta">
                                                                    <div class="comment-left-meta">
                                                                        <h4 class="author-name">{{ $child->full_name }}</h4>
                                                                        <div
                                                                            class="comment-date">{{ $child->persianCreatedAt('d F Y') }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="comment-text">
                                                                    <p>{{ $child->comment }}</p>
                                                                </div>
                                                            </div>
                                                        </article>
                                                    </li>
                                                @endforeach
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                </div>

                <!-- property Sidebar -->
                <div class="d-none d-md-block col-lg-4 col-md-12 col-sm-12">
                    <div class="property-sidebar side_stiky">

                        <div class="sider_blocks_wrap shadows">
                            <div class="sidetab-content">
                                <div class="side-booking-body">
                                    <reserve-home
                                        csrf="{{ csrf_token() }}"
                                        route="{{ route('main.homes.reserve', $home) }}"
                                        date_text_start="@lang('title.start_date')"
                                        date_text_end="@lang('title.end_date')"
                                        count_guest_text="@lang('title.guest_count')"
                                        submit_reserve_text="@lang('title.submit_reserve')"
                                        total_payment_text="@lang('title.total_payment')"
                                        price_text="@lang('title.price')"
                                        price_per_surplus_text="@lang('title.total_price_per_surplus')"
                                        total_payment_text="@lang('title.total_payment')"
                                        max_guest="{{ $home->main_guest }}"
                                        max_extra_guest="{{ $home->extra_guest }}"
                                        price_per_surplus="{{ $home->price_per_surplus }}"
                                        :custom_prices_prop="{{ $home->custom_prices->pluck('price', 'date') }}"
                                        off="{{ $home->off }}"
                                        daily_off="{{ $home->daily_off }}"
                                        daily_off_percent="{{ $home->daily_off_amount }}"
                                        week_price="{{ $home->week_price }}"
                                        wed_price="{{ $home->wed_price }}"
                                        thu_price="{{ $home->thu_price }}"
                                        fri_price="{{ $home->fri_price }}"
                                        min_date="{{ Order::getMinReserveDate() }}"
                                        max_date="{{ Order::getMaxReserveDate() }}"
                                        :disable_dates_prop='@json($home->disable_dates)'
                                        :fast_reserve_dates="{{ $home->fast_reserve_dates }}"
                                        :prop_holidays="{{ \App\Classes\Date::holidayList() }}"
                                        text_start_date="{{ __('text.start_date_text', ['hour' => \App\Models\Order::START_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                        text_end_date="{{ __('text.end_date_text', ['hour' => Order::END_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                        hide_calendar="true"
                                        contact_url="{{ route('main.contact-us') }}"
                                        faq_url="{{ route('main.faq') }}"
                                        cancel_policy_url="#cancel-policy"
                                    ></reserve-home>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="d-flex justify-content-center d-md-none w-100"
                 style="position: fixed; bottom: 10px; right: 0; z-index: 1000;">
                <div class="d-flex justify-content-between bg--success rounded p-3"
                     style="width: 90%; background-color: rgb(0 0 0 / 70%)">
                    <span class="align-self-center text-light"
                          style="font-size: 12px">{{ $home->price() }} @lang('title.toman')</span>
                    <button type="button" data-toggle="modal" data-target="#reserve" class="btn btn-info rounded"
                            title="@lang('title.text_set_custom_reserve')">
                        @lang('title.text_set_custom_reserve')
                    </button>
                </div>
            </div>
            <div class="modal fade" id="reserve" tabindex="-1" role="dialog" aria-labelledby="reserve"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <span class="mod-close" data-dismiss="modal" aria-hidden="true"><i
                                    class="ti-close"></i></span>
                        </div>
                        <div class="modal-body">
                            <reserve-home
                                csrf="{{ csrf_token() }}"
                                route="{{ route('main.homes.reserve', $home) }}"
                                date_text_start="@lang('title.start_date')"
                                date_text_end="@lang('title.end_date')"
                                count_guest_text="@lang('title.guest_count')"
                                submit_reserve_text="@lang('title.submit_reserve')"
                                total_payment_text="@lang('title.total_payment')"
                                price_text="@lang('title.price')"
                                price_per_surplus_text="@lang('title.price_per_surplus')"
                                total_payment_text="@lang('title.total_payment')"
                                max_guest="{{ $home->main_guest }}"
                                max_extra_guest="{{ $home->extra_guest }}"
                                price_per_surplus="{{ $home->price_per_surplus }}"
                                :custom_prices_prop="{{ $home->custom_prices->pluck('price', 'date') }}"
                                off="{{ $home->off }}"
                                daily_off="{{ $home->daily_off }}"
                                daily_off_percent="{{ $home->daily_off_amount }}"
                                week_price="{{ $home->week_price }}"
                                wed_price="{{ $home->wed_price }}"
                                thu_price="{{ $home->thu_price }}"
                                fri_price="{{ $home->fri_price }}"
                                min_date="{{ \App\Models\Order::getMinReserveDate() }}"
                                max_date="{{ \App\Models\Order::getMaxReserveDate() }}"
                                :disable_dates_prop='@json($home->disable_dates)'
                                :fast_reserve_dates="{{ $home->fast_reserve_dates }}"
                                :prop_holidays="{{ \App\Classes\Date::holidayList() }}"
                                text_start_date="{{ __('text.start_date_text', ['hour' => \App\Models\Order::START_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                text_end_date="{{ __('text.end_date_text', ['hour' => \App\Models\Order::END_DATE_HOUR, 'time' => __('title.noon')]) }}"
                            ></reserve-home>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ============================ Property Detail End ================================== -->

    @if(!empty($similarCategories))
        <div class="home-similar-homes-desktop">
            <div class="container">
                @include('main.homes.partials.similar-homes', ['layout' => 'desktop'])
            </div>
        </div>
    @endif

    <!-- Reservation Summary Modal for Desktop -->
    <div class="modal fade" id="reserveSummaryModal" tabindex="-1" aria-labelledby="reserveSummaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">خلاصه رزرو</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 0.5; font-size: 1.5rem; font-weight: 700; line-height: 1; color: #000; text-shadow: 0 1px 0 #fff;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reserveSummaryBody">
                    <!-- Summary will be shown here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal" style="background-color: #000; border-color: #000;">بستن</button>
                    <button type="button" class="btn" id="confirmReserveBtn" style="background-color: #D39D1A; border-color: #D39D1A; color: white;">تایید و پرداخت</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker-custom.css') }}">
    <style>
        /* Minimal Sidebar Styles */
        .property-sidebar {
            padding: 0;
            overflow: visible;
        }
        
        .property-sidebar .sider_blocks_wrap {
            background-color: transparent;
            border: none;
            border-radius: 0;
            box-shadow: none;
            padding: 0;
            margin-bottom: 0;
        }
        
        .property-sidebar .sider_blocks_wrap.shadows {
            box-shadow: none;
            border: none;
        }
        
        .property-sidebar .sidetab-content {
            padding: 0;
            overflow: visible;
        }
        
        .property-sidebar .side-booking-body {
            padding: 0;
            background: transparent;
            overflow: visible;
        }

        .home-show-detail-row {
            align-items: stretch;
        }

        @media (min-width: 768px) {
            .property-sidebar.side_stiky {
                position: sticky;
                top: 88px;
                z-index: 20;
            }
        }

        .home-similar-homes-desktop {
            background: #fff;
            padding: 28px 0 40px;
            border-top: 1px solid #ececec;
        }
        
        /* Ensure font is applied */
        .property-sidebar,
        .property-sidebar * {
            font-family: 'Vazirmatn', 'IranYekan', sans-serif !important;
        }
        
        body,
        body * {
            font-family: 'Vazirmatn', 'IranYekan', sans-serif;
        }
        
        /* Reserve Summary Modal Styles */
        .reserve-summary-content {
            padding: 16px 0;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .summary-row:last-child {
            border-bottom: none;
        }
        
        .summary-row.total-row {
            margin-top: 8px;
            padding-top: 16px;
            border-top: 2px solid #D39D1A;
        }
        
        .summary-row .summary-label {
            font-size: 14px;
            color: #666666;
            font-weight: 500;
        }
        
        .summary-row .summary-value {
            font-size: 14px;
            color: #1a1a1a;
            font-weight: 600;
        }
        
        .summary-row.total-row .summary-value {
            font-size: 18px;
            color: #D39D1A;
            font-weight: 700;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const sidebarBtn = document.querySelector('.sidebar-reserve-btn');
                if (sidebarBtn) {
                    sidebarBtn.addEventListener('click', function(e) {
                        setTimeout(function() {
                            const reserveComponent = document.querySelector('.property-sidebar reserve-home');
                            if (reserveComponent && reserveComponent.__vue__) {
                                return;
                            }
                        }, 100);
                    }, true);
                }
            }, 500);

            const shareBtn = document.getElementById('homeGalleryShareBtn');
            if (shareBtn) {
                shareBtn.addEventListener('click', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    const payload = {
                        title: @json($home->name),
                        url: @json($home->link)
                    };
                    if (window.eventBus && typeof window.eventBus.$emit === 'function') {
                        window.eventBus.$emit('open_share_modal', payload);
                        return;
                    }
                    if (navigator.share) {
                        navigator.share(payload).catch(function () {});
                        return;
                    }
                    if (navigator.clipboard && payload.url) {
                        navigator.clipboard.writeText(payload.url);
                    }
                });
            }

            if (window.jQuery && jQuery.fn.magnificPopup) {
                jQuery('.featured_slick_gallery').magnificPopup({
                    type: 'image',
                    delegate: 'a.mfp-gallery-slick',
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    closeBtnInside: false,
                    preloader: true,
                    removalDelay: 0,
                    mainClass: 'mfp-fade',
                    gallery: {
                        enabled: true
                    }
                });
            }
        });
    </script>
@endsection
