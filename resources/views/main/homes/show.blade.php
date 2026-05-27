@php use App\Models\Order; @endphp
@extends('layouts.main.main', ['title' => $home->name, 'show_fixed_buttons' => false])

@section('meta')
    @if($home->images->isNotEmpty() || $home->cover)
        @php
            $lcpCover = ($home->cover) ? $home->cover_path : optional($home->images->first())->image_path;
        @endphp
        @if($lcpCover)
            <link rel="preload" as="image" href="{{ $lcpCover }}" fetchpriority="high">
        @endif
    @endif
@endsection

@section('content')
    @if($home->images->isNotEmpty() || $home->cover)
        <!-- ============================ Hero Banner  Start================================== -->
        <!-- Gallery Part Start -->
        <section class="gallery_parts pt-2 pb-2 d-none d-sm-none d-md-none d-lg-none d-xl-block">
            <div class="container">
                <div class="row align-items-center">
                    @php
                        $cover = ($home->cover) ? $home->cover_path: $home->images->shift()->image_path
                    @endphp
                    <div class="col-lg-8 col-md-7 col-sm-12 pl-1">
                        <div class="gg_single_part left">
                            <a href="{{ $cover }}" class="mfp-gallery">
                                <img src="{{ $cover }}"
                                     class="img-fluid mx-auto"
                                     alt="{{ homeImageAlt($home) }}"
                                     width="800"
                                     height="500"
                                     fetchpriority="high"
                                     loading="eager"
                                     decoding="async"/>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-5 col-sm-12 pr-1">
                        @foreach($home->images->take(3) as $index => $image)
                            <div class="gg_single_part-right min @if($index ===1) mt-2 mb-2 @endif">
                                <a href="{{ $image->image_path }}" class="mfp-gallery">
                                    <img src="{{ $image->image_path }}"
                                         class="img-fluid mx-auto"
                                         alt="{{ homeImageAlt($home, $image->original_name ?: 'گالری', $image) }}"
                                         width="400"
                                         height="158"
                                         loading="lazy"
                                         decoding="async"/>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <div class="featured_slick_gallery gray d-block d-md-block d-lg-block d-xl-none">
            <div class="featured_slick_gallery-slide">
                @foreach($home->covers as $slideIndex => $image)
                    <div class="featured_slick_padd" style="height: 300px">
                        <a href="{{ $image }}" class="mfp-gallery">
                            <img src="{{ $image }}"
                                 class="img-fluid mx-auto"
                                 alt="{{ homeImageAlt($home, 'اسلاید '.($slideIndex + 1)) }}"
                                 width="600"
                                 height="300"
                                 @if($slideIndex === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
                                 decoding="async"/></a>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- ============================ Hero Banner End ================================== -->

        <!-- ============================ Property Detail Start ================================== -->
    @endif

    <!-- ============================ Property Name Start================================== -->
    <section class="gallery_bottom_block">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-md-12">
                    <div class="align-items-end">
                        <h3>{{ $home->name }}</h3>
                        @if($home->hasGuestReviews())
                            <div class="text-muted mt-2" title="امتیاز مهمان‌ها">
                                <i class="fas fa-star text-warning"></i> {{ $home->guestRatingScoreForDisplay() }}
                                ({{ persianNumber($home->count_comments) }} نظر مهمان)
                            </div>
                        @endif
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
            <div class="row">

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

                    <div class="property_block_wrap mb-1">
                        <div class="property_block_wrap_header">
                            <h4 class="property_block_title">قیمت روزهای هفته</h4>
                        </div>
                        <div class="block-body">
                            <ul class="list-unstyled mb-2">
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">@lang('title.week_price')</span>
                                    <strong>{{ number_format($home->week_price) }} @lang('title.toman')</strong>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">@lang('title.wed_price')</span>
                                    <strong>{{ number_format($home->wed_price) }} @lang('title.toman')</strong>
                                </li>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <span class="text-muted">@lang('title.thu_price')</span>
                                    <strong>{{ number_format($home->thu_price) }} @lang('title.toman')</strong>
                                </li>
                                <li class="d-flex justify-content-between py-2">
                                    <span class="text-muted">@lang('title.fri_price')</span>
                                    <strong>{{ number_format($home->fri_price) }} @lang('title.toman')</strong>
                                </li>
                            </ul>
                            <p class="text-muted small @if($home->hasLongStayDiscount()) mb-2 @else mb-0 @endif">
                                ممکن است برخی روزها به‌دلیل پیک سفر یا تعطیلات، قیمت متفاوتی داشته باشند. مبلغ دقیق هر شب هنگام انتخاب تاریخ در تقویم رزرو نمایش داده می‌شود.
                            </p>
                            @if($home->hasLongStayDiscount())
                                <p class="small mb-0 py-2 px-3 rounded" style="color: #555; background: #fffafa; border: 1px solid #fce8e8;">
                                    <i class="fas fa-tag ml-1" style="color: #c45c5c; font-size: 12px;" aria-hidden="true"></i>
                                    تخفیف رزرو چندشبه:
                                    <strong style="color: #b84a4a; font-weight: 600;">{{ $home->longStayDiscountLabel() }}</strong>
                                    <span class="text-muted">(برای اقامت‌های بلندتر از این مدت)</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    @include('main.homes.partials.options', compact('home'))

                    @include('main.homes.partials.safeties', compact('home'))

                    @include('main.homes.partials.healths', compact('home'))

                    @if($home->latitude && $home->longitude)
                        <div class="property_block_wrap mb-1">
                            <div class="property_block_wrap_header">
                                <h4 class="property_block_title">محدوده اقامتگاه</h4>
                            </div>
                            <div class="block-body">
                                <p class="text-muted small mb-3">محل تقریبی اقامتگاه در محدودهٔ مشخص‌شده روی نقشه نمایش داده می‌شود.</p>
                                <div class="map-container">
                                    <leaftlet-map :zoom="13" :layer="true" :readonly="true" :height="450" :radius="750"
                                                  :latitude="{{ $home->latitude }}"
                                                  :longitude="{{ $home->longitude }}"></leaftlet-map>
                                </div>
                            </div>
                        </div>
                    @endif

                    @include('main.homes.partials.sleep-place', compact('home'))

                    @include('main.homes.partials.rules', compact('home'))

                    @include('main.homes.partials.cancel-policy', compact('home'))

                    @include('main.homes.partials.video', compact('home'))

                    @include('main.homes.partials.similar-homes', ['layout' => 'desktop'])

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
                                :custom_min_nights_prop='@json($home->custom_min_nights_map)'
                                off="{{ $home->off }}"
                                daily_off="{{ $home->daily_off }}"
                                daily_off_percent="{{ $home->daily_off_amount }}"
                                week_price="{{ $home->week_price }}"
                                wed_price="{{ $home->wed_price }}"
                                thu_price="{{ $home->thu_price }}"
                                fri_price="{{ $home->fri_price }}"
                                min_date="{{ Order::getMinReserveDate() }}"
                                max_date="{{ \App\Models\Order::getMaxReserveDate() }}"
                                :disable_dates_prop="{{ $home->disable_dates }}"
                                :order_blocked_dates_prop="{{ $home->disable_order_dates }}"
                                :host_closed_dates_prop="{{ $home->disable_custom_dates }}"
                                :fast_reserve_dates="{{ $home->fast_reserve_dates }}"
                                :prop_holidays="{{ \App\Classes\Date::holidayList() }}"
                                text_start_date="{{ __('text.start_date_text', ['hour' => Order::START_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                text_end_date="{{ __('text.end_date_text', ['hour' => \App\Models\Order::END_DATE_HOUR, 'time' => __('title.noon')]) }}"
                            ></reserve-home>
                        </div>
                    </div>

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
                                :custom_min_nights_prop='@json($home->custom_min_nights_map)'
                                        off="{{ $home->off }}"
                                        daily_off="{{ $home->daily_off }}"
                                        daily_off_percent="{{ $home->daily_off_amount }}"
                                        week_price="{{ $home->week_price }}"
                                        wed_price="{{ $home->wed_price }}"
                                        thu_price="{{ $home->thu_price }}"
                                        fri_price="{{ $home->fri_price }}"
                                        min_date="{{ Order::getMinReserveDate() }}"
                                        max_date="{{ Order::getMaxReserveDate() }}"
                                        :disable_dates_prop="{{ $home->disable_dates }}"
                                :order_blocked_dates_prop="{{ $home->disable_order_dates }}"
                                :host_closed_dates_prop="{{ $home->disable_custom_dates }}"
                                        :fast_reserve_dates="{{ $home->fast_reserve_dates }}"
                                        :prop_holidays="{{ \App\Classes\Date::holidayList() }}"
                                        text_start_date="{{ __('text.start_date_text', ['hour' => \App\Models\Order::START_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                        text_end_date="{{ __('text.end_date_text', ['hour' => Order::END_DATE_HOUR, 'time' => __('title.noon')]) }}"
                                        hide_calendar="true"
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
                                :custom_min_nights_prop='@json($home->custom_min_nights_map)'
                                off="{{ $home->off }}"
                                daily_off="{{ $home->daily_off }}"
                                daily_off_percent="{{ $home->daily_off_amount }}"
                                week_price="{{ $home->week_price }}"
                                wed_price="{{ $home->wed_price }}"
                                thu_price="{{ $home->thu_price }}"
                                fri_price="{{ $home->fri_price }}"
                                min_date="{{ \App\Models\Order::getMinReserveDate() }}"
                                max_date="{{ \App\Models\Order::getMaxReserveDate() }}"
                                :disable_dates_prop="{{ $home->disable_dates }}"
                                :order_blocked_dates_prop="{{ $home->disable_order_dates }}"
                                :host_closed_dates_prop="{{ $home->disable_custom_dates }}"
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

@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker-custom.css') }}">
    <style>
        /* Minimal Sidebar Styles */
        .property-sidebar {
            padding: 0;
        }
        
        .property-sidebar .sider_blocks_wrap {
            background-color: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: none;
            padding: 0;
            margin-bottom: 0;
        }
        
        .property-sidebar .sider_blocks_wrap.shadows {
            box-shadow: none;
            border: 1px solid #e9ecef;
        }
        
        .property-sidebar .sidetab-content {
            padding: 0;
        }
        
        .property-sidebar .side-booking-body {
            padding: 1rem;
            background: transparent;
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
    </style>
    <script>
        // Ensure sidebar reserve button works (fallback for desktop)
        document.addEventListener('DOMContentLoaded', function() {
            // Wait for Vue to mount
            setTimeout(function() {
                const sidebarBtn = document.querySelector('.sidebar-reserve-btn');
                if (sidebarBtn) {
                    // Check if Vue handler is working
                    sidebarBtn.addEventListener('click', function(e) {
                        // Let Vue handle it first, but if it doesn't work, this will catch it
                        setTimeout(function() {
                            const reserveComponent = document.querySelector('.property-sidebar reserve-home');
                            if (reserveComponent && reserveComponent.__vue__) {
                                // Vue component exists, let it handle
                                return;
                            }
                        }, 100);
                    }, true); // Use capture phase
                }
            }, 500);
        });
    </script>
@endsection
