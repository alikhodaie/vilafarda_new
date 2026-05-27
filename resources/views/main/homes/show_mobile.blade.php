@php use App\Models\Order; @endphp
@extends('layouts.main.main_mobile', ['title' => $home->name])

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/mobile-consistency.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/home-ratings-summary-mobile.css') }}">
<style>
    /* Hero Slider — fixed frame so portrait/landscape photos stay uniform */
    .home-show-hero-wrap {
        position: relative;
        background: #f5f5f5;
    }

    .hero-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
        background: #e8e8e8;
    }

    .hero-slider__media {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
    }

    #homeImageSwiper {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    #homeImageSwiper .swiper-wrapper,
    #homeImageSwiper .swiper-slide {
        height: 100%;
    }

    #homeImageSwiper .swiper-slide {
        width: 100%;
        overflow: hidden;
        background: #e8e8e8;
    }

    #homeImageSwiper .hero-slide-media {
        display: block;
        width: 100%;
        height: 100%;
        cursor: zoom-in;
        -webkit-tap-highlight-color: transparent;
    }

    #homeImageSwiper .swiper-slide img,
    #homeImageSwiper .swiper-slide .video-container,
    #homeImageSwiper .swiper-slide .video-container video {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
    }

    #homeImageSwiper .swiper-slide .video-container {
        background: #000;
    }

    .hero-slider__badges {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 25;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .hero-slider__badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: rgba(255, 255, 255, 0.95);
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #1a1a1a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        white-space: nowrap;
    }

    .hero-slider__badge i {
        font-size: 14px;
        color: #f5b800;
    }

    .hero-slider__counter {
        position: absolute;
        bottom: 16px;
        left: 16px;
        z-index: 25;
        padding: 6px 12px;
        background: rgba(0, 0, 0, 0.55);
        color: #fff;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        backdrop-filter: blur(6px);
        direction: ltr;
        unicode-bidi: plaintext;
    }

    /* Info card below hero */
    .home-hero-info {
        position: relative;
        margin-top: -20px;
        z-index: 20;
        background: #fff;
        border-radius: 24px 24px 0 0;
        padding: 20px 16px 12px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.06);
    }

    .home-detail-page {
        padding: 4px 16px 24px !important;
    }

    .home-detail-divider {
        border: 0;
        height: 0;
        border-top: 1px solid #ebebeb;
        margin: 0;
        opacity: 1;
    }

    .home-detail-section {
        padding: 14px 0;
    }

    .home-detail-section__title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 1rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 8px;
    }

    .home-detail-section__title i {
        font-size: 1.35rem;
        line-height: 1;
    }

    .home-detail-section__body {
        margin: 0;
        color: #666;
        font-size: 14px;
        line-height: 1.65;
    }

    .home-location-map-wrap {
        padding: 0 0 14px;
    }

    .home-location-map-hint {
        margin: 0 0 10px;
        font-size: 12px;
        color: #888;
        line-height: 1.5;
    }

    .home-location-map {
        height: 240px;
        min-height: 240px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #ebebeb;
        background: #f3f3f3;
        position: relative;
        z-index: 1;
    }

    .home-location-map .leaflet-container {
        height: 100%;
        width: 100%;
        border-radius: 16px;
    }

    .home-weekday-prices {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .home-weekday-prices li {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        gap: 12px;
        padding: 8px 0;
        font-size: 14px;
        color: #444;
        border-bottom: 1px solid #f0f0f0;
    }

    .home-weekday-prices li:last-child {
        border-bottom: none;
    }

    .home-weekday-prices__label {
        color: #666;
    }

    .home-weekday-prices__amount {
        font-weight: 600;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .home-weekday-prices__note {
        margin: 10px 0 0;
        font-size: 12px;
        color: #888;
        line-height: 1.6;
    }

    .home-long-stay-discount {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 10px 0 0;
        padding: 10px 12px;
        font-size: 13px;
        line-height: 1.55;
        color: #555;
        background: #fffafa;
        border: 1px solid #fce8e8;
        border-radius: 10px;
    }

    .home-long-stay-discount i {
        font-size: 15px;
        color: #c45c5c;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .home-long-stay-discount__highlight {
        color: #b84a4a;
        font-weight: 600;
    }

    .home-cancel-policy__type {
        margin: 0 0 10px;
        font-size: 13px;
        color: #888;
    }

    .home-cancel-policy__type strong {
        color: #444;
        font-weight: 600;
    }

    .home-cancel-policy__list {
        margin: 0;
        padding: 0 18px 0 0;
        font-size: 14px;
        color: #555;
        line-height: 1.65;
    }

    .home-cancel-policy__list li + li {
        margin-top: 8px;
    }

    .home-hero-info__breadcrumb {
        font-size: 12px;
        color: #888;
        margin-bottom: 10px;
        line-height: 1.6;
    }

    .home-hero-info__breadcrumb span {
        margin: 0 4px;
        color: #ccc;
    }

    .home-hero-info__title {
        font-size: 18px;
        font-weight: 800;
        color: #1a1a1a;
        line-height: 1.55;
        margin: 0 0 14px;
    }

    .home-hero-info__meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .home-hero-info__rating {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .home-hero-info__rating i {
        color: #f5b800;
        font-size: 15px;
    }

    .home-hero-info__rating small {
        font-weight: 500;
        color: #888;
        font-size: 12px;
    }

    .home-hero-info__bookings {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        background: #f3f3f3;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        color: #555;
        white-space: nowrap;
    }

    .home-hero-info__code {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #555;
        cursor: pointer;
        white-space: nowrap;
        border: none;
        background: transparent;
        padding: 0;
    }

    .home-hero-info__code i {
        font-size: 15px;
        color: #888;
    }
    
    /* Slider Navigation Buttons (hidden — swipe + dots only) */
    .slider-nav-btn {
        display: none !important;
    }
    
    .slider-nav-btn[style*="display: none"] {
        display: none !important;
    }
    
    .slider-nav-btn:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .slider-nav-btn:active {
        transform: translateY(-50%) scale(0.95);
    }
    
    .slider-nav-btn.prev {
        right: 15px;
    }
    
    .slider-nav-btn.next {
        left: 15px;
    }
    
    .slider-nav-btn i {
        font-size: 20px;
        color: #333;
    }
    
    .slider-nav-btn.swiper-button-disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    /* Slider Dots Pagination */
    .slider-pagination {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 6px;
        z-index: 20;
        padding: 0;
        background: transparent;
        border-radius: 0;
        backdrop-filter: none;
    }
    
    .slider-pagination span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.45);
        cursor: pointer;
        transition: all 0.25s ease;
        display: block;
    }

    .slider-pagination span.active {
        background: #fff;
        width: 7px;
        transform: scale(1.25);
    }
    
    .slider-pagination span:hover {
        background: rgba(255, 255, 255, 0.8);
    }

    /* Hero action icons (share / favorite) — top-right like original */
    .hero-icon {
        position: absolute;
        top: 20px;
        z-index: 30;
        width: 44px;
        height: 44px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: #333;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .hero-icon:hover {
        background: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .hero-icon:active {
        transform: scale(0.95);
    }

    .hero-icon.icon-right {
        right: 20px;
    }

    .hero-icon.icon-right--favorite {
        right: 70px;
    }

    .hero-icon.favorited {
        color: #dc3545 !important;
    }
    
    @media (max-width: 480px) {
        .slider-nav-btn {
            width: 36px;
            height: 36px;
        }
        
        .slider-nav-btn i {
            font-size: 16px;
        }
        
        .slider-nav-btn.prev {
            right: 10px;
        }
        
        .slider-nav-btn.next {
            left: 10px;
        }
        
        .slider-pagination {
            bottom: 15px;
        }

        .hero-icon {
            width: 36px;
            height: 36px;
            font-size: 18px;
            top: 15px;
        }

        .hero-icon.icon-right {
            right: 15px;
        }

        .hero-icon.icon-right--favorite {
            right: 58px;
        }

        .home-hero-info__title {
            font-size: 17px;
        }
    }

    /* Sticky Navbar Styles */
    .sticky-nav {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        background: rgba(17, 17, 17, 0.89);
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        transform: translateY(-100%);
        transition: transform 0.3s ease;
        border-bottom-left-radius: 20px;
        border-bottom-right-radius: 20px;
    }
    .sticky-nav.visible {
        transform: translateY(0);
    }
    .sticky-nav i {
        color: white;
        font-size: 20px;
        cursor: pointer;
    }
    .sticky-nav .home-title {
        color: white;
        font-size: 16px;
        font-weight: bold;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    /* Modal Styles */
    .modal-content {
        border-radius: 20px;
        border: none;
    }
    .modal-header {
        border: none;
        padding: 1rem;
        position: relative;
    }
    .modal-close {
        position: absolute;
        left: 1rem;
        top: 1rem;
        background: rgba(0, 0, 0, 0.1);
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .modal-close:hover {
        background: rgba(0, 0, 0, 0.2);
    }
    .modal-close i {
        font-size: 18px;
        color: #333;
    }
    .modal-body {
        padding: 1rem;
    }

    /* Fix calendar overflow on mobile */
    .container {
        width: 100%;
        max-width: 100%;
        padding-left: 1rem;
        padding-right: 1rem;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    #reserve-calendar-section.reserve-calendar-main {
        width: 100%;
        max-width: 100%;
        padding: 14px 0 !important;
        margin: 0 !important;
        background: transparent !important;
        border-radius: 0 !important;
        overflow: visible !important;
        box-sizing: border-box;
    }

    #reserve-calendar-section .home-detail-section__title {
        margin: 0 0 12px;
        padding: 0;
    }

    .home-reserve-calendar-box {
        position: relative;
        border: 1px solid #ebebeb;
        border-radius: 16px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
    }

    .home-reserve-calendar-box .jabama-reserve-form {
        margin: 0;
    }

    #reserve-calendar-section .persian-calendar--stacked {
        width: 100% !important;
        max-width: 100% !important;
        padding: 12px 12px 0 !important;
        border-radius: 0 !important;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-range-header {
        margin-bottom: 10px;
        padding: 0;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-scroll-months {
        position: relative;
        max-height: min(52vh, 380px) !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch;
        padding: 2px 0 8px;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-scroll-months::after {
        content: '';
        display: block;
        position: sticky;
        bottom: 0;
        height: 32px;
        margin-top: -32px;
        background: linear-gradient(to bottom, rgba(255, 255, 255, 0), #fff 90%);
        pointer-events: none;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-month-block {
        margin-bottom: 16px;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-month-block:last-child {
        margin-bottom: 4px;
    }

    #reserve-calendar-section .persian-calendar--stacked .calendar-stacked-footer {
        padding: 10px 12px 12px;
        border-top: 1px solid #f0f0f0;
        background: #fff;
    }

    #reserve-calendar-section .info-box {
        margin: 10px 12px 12px;
    }

    /* Padding برای جلوگیری از پوشیده شدن محتوا توسط fixed box */
    @media (max-width: 768px) {
        body {
            padding-bottom: calc(140px + env(safe-area-inset-bottom, 0px)) !important;
        }

        .reserve-calendar-section--page-end {
            padding-bottom: calc(24px + env(safe-area-inset-bottom, 0px));
            scroll-margin-bottom: calc(160px + env(safe-area-inset-bottom, 0px));
        }
    }

    /* Bottom sheet رزرو موبایل */
    .mobile-reserve-sheet {
        --sheet-duration: 0.48s;
        --sheet-ease: cubic-bezier(0.16, 1, 0.3, 1);
        position: fixed;
        inset: 0;
        z-index: 1090;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        pointer-events: none;
        visibility: hidden;
    }

    .mobile-reserve-sheet.is-open,
    .mobile-reserve-sheet.is-closing {
        visibility: visible;
    }

    .mobile-reserve-sheet.is-open {
        pointer-events: auto;
    }

    .mobile-reserve-sheet.is-closing {
        pointer-events: none;
    }

    .mobile-reserve-sheet__backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        opacity: 0;
        transition: opacity var(--sheet-duration) ease;
    }

    .mobile-reserve-sheet.is-open .mobile-reserve-sheet__backdrop {
        opacity: 1;
    }

    .mobile-reserve-sheet__dock {
        position: relative;
        z-index: 1091;
        width: 100%;
        max-height: 58vh;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        flex: 0 1 auto;
        transform: translate3d(0, 100%, 0);
        transition: transform var(--sheet-duration) var(--sheet-ease), max-height 0.35s var(--sheet-ease);
        will-change: transform;
    }

    .mobile-reserve-sheet.is-open .mobile-reserve-sheet__dock {
        transform: translate3d(0, 0, 0);
    }

    @media (prefers-reduced-motion: reduce) {
        .mobile-reserve-sheet {
            --sheet-duration: 0.01s;
        }

        .mobile-reserve-sheet__dock {
            transition-duration: 0.01s;
        }
    }

    .mobile-reserve-sheet.has-payment .mobile-reserve-sheet__dock {
        max-height: min(92vh, calc(100dvh - 48px));
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__dock {
        max-height: min(88vh, calc(100dvh - 48px));
    }

    .mobile-reserve-sheet__close {
        position: relative;
        left: 16px;
        bottom: auto;
        z-index: 1092;
        width: 40px;
        height: 40px;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.96);
        color: #1a1a1a;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.18);
        font-size: 22px;
        line-height: 1;
        padding: 0;
        margin: 0 0 12px 16px;
        flex-shrink: 0;
        opacity: 0;
        transform: scale(0.92) translateY(8px);
        transition: opacity 0.36s ease, transform 0.4s var(--sheet-ease);
        transition-delay: 0s;
        cursor: pointer;
    }

    .mobile-reserve-sheet.is-open .mobile-reserve-sheet__close {
        opacity: 1;
        transform: scale(1) translateY(0);
        transition-delay: 0.14s;
    }

    .mobile-reserve-sheet__panel {
        position: relative;
        width: 100%;
        flex: 0 1 auto;
        min-height: 0;
        max-height: 100%;
        background: #ffffff;
        border-radius: 20px 20px 0 0;
        padding: 20px 16px calc(16px + env(safe-area-inset-bottom, 0px));
        overflow: hidden;
        box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.18);
        direction: rtl;
        display: flex;
        flex-direction: column;
    }

    .mobile-reserve-sheet__step--form {
        display: flex;
        flex-direction: column;
        max-height: 100%;
        min-height: 0;
    }

    .mobile-reserve-sheet.has-payment .mobile-reserve-sheet__step--form {
        flex: 1 1 auto;
        min-height: 0;
        max-height: calc(min(92vh, calc(100dvh - 48px)) - 52px);
    }

    .mobile-reserve-sheet__body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
        padding-bottom: 4px;
    }

    .mobile-reserve-sheet__footer {
        flex-shrink: 0;
        padding-top: 12px;
        background: #ffffff;
        box-shadow: 0 -6px 16px rgba(0, 0, 0, 0.06);
    }

    .mobile-reserve-sheet__field {
        margin-bottom: 18px;
    }

    .mobile-reserve-sheet__label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .mobile-reserve-sheet__date-split {
        display: flex;
        align-items: stretch;
        border: 1px solid #d9d9d9;
        border-radius: 12px;
        overflow: hidden;
        background: #fff;
    }

    .mobile-reserve-sheet__date-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 14px 10px;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
        color: #1a1a1a;
    }

    .mobile-reserve-sheet__date-btn.is-placeholder {
        color: #9a9a9a;
        font-weight: 400;
    }

    .mobile-reserve-sheet__date-btn.is-active {
        background: #faf6ea;
    }

    .mobile-reserve-sheet__date-divider {
        width: 1px;
        background: #e5e5e5;
        flex-shrink: 0;
    }

    .mobile-reserve-sheet__guest-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .mobile-reserve-sheet__guest-info {
        flex: 1;
        min-width: 0;
    }

    .mobile-reserve-sheet__guest-title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 4px;
    }

    .mobile-reserve-sheet__guest-title i {
        font-size: 18px;
        color: #555;
    }

    .mobile-reserve-sheet__guest-breakdown {
        margin: 0;
        font-size: 12px;
        color: #8a8a8a;
        line-height: 1.5;
    }

    .mobile-reserve-sheet__guest-counter {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .mobile-reserve-sheet__guest-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #d0d0d0;
        border-radius: 8px;
        background: #fff;
        color: #1a1a1a;
        font-size: 20px;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        cursor: pointer;
    }

    .mobile-reserve-sheet__guest-btn:disabled {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .mobile-reserve-sheet__guest-count {
        min-width: 24px;
        text-align: center;
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .mobile-reserve-sheet__info-banner {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        background: #e8f4fd;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 16px;
        font-size: 12px;
        color: #1a5a8a;
        line-height: 1.6;
    }

    .mobile-reserve-sheet__info-banner i {
        font-size: 16px;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .mobile-reserve-payment {
        border-top: 1px solid #eee;
        padding-top: 14px;
        margin-bottom: 14px;
        display: block;
    }

    .mobile-reserve-payment[hidden] {
        display: none !important;
    }

    .mobile-reserve-payment__head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 12px;
    }

    .mobile-reserve-payment__title {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .mobile-reserve-payment__badge {
        font-size: 11px;
        color: #666;
        background: #f0f0f0;
        border-radius: 999px;
        padding: 4px 10px;
        white-space: nowrap;
    }

    .mobile-reserve-payment__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        font-size: 13px;
        color: #444;
        margin-bottom: 8px;
    }

    .mobile-reserve-payment__breakdown {
        background: #f5f5f5;
        border-radius: 10px;
        padding: 10px 12px;
        margin-bottom: 10px;
        max-height: min(28vh, 200px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    .mobile-reserve-payment__breakdown-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 13px;
        color: #333;
        padding: 6px 0;
    }

    .mobile-reserve-payment__breakdown-row + .mobile-reserve-payment__breakdown-row {
        border-top: 1px solid #ebebeb;
    }

    .mobile-reserve-payment__breakdown-formula {
        color: #555;
        line-height: 1.5;
    }

    .mobile-reserve-payment__breakdown-amount {
        font-weight: 600;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .mobile-reserve-payment__row--rent-total {
        margin-bottom: 10px;
        font-weight: 600;
    }

    .mobile-reserve-payment__row--discount {
        color: #c62828;
    }

    .mobile-reserve-payment__row--discount span:last-child {
        color: #c62828;
    }

    .mobile-reserve-payment__row span:last-child {
        font-weight: 600;
        color: #1a1a1a;
        white-space: nowrap;
    }

    .mobile-reserve-payment__total {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        padding: 12px 14px;
        margin-top: 10px;
        font-size: 13px;
        color: #444;
    }

    .mobile-reserve-payment__total strong {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .mobile-reserve-sheet__submit {
        width: 100%;
        border: none;
        border-radius: 999px;
        background: #f5c518;
        color: #1a1a1a;
        font-size: 15px;
        font-weight: 700;
        padding: 14px 16px;
        margin-top: 0;
    }

    .mobile-reserve-sheet__step--calendar {
        display: none;
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__step--form {
        display: none;
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__step--calendar {
        display: block;
    }

    .mobile-reserve-sheet__calendar-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px;
    }

    .mobile-reserve-sheet__calendar-back {
        border: none;
        background: transparent;
        font-size: 20px;
        color: #1a1a1a;
        padding: 0;
        line-height: 1;
    }

    .mobile-reserve-sheet__calendar-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a1a1a;
    }

    .mobile-reserve-sheet__calendar-mount {
        max-height: calc(52vh - 120px);
        overflow-y: auto;
        overflow-x: hidden;
    }

    .mobile-reserve-sheet__calendar-mount .calendar-title {
        display: none;
    }

    .mobile-reserve-sheet__calendar-mount .persian-calendar {
        padding: 0;
        font-family: 'IranYekan', 'Vazirmatn', sans-serif;
    }

    .mobile-reserve-sheet__calendar-mount .persian-calendar--stacked .calendar-scroll-months {
        max-height: none;
        overflow: visible;
    }

    .mobile-reserve-sheet__calendar-mount .persian-calendar--stacked .calendar-day,
    #reserve-calendar-section .persian-calendar--stacked .calendar-day {
        height: auto !important;
        min-height: 48px !important;
        padding-bottom: 0 !important;
    }

    .mobile-reserve-sheet__calendar-mount .persian-calendar--stacked .day-content,
    #reserve-calendar-section .persian-calendar--stacked .day-content {
        position: relative !important;
        min-height: 44px;
    }

    body.mobile-reserve-sheet-open {
        overflow: hidden;
    }

    body.mobile-reserve-sheet-open .fixed-flex-box {
        z-index: 1050;
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__panel {
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__step--calendar {
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        min-height: 0;
    }

    .mobile-reserve-sheet.is-calendar-step .mobile-reserve-sheet__calendar-mount {
        flex: 1 1 auto;
        min-height: 0;
        max-height: none;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* فونت اعداد فارسی */
    .fixed-flex-box-price-amount,
    .fixed-flex-box-badge,
    #mobileReserveSheet,
    #mobileReserveSheet .mobile-reserve-payment,
    #mobileReserveSheet .mobile-reserve-payment *,
    #mobileReserveSheet .mobile-reserve-sheet__guest-count,
    #mobileReserveSheet .mobile-reserve-sheet__date-btn:not(.is-placeholder),
    #reserve-calendar-section .persian-calendar,
    #reserve-calendar-section .day-number,
    #reserve-calendar-section .day-price,
    #reserve-calendar-section .calendar-range-value,
    #reserve-calendar-section .calendar-month-title,
    #reserve-calendar-section .month-year {
        font-family: 'IranYekan', 'Vazirmatn', sans-serif !important;
        font-variant-numeric: tabular-nums;
    }
</style>
<link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')

<!-- Sticky Navbar -->
<div class="sticky-nav">
    <i class="bi bi-arrow-right" onclick="history.back()"></i>
    <h1 class="home-title">{{ $home->name }}</h1>
    <i class="bi bi-box-arrow-up" onclick="shareHome()"></i>
</div>

@php
    $allMedia = collect();
    if ($home->video) {
        $allMedia->push(['type' => 'video', 'url' => $home->video_path]);
    }
    if ($home->cover) {
        $allMedia->push(['type' => 'image', 'url' => $home->cover_path]);
    }
    foreach ($home->images as $image) {
        $allMedia->push(['type' => 'image', 'url' => $image->image_path]);
    }

    $heroMediaTotal = $allMedia->count();
    $guestRatingScore = $home->guestRatingScore();
    $heroScoreLabel = match (true) {
        $guestRatingScore >= 5 => 'ممتاز',
        $guestRatingScore >= 4 => 'عالی',
        default => null,
    };
    $heroSuccessfulBookings = $home->orders()->where('status', Order::DONE)->count();
@endphp

<div class="home-show-hero-wrap">
    <div class="hero-slider">
        <div class="hero-slider__media">
            <div class="swiper" id="homeImageSwiper">
                <div class="swiper-wrapper">
                    @foreach($allMedia as $index => $media)
                        <div class="swiper-slide">
                            @if($media['type'] === 'video')
                                <div class="video-container">
                                    <video src="{{ $media['url'] }}" controls playsinline></video>
                                </div>
                            @else
                                <a href="{{ $media['url'] }}" class="mfp-gallery hero-slide-media" data-gallery-index="{{ $index }}">
                                    <img src="{{ $media['url'] }}" alt="{{ $home->name }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if($heroScoreLabel)
                <div class="hero-slider__badges">
                    <span class="hero-slider__badge">
                        <i class="bi bi-star-fill"></i>
                        {{ $heroScoreLabel }}
                    </span>
                </div>
            @endif

            <i class="bi bi-box-arrow-up hero-icon icon-right" onclick="shareHome()" role="button" aria-label="اشتراک‌گذاری"></i>
            @auth
                @php
                    $isHomeFavorite = $home->isFavorite();
                @endphp
                <i class="bi {{ $isHomeFavorite ? 'bi-heart-fill' : 'bi-heart' }} hero-icon icon-right icon-right--favorite {{ $isHomeFavorite ? 'favorited' : '' }}"
                   id="favoriteBtn"
                   data-home-id="{{ $home->id }}"
                   data-is-favorite="{{ $isHomeFavorite ? '1' : '0' }}"
                   role="button"
                   aria-label="{{ $isHomeFavorite ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}"
                   aria-pressed="{{ $isHomeFavorite ? 'true' : 'false' }}"></i>
            @endauth

            @if($heroMediaTotal > 0)
                <div class="hero-slider__counter" id="heroImageCounter" aria-live="polite">
                    {{ persianNumber(1) }} از {{ persianNumber($heroMediaTotal) }}
                </div>
            @endif

            @if($heroMediaTotal > 1)
                <div class="slider-pagination" id="sliderPagination">
                    @foreach($allMedia as $index => $media)
                        <span data-index="{{ $index }}" @if($index === 0) class="active" @endif></span>
                    @endforeach
                </div>
            @endif
        </div>

        <button class="slider-nav-btn prev" id="sliderPrev" type="button" hidden>
            <i class="bi bi-chevron-right"></i>
        </button>
        <button class="slider-nav-btn next" id="sliderNext" type="button" hidden>
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <div class="home-hero-info">
        <nav class="home-hero-info__breadcrumb" aria-label="دسته‌بندی">
            اجاره {{ $home->typeLabel() }}
        </nav>

        <h1 class="home-hero-info__title">{{ $home->name }}</h1>

        <div class="home-hero-info__meta">
            @if($home->hasGuestReviews())
                <div class="home-hero-info__rating" title="امتیاز مهمان‌ها">
                    <i class="bi bi-star-fill"></i>
                    <span>{{ $home->guestRatingScoreForDisplay() }}</span>
                    <small>({{ persianNumber($home->count_comments) }} نظر مهمان)</small>
                </div>
            @endif

            @if($heroSuccessfulBookings > 0)
                <span class="home-hero-info__bookings">
                    +{{ persianNumber($heroSuccessfulBookings) }} رزرو موفق
                </span>
            @endif

            @if($home->code)
                <button type="button" class="home-hero-info__code" onclick="copyHomeCode()" aria-label="کپی کد اقامتگاه">
                    <i class="bi bi-copy"></i>
                    <span>کد: {{ persianNumber($home->code) }}</span>
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Detail Section -->
<div class="container home-detail-page">
    @php
        $bedDetails = [];
        $share_place = $home->sleepPlaces->where('is_share', true)->first();
        if ($share_place) {
            if ($share_place->single_bed) {
                $bedDetails[] = number_format($share_place->single_bed) . ' تخت یک نفره';
            }
            if ($share_place->double_bed) {
                $bedDetails[] = number_format($share_place->double_bed) . ' تخت دو نفره';
            }
            if ($share_place->traditional_bed) {
                $bedDetails[] = number_format($share_place->traditional_bed) . ' رخت خواب سنتی';
            }
            if ($share_place->more) {
                $bedDetails[] = strip_tags($share_place->more);
            }
        }

        foreach ($home->sleepPlaces->where('is_share', false) as $place) {
            if ($place->single_bed) {
                $bedDetails[] = number_format($place->single_bed) . ' تخت یک نفره';
            }
            if ($place->double_bed) {
                $bedDetails[] = number_format($place->double_bed) . ' تخت دو نفره';
            }
            if ($place->traditional_bed) {
                $bedDetails[] = number_format($place->traditional_bed) . ' رخت خواب سنتی';
            }
            if ($place->more) {
                $bedDetails[] = strip_tags($place->more);
            }
        }

        $bedDetailsText = implode('، ', $bedDetails);
    @endphp

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">
            <i class="bi bi-house"></i>
            توضیحات کلی
        </h3>
        <p class="home-detail-section__body">{{ $home->detail_text }}</p>
    </section>

    <hr class="home-detail-divider">

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">
            <i class="bi bi-person"></i>
            ظرفیت
        </h3>
        <p class="home-detail-section__body">{{ $home->guest_text }}</p>
    </section>

    <hr class="home-detail-divider">

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">
            <i class="bi bi-toggle-off"></i>
            سرویس‌های خواب
        </h3>
        <p class="home-detail-section__body">
            {{ $home->sleepPlaces->where('is_share', false)->count() }} اتاق خواب -
            {{ $home->sleepPlaces->sum(function($p) {
                return ($p->single_bed ?? 0) + ($p->double_bed ?? 0) + ($p->traditional_bed ?? 0);
            }) }} تخت
            @if($bedDetailsText)
                (به ترتیب {{ $bedDetailsText }})
            @endif
        </p>
    </section>

    <hr class="home-detail-divider">

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">
            <i class="bi bi-plus-circle"></i>
            هزینه افراد اضافه
        </h3>
        <p class="home-detail-section__body">{{ persianNumber(number_format($home->price_per_surplus)) }} تومان به ازای هر نفر مازاد</p>
    </section>

    <hr class="home-detail-divider">

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">
            <i class="bi bi-calendar-week"></i>
            قیمت روزهای هفته
        </h3>
        <ul class="home-weekday-prices">
            <li>
                <span class="home-weekday-prices__label">@lang('title.week_price')</span>
                <span class="home-weekday-prices__amount">{{ persianNumber(number_format($home->week_price)) }} تومان</span>
            </li>
            <li>
                <span class="home-weekday-prices__label">@lang('title.wed_price')</span>
                <span class="home-weekday-prices__amount">{{ persianNumber(number_format($home->wed_price)) }} تومان</span>
            </li>
            <li>
                <span class="home-weekday-prices__label">@lang('title.thu_price')</span>
                <span class="home-weekday-prices__amount">{{ persianNumber(number_format($home->thu_price)) }} تومان</span>
            </li>
            <li>
                <span class="home-weekday-prices__label">@lang('title.fri_price')</span>
                <span class="home-weekday-prices__amount">{{ persianNumber(number_format($home->fri_price)) }} تومان</span>
            </li>
        </ul>
        <p class="home-weekday-prices__note">
            ممکن است برخی روزها به‌دلیل پیک سفر یا تعطیلات، قیمت متفاوتی داشته باشند. مبلغ دقیق هر شب هنگام انتخاب تاریخ در تقویم رزرو نمایش داده می‌شود.
        </p>
        @if($home->hasLongStayDiscount())
            <p class="home-long-stay-discount">
                <i class="bi bi-tag-fill" aria-hidden="true"></i>
                <span>
                    تخفیف رزرو چندشبه:
                    <span class="home-long-stay-discount__highlight">{{ $home->longStayDiscountLabel() }}</span>
                    (برای اقامت‌های بلندتر از این مدت)
                </span>
            </p>
        @endif
    </section>

    <hr class="home-detail-divider">

    <section id="about" class="home-detail-section">
        <h3 class="home-detail-section__title">درباره اقامتگاه</h3>
        <p class="home-detail-section__body">{{ $home->description }}</p>
    </section>

    <hr class="home-detail-divider">

    <section class="home-detail-section">
        <h3 class="home-detail-section__title">امکانات</h3>
        <div class="d-flex flex-row flex-wrap gap-1">
            @foreach($home->options as $option)
                <span class="badge bg-light text-muted rounded-pill me-1 d-inline-flex align-items-center">
                    <x-option-icon :option="$option" :size="20" />
                    <span class="mx-2">|</span>
                    {{ $option->title }}
                </span>
            @endforeach
        </div>
    </section>

    @if($home->safeties->isNotEmpty() || $home->more_safety)
        <hr class="home-detail-divider">
        <section class="home-detail-section">
            <h3 class="home-detail-section__title">ایمنی اقامتگاه</h3>
            <div class="d-flex flex-wrap gap-2">
                @foreach($home->safeties as $safety)
                    <div class="border rounded p-2 px-3 small text-muted">
                        <span>{{ $safety->title }}</span>
                        @if($safety->pivot->description)
                            <span> ({{ $safety->pivot->description }})</span>
                        @endif
                    </div>
                @endforeach

                @if($home->more_safety)
                    <div class="border rounded p-2 px-3 small text-muted w-100">
                        {!! nl2br(e($home->more_safety)) !!}
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if($home->healths->isNotEmpty() || $home->more_health)
        <hr class="home-detail-divider">
        <section class="home-detail-section">
            <h3 class="home-detail-section__title">
                <i class="bi bi-emoji-smile"></i>
                اقلام بهداشتی
            </h3>
            <div class="d-flex flex-wrap gap-2">
                @foreach($home->healths as $health)
                    <div class="border rounded-pill px-3 py-1 bg-light text-dark small">
                        {{ $health->title }}
                    </div>
                @endforeach
            </div>
            @if($home->more_health)
                <p class="home-detail-section__body mt-3 mb-0">{{ $home->more_health }}</p>
            @endif
        </section>
    @endif

    @if($home->latitude && $home->longitude)
        <hr class="home-detail-divider">

        <section id="home-location-section" class="home-location-map-wrap">
            <h3 class="home-detail-section__title">
                <i class="bi bi-geo-alt"></i>
                محدوده اقامتگاه
            </h3>
            @if($home->province && $home->city)
                <p class="home-location-map-hint mb-2">{{ $home->province->name }}، {{ $home->city->name }}</p>
            @endif
            <p class="home-location-map-hint">محل تقریبی اقامتگاه در محدودهٔ مشخص‌شده روی نقشه نمایش داده می‌شود.</p>
            <div id="homeDetailLocationMap" class="home-location-map" role="img" aria-label="نقشه محدوده اقامتگاه"></div>
        </section>
    @endif

    @if(!empty($home->rules))
        <hr class="home-detail-divider">
        <section id="rules" class="home-detail-section">
            <h3 class="home-detail-section__title">قوانین اقامتگاه</h3>
            <p class="home-detail-section__body">{{ $home->rules }}</p>
        </section>
    @endif

    @include('main.homes.partials.cancel-policy', ['home' => $home, 'layout' => 'mobile'])

    <hr class="home-detail-divider">

    @if($home->hasGuestReviews() && ($ratingsSummary['has_data'] ?? false))
        @include('main.homes.partials.ratings-summary-mobile', ['ratingsSummary' => $ratingsSummary])
    @endif

    <section class="home-detail-section">
        @if(empty($ratingsSummary['has_data']) || ($ratingsSummary['total'] ?? 0) === 0)
            <h3 class="home-detail-section__title">
                <i class="bi bi-chat-dots"></i>
                نظرات کاربران
            </h3>
        @endif

        @if($home->count_comments)
            <div class="comments-scroll mb-3">
                <div class="d-flex gap-3" style="overflow-x: auto; padding-bottom: 10px;">
                    @foreach($home->activeComments->take(3) as $comment)
                        <div class="border rounded p-3" style="min-width: 280px;">
                            <div class="d-flex gap-2 align-items-center mb-2">
                                @if($comment->user)
                                    <img src="{{ $comment->user->avatar_path }}" 
                                         alt="{{ $comment->full_name }}"
                                         class="rounded-circle"
                                         width="40" height="40">
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $comment->full_name }}</h6>
                                    <small class="text-muted">{{ $comment->persianCreatedAt('d F Y') }}</small>
                                </div>
                            </div>
                            <p class="mb-0 text-muted">{{ Str::limit($comment->comment, 100) }}</p>
                        </div>
                    @endforeach
                    <div class="border rounded p-3 d-flex flex-column align-items-center justify-content-center" 
                         style="min-width: 280px; cursor: pointer;"
                         onclick="document.getElementById('all-comments').scrollIntoView({behavior: 'smooth'})">
                        <i class="bi bi-plus-circle fs-1 mb-2"></i>
                        <span>همه نظرات</span>
                    </div>
                </div>
            </div>
        @endif

        @php
            $pendingReviewRent = null;
            if (auth()->check()) {
                $pendingReviewRent = auth()->user()->rents()
                    ->forTripTab(\App\Models\Order::TRIP_TAB_AWAITING_REVIEW, auth()->id())
                    ->where('home_id', $home->id)
                    ->first();
            }
        @endphp

        @if(! auth()->check())
            <div class="alert alert-danger text-center mb-0">
                برای ثبت نظر حتما باید وارد حساب خود شوید!
            </div>
        @elseif(! auth()->user()->isRent($home))
            <div class="alert alert-danger text-center mb-0">
                برای ثبت نظر حتما باید یکبار این ملک را رزرو کرده باشید!
            </div>
        @elseif($pendingReviewRent)
            <a href="{{ route('dashboard.rents.review.create', $pendingReviewRent) }}"
               class="btn btn-mobile-secondary w-100 text-decoration-none"
               style="color: white; border-radius: 12px;">
                ثبت نظر
            </a>
        @endif
    </section>

    @if($home->count_comments)
        @if(empty($ratingsSummary['has_data']) || ($ratingsSummary['total'] ?? 0) === 0)
            <hr class="home-detail-divider">
        @endif
        <section id="all-comments" class="home-detail-section pb-2">
            @if(empty($ratingsSummary['has_data']) || ($ratingsSummary['total'] ?? 0) === 0)
                <h3 class="home-detail-section__title">همه نظرات</h3>
            @endif
            <div class="comments-list">
                @foreach($home->activeComments as $comment)
                    <div class="comment-item mb-4">
                        <div class="d-flex gap-3">
                            @if($comment->user)
                                <img src="{{ $comment->user->avatar_path }}" 
                                     alt="{{ $comment->full_name }}"
                                     class="rounded-circle"
                                     width="40" height="40">
                            @endif
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $comment->full_name }}</h6>
                                    <small class="text-muted">{{ $comment->persianCreatedAt('d F Y') }}</small>
                                </div>
                                <p class="mb-0 text-muted small">{{ $comment->comment }}</p>
                            </div>
                        </div>

                        @foreach($comment->activeChildren as $child)
                            <div class="comment-item ms-4 mt-3">
                                <div class="d-flex gap-3">
                                    @if($child->user)
                                        <img src="{{ $child->user->avatar_path }}" 
                                             alt="{{ $child->full_name }}"
                                             class="rounded-circle"
                                             width="35" height="35">
                                    @endif
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 small">{{ $child->full_name }}</h6>
                                            <small class="text-muted">{{ $child->persianCreatedAt('d F Y') }}</small>
                                        </div>
                                        <p class="mb-0 text-muted small">{{ $child->comment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <hr class="home-detail-divider">

    <!-- Reserve Calendar Section -->
    <section id="reserve-calendar-section" class="home-detail-section reserve-calendar-main reserve-calendar-section--page-end mb-4">
        <h3 class="home-detail-section__title">
            <i class="bi bi-calendar3"></i>
            انتخاب تاریخ رزرو
        </h3>
        <div class="home-reserve-calendar-box">
        <reserve-home
            key="reserve-home-{{ $home->id }}"
            stacked_calendar="true"
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
    </section>

    @include('main.homes.partials.similar-homes', ['layout' => 'mobile'])

</div>

<div class="fixed-flex-box">
    <div class="fixed-flex-box-info">
        @if($home->hasLongStayDiscount())
            <span class="fixed-flex-box-badge">{{ $home->longStayDiscountLabel() }}</span>
        @endif
        <p class="fixed-flex-box-price-line">
            <span class="fixed-flex-box-price-prefix">هر شب از</span>
            <span class="fixed-flex-box-price-amount">{{ $home->minNightlyPriceFormatted() }}</span>
            <span class="fixed-flex-box-price-suffix">@lang('title.toman')</span>
        </p>
    </div>

    <button type="button" class="fixed-flex-box-btn" id="reserveBtn" data-mobile-reserve-trigger>
        درخواست رزرو (رایگان)
    </button>
</div>

@php
    $mobileReserveMaxGuests = (int) $home->main_guest + (int) $home->extra_guest;
    $mobileReserveDefaultGuest = min(max((int) $home->main_guest, 1), 2);
@endphp

<div id="mobileReserveSheet" class="mobile-reserve-sheet" aria-hidden="true">
    <div class="mobile-reserve-sheet__backdrop" data-mobile-reserve-close></div>

    <div class="mobile-reserve-sheet__dock">
        <button type="button" class="mobile-reserve-sheet__close" aria-label="بستن" data-mobile-reserve-close>
            <i class="bi bi-x-lg"></i>
        </button>

        <div class="mobile-reserve-sheet__panel" role="dialog" aria-modal="true" aria-labelledby="mobileReserveSheetTitle">
        <div class="mobile-reserve-sheet__step mobile-reserve-sheet__step--form">
            <div class="mobile-reserve-sheet__body">
            <div class="mobile-reserve-sheet__field">
                <span class="mobile-reserve-sheet__label" id="mobileReserveSheetTitle">تاریخ سفر</span>
                <div class="mobile-reserve-sheet__date-split">
                    <button type="button" class="mobile-reserve-sheet__date-btn is-placeholder" id="mobileReserveStartBtn" data-date-focus="start">
                        @lang('title.date_enter')
                    </button>
                    <span class="mobile-reserve-sheet__date-divider" aria-hidden="true"></span>
                    <button type="button" class="mobile-reserve-sheet__date-btn is-placeholder" id="mobileReserveEndBtn" data-date-focus="end">
                        @lang('title.date_quit')
                    </button>
                </div>
            </div>

            <div class="mobile-reserve-sheet__field">
                <div class="mobile-reserve-sheet__guest-row">
                    <div class="mobile-reserve-sheet__guest-info">
                        <div class="mobile-reserve-sheet__guest-title">
                            <i class="bi bi-people" aria-hidden="true"></i>
                            <span>تعداد مسافران</span>
                        </div>
                        <p class="mobile-reserve-sheet__guest-breakdown" id="mobileReserveGuestBreakdown"></p>
                    </div>
                    <div class="mobile-reserve-sheet__guest-counter">
                        <button type="button" class="mobile-reserve-sheet__guest-btn" id="mobileReserveGuestMinus" aria-label="کم کردن">−</button>
                        <span class="mobile-reserve-sheet__guest-count" id="mobileReserveGuestCount">{{ persianNumber($mobileReserveDefaultGuest) }}</span>
                        <button type="button" class="mobile-reserve-sheet__guest-btn" id="mobileReserveGuestPlus" aria-label="زیاد کردن">+</button>
                    </div>
                </div>
            </div>

            <div class="mobile-reserve-sheet__info-banner">
                <i class="bi bi-info-circle" aria-hidden="true"></i>
                <span>کودک زیر دو سال جزو نفرات حساب نمی‌شود.</span>
            </div>

            <div id="mobileReservePayment" class="mobile-reserve-payment" hidden>
                <div class="mobile-reserve-payment__head">
                    <h4 class="mobile-reserve-payment__title">خلاصه پرداخت</h4>
                    <span class="mobile-reserve-payment__badge" id="mobileReserveCapacityBadge"></span>
                </div>
                <div id="mobileReserveNightBreakdown" class="mobile-reserve-payment__breakdown"></div>
                <div class="mobile-reserve-payment__row mobile-reserve-payment__row--rent-total">
                    <span id="mobileReserveRentLabel">مجموع اجاره‌بها</span>
                    <span id="mobileReserveRentAmount">—</span>
                </div>
                <div class="mobile-reserve-payment__row" id="mobileReserveExtraRow" hidden>
                    <span>هزینه نفرات اضافه</span>
                    <span id="mobileReserveExtraAmount">—</span>
                </div>
                <div class="mobile-reserve-payment__row mobile-reserve-payment__row--discount" id="mobileReserveDiscountRow" hidden>
                    <span>تخفیف رزرو چند روزه (میزبان)</span>
                    <span id="mobileReserveDiscountAmount">—</span>
                </div>
                <div class="mobile-reserve-payment__total">
                    <span>مبلغ قابل پرداخت</span>
                    <strong id="mobileReserveTotalAmount">—</strong>
                </div>
            </div>
            </div>

            <div class="mobile-reserve-sheet__footer">
                <button type="button" class="mobile-reserve-sheet__submit" id="mobileReserveSubmit">
                    ثبت درخواست رزرو (رایگان)
                </button>
            </div>
        </div>

        <div class="mobile-reserve-sheet__step mobile-reserve-sheet__step--calendar">
            <div class="mobile-reserve-sheet__calendar-header">
                <button type="button" class="mobile-reserve-sheet__calendar-back" id="mobileReserveCalendarBack" aria-label="بازگشت">
                    <i class="bi bi-arrow-right"></i>
                </button>
                <span class="mobile-reserve-sheet__calendar-title">انتخاب تاریخ</span>
            </div>
            <div class="mobile-reserve-sheet__calendar-mount" id="mobileReserveCalendarMount"></div>
            <div class="mobile-reserve-sheet__footer">
                <button type="button" class="mobile-reserve-sheet__submit" id="mobileReserveCalendarDone">
                    تایید تاریخ
                </button>
            </div>
        </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/swiper/swiper-bundle.min.js') }}"></script>
@if($home->latitude && $home->longitude)
<script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
@endif

<script>
// JavaScript functions
function favoriteApiPath(homeId) {
    var base = (document.body && document.body.getAttribute('data-app-base')) || '';
    base = base.replace(/\/$/, '');
    return base + '/homes/' + encodeURIComponent(homeId) + '/favorite';
}

function isFavoriteResponseOk(data) {
    return data === true || data === 1 || data === '1';
}

function setFavoriteButtonState(favoriteBtn, isFavorited) {
    if (!favoriteBtn) {
        return;
    }
    favoriteBtn.classList.toggle('favorited', isFavorited);
    favoriteBtn.setAttribute('data-is-favorite', isFavorited ? '1' : '0');
    favoriteBtn.setAttribute('aria-pressed', isFavorited ? 'true' : 'false');
    favoriteBtn.setAttribute('aria-label', isFavorited ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها');
    favoriteBtn.classList.remove('bi-heart', 'bi-heart-fill');
    favoriteBtn.classList.add(isFavorited ? 'bi-heart-fill' : 'bi-heart');
}

function readFavoriteButtonState(favoriteBtn) {
    if (!favoriteBtn) {
        return false;
    }
    return favoriteBtn.getAttribute('data-is-favorite') === '1'
        || favoriteBtn.classList.contains('favorited');
}

function toggleFavorite(homeId) {
    const favoriteBtn = document.getElementById('favoriteBtn');
    if (!favoriteBtn || favoriteBtn.classList.contains('is-loading')) {
        return;
    }

    const isFavorited = readFavoriteButtonState(favoriteBtn);
    const method = isFavorited ? 'DELETE' : 'POST';
    const csrf = document.querySelector('meta[name="csrf-token"]');
    const token = csrf ? csrf.getAttribute('content') : '';

    favoriteBtn.classList.add('is-loading');

    fetch(favoriteApiPath(homeId), {
        method: method,
        headers: {
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        credentials: 'same-origin',
    })
        .then(function (response) {
            if (response.status === 401 || response.status === 403) {
                throw new Error('login');
            }
            if (response.status === 419) {
                throw new Error('csrf');
            }
            return response.json().then(function (data) {
                if (!response.ok) {
                    throw new Error('request');
                }
                return data;
            });
        })
        .then(function (data) {
            if (!isFavoriteResponseOk(data)) {
                throw new Error('response');
            }
            const next = !isFavorited;
            setFavoriteButtonState(favoriteBtn, next);
            showToast(
                next ? 'به علاقه‌مندی‌ها اضافه شد' : 'از علاقه‌مندی‌ها حذف شد',
                'success'
            );
        })
        .catch(function (error) {
            console.error('Favorite error:', error);
            if (error && error.message === 'csrf') {
                showToast('نشست منقضی شده. صفحه را رفرش کنید.', 'error');
            } else {
                showToast('خطا در ثبت علاقه‌مندی', 'error');
            }
        })
        .finally(function () {
            favoriteBtn.classList.remove('is-loading');
        });
}

function showToast(message, type) {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#dc3545'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        font-size: 14px;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        document.body.removeChild(toast);
    }, 3000);
}

function shareHome() {
    if (navigator.share) {
        navigator.share({
            title: {!! json_encode($home->name) !!},
            text: {!! json_encode(Str::limit($home->description, 100)) !!},
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            showToast('لینک کپی شد', 'success');
        });
    }
}

function copyHomeCode() {
    const code = {!! json_encode((string) $home->code) !!};
    if (!code) return;

    const onCopied = () => showToast('کد اقامتگاه کپی شد', 'success');

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(code).then(onCopied).catch(() => {
            showToast('کپی ناموفق بود', 'error');
        });
        return;
    }

    const input = document.createElement('textarea');
    input.value = code;
    input.setAttribute('readonly', '');
    input.style.position = 'absolute';
    input.style.left = '-9999px';
    document.body.appendChild(input);
    input.select();
    try {
        document.execCommand('copy');
        onCopied();
    } catch (e) {
        showToast('کپی ناموفق بود', 'error');
    }
    document.body.removeChild(input);
}

function toPersianDigits(value) {
    return String(value).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]);
}

function updateHeroImageCounter(activeIndex, total) {
    const counter = document.getElementById('heroImageCounter');
    if (!counter || total < 1) return;
    counter.textContent = toPersianDigits(activeIndex + 1) + ' از ' + toPersianDigits(total);
}

function initHomeGalleryLightbox() {
    const swiperEl = document.getElementById('homeImageSwiper');
    if (!swiperEl) return;

    const galleryLinks = swiperEl.querySelectorAll('.swiper-slide:not(.swiper-slide-duplicate) a.mfp-gallery');
    if (!galleryLinks.length) return;

    const items = Array.from(galleryLinks).map(function (link) {
        return {
            src: link.getAttribute('href'),
            type: 'image',
        };
    });

    swiperEl.addEventListener('click', function (e) {
        const link = e.target.closest('a.mfp-gallery');
        if (!link || !swiperEl.contains(link)) return;

        e.preventDefault();
        e.stopPropagation();

        if (typeof jQuery === 'undefined' || !jQuery.magnificPopup) {
            window.open(link.getAttribute('href'), '_blank');
            return;
        }

        const index = parseInt(link.getAttribute('data-gallery-index') || '0', 10);

        jQuery.magnificPopup.open({
            items: items,
            type: 'image',
            gallery: {
                enabled: items.length > 1,
            },
            mainClass: 'mfp-fade mfp-img-mobile',
            fixedContentPos: true,
            fixedBgPos: true,
            closeBtnInside: false,
            preloader: true,
            removalDelay: 0,
            callbacks: {
                open: function () {
                    document.body.classList.add('home-gallery-lightbox-open');
                },
                close: function () {
                    document.body.classList.remove('home-gallery-lightbox-open');
                },
            },
        }, index);
    });
}

(function () {
    const sheet = document.getElementById('mobileReserveSheet');
    if (!sheet) return;

    const startBtn = document.getElementById('mobileReserveStartBtn');
    const endBtn = document.getElementById('mobileReserveEndBtn');
    const guestMinusBtn = document.getElementById('mobileReserveGuestMinus');
    const guestPlusBtn = document.getElementById('mobileReserveGuestPlus');
    const guestCountEl = document.getElementById('mobileReserveGuestCount');
    const guestBreakdownEl = document.getElementById('mobileReserveGuestBreakdown');
    const paymentBox = document.getElementById('mobileReservePayment');
    const capacityBadgeEl = document.getElementById('mobileReserveCapacityBadge');
    const nightBreakdownEl = document.getElementById('mobileReserveNightBreakdown');
    const rentLabelEl = document.getElementById('mobileReserveRentLabel');
    const rentAmountEl = document.getElementById('mobileReserveRentAmount');
    const extraRowEl = document.getElementById('mobileReserveExtraRow');
    const extraAmountEl = document.getElementById('mobileReserveExtraAmount');
    const discountRowEl = document.getElementById('mobileReserveDiscountRow');
    const discountAmountEl = document.getElementById('mobileReserveDiscountAmount');
    const totalAmountEl = document.getElementById('mobileReserveTotalAmount');
    const submitBtn = document.getElementById('mobileReserveSubmit');
    const calendarBackBtn = document.getElementById('mobileReserveCalendarBack');
    const calendarDoneBtn = document.getElementById('mobileReserveCalendarDone');
    const calendarMount = document.getElementById('mobileReserveCalendarMount');

    const reserveConfig = {
        mainGuest: {{ (int) $home->main_guest }},
        maxGuests: {{ $mobileReserveMaxGuests }},
        defaultGuest: {{ $mobileReserveDefaultGuest }},
    };

    const labels = {
        start: @json(__('title.date_enter')),
        end: @json(__('title.date_quit')),
    };

    let guestCount = reserveConfig.defaultGuest;
    let calendarHome = null;
    let calendarMoved = false;
    let dateSyncTimer = null;

    function findVueComponent(vm, name) {
        if (!vm) return null;
        const tag = vm.$options && vm.$options.name;
        const vnodeTag = vm.$vnode && vm.$vnode.componentOptions
            ? vm.$vnode.componentOptions.tag
            : null;
        if (tag === name || vnodeTag === 'reserve-home') {
            return vm;
        }
        if (vm.$children && vm.$children.length) {
            for (let i = 0; i < vm.$children.length; i++) {
                const found = findVueComponent(vm.$children[i], name);
                if (found) return found;
            }
        }
        return null;
    }

    function getReserveVue() {
        const el = document.querySelector('#reserve-calendar-section reserve-home')
            || document.querySelector('reserve-home');
        if (el && el.__vue__) {
            return el.__vue__;
        }
        const appEl = document.getElementById('app');
        if (appEl && appEl.__vue__) {
            return findVueComponent(appEl.__vue__, 'ReserveHome');
        }
        return null;
    }

    function bindReserveVueEvents() {
        const vue = getReserveVue();
        if (!vue || vue._mobileReserveBound) {
            return !!vue;
        }
        vue._mobileReserveBound = true;
        vue.$root.$on('reserve-dates-updated', function () {
            updateDateButtons();
            updatePaymentSummary();
        });
        return true;
    }

    function ensureReservePricing(vue) {
        if (!vue || !vue.start_date || !vue.end_date) {
            return false;
        }

        if (!Array.isArray(vue.dates) || vue.dates.length === 0) {
            if (typeof vue.handleEndDateChange === 'function') {
                vue.handleEndDateChange(vue.end_date);
            } else if (vue.$root && typeof vue.$root.datePeriod === 'function') {
                vue.dates = vue.$root.datePeriod(vue.start_date, vue.end_date);
            }
        }

        if (typeof vue.calcTotal === 'function') {
            vue.calcTotal();
        }

        return Array.isArray(vue.dates) && vue.dates.length > 0;
    }

    function formatFaDate(isoDate) {
        if (!isoDate) return '';
        const date = new Date(isoDate);
        if (Number.isNaN(date.getTime())) return '';
        return date.toLocaleDateString('fa-IR', { day: '2-digit', month: 'long' });
    }

    function readDatesFromForm() {
        const startInput = document.querySelector('form[action*="reserve"] input[name="start_date"]');
        const endInput = document.querySelector('form[action*="reserve"] input[name="end_date"]');
        return {
            start: startInput ? startInput.value : '',
            end: endInput ? endInput.value : '',
        };
    }

    function getSelectedDates() {
        const vue = getReserveVue();
        if (vue) {
            return { start: vue.start_date || '', end: vue.end_date || '' };
        }
        return readDatesFromForm();
    }

    function persianDigit(value) {
        const digits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return String(value).replace(/\d/g, function (d) {
            return digits[parseInt(d, 10)];
        });
    }

    function formatPrice(amount) {
        const vue = getReserveVue();
        const num = parseInt(amount, 10) || 0;
        let formatted;
        if (vue && vue.$root && typeof vue.$root.formatNumber === 'function') {
            formatted = vue.$root.formatNumber(num);
        } else {
            formatted = num.toLocaleString('en-US');
        }
        return persianDigit(formatted);
    }

    function syncGuestToVue() {
        const vue = getReserveVue();
        if (!vue) return;
        vue.guest = guestCount;
        if (typeof vue.calcTotal === 'function') {
            vue.calcTotal();
        }
    }

    function setGuestCount(count) {
        guestCount = Math.max(1, Math.min(reserveConfig.maxGuests, count));
        syncGuestToVue();
        updateGuestUI();
        updatePaymentSummary();
    }

    function updateGuestUI() {
        if (guestCountEl) {
            guestCountEl.textContent = persianDigit(guestCount);
        }
        if (guestMinusBtn) {
            guestMinusBtn.disabled = guestCount <= 1;
        }
        if (guestPlusBtn) {
            guestPlusBtn.disabled = guestCount >= reserveConfig.maxGuests;
        }
        if (guestBreakdownEl) {
            const extra = Math.max(0, guestCount - reserveConfig.mainGuest);
            if (extra > 0) {
                guestBreakdownEl.textContent = persianDigit(reserveConfig.mainGuest) + ' نفر پایه + ' + persianDigit(extra) + ' نفر اضافه';
            } else if (guestCount === reserveConfig.mainGuest) {
                guestBreakdownEl.textContent = persianDigit(guestCount) + ' نفر پایه';
            } else {
                guestBreakdownEl.textContent = persianDigit(guestCount) + ' نفر';
            }
        }
    }

    function buildNightPriceGroups(vue) {
        const map = new Map();
        vue.dates.forEach(function (date) {
            const d = date instanceof Date ? new Date(date.getTime()) : new Date(date);
            if (typeof vue.getPrice !== 'function') {
                return;
            }
            const price = parseInt(vue.getPrice(d, false), 10) || 0;
            map.set(price, (map.get(price) || 0) + 1);
        });
        return Array.from(map.entries())
            .map(function (entry) {
                return {
                    price: entry[0],
                    nights: entry[1],
                    subtotal: entry[0] * entry[1],
                };
            })
            .sort(function (a, b) {
                return b.nights - a.nights || b.price - a.price;
            });
    }

    function renderNightBreakdown(vue) {
        if (!nightBreakdownEl) {
            return 0;
        }
        const groups = buildNightPriceGroups(vue);
        nightBreakdownEl.innerHTML = '';
        let rentTotal = 0;

        groups.forEach(function (group) {
            rentTotal += group.subtotal;
            const row = document.createElement('div');
            row.className = 'mobile-reserve-payment__breakdown-row';

            const formula = document.createElement('span');
            formula.className = 'mobile-reserve-payment__breakdown-formula';
            formula.textContent = persianDigit(group.nights) + ' شب × ' + formatPrice(group.price) + ' تومان';

            const amount = document.createElement('span');
            amount.className = 'mobile-reserve-payment__breakdown-amount';
            amount.textContent = formatPrice(group.subtotal) + ' تومان';

            row.appendChild(formula);
            row.appendChild(amount);
            nightBreakdownEl.appendChild(row);
        });

        return rentTotal;
    }

    function syncSheetLayout() {
        if (!sheet) return;
        const paymentVisible = paymentBox && !paymentBox.hidden;
        sheet.classList.toggle('has-payment', !!paymentVisible);
    }

    function updatePaymentSummary() {
        if (!paymentBox) return;

        bindReserveVueEvents();

        const dates = getSelectedDates();
        if (!dates.start || !dates.end) {
            paymentBox.hidden = true;
            paymentBox.setAttribute('hidden', 'hidden');
            syncSheetLayout();
            return;
        }

        const vue = getReserveVue();
        if (!vue || !ensureReservePricing(vue)) {
            paymentBox.hidden = true;
            paymentBox.setAttribute('hidden', 'hidden');
            syncSheetLayout();
            return;
        }

        syncGuestToVue();

        const nights = vue.dates.length;
        const extraPrice = parseInt(vue.extra_guest_price, 10) || 0;
        const discount = parseInt(vue.daily_off_price, 10) || 0;
        const total = parseInt(vue.total, 10) || 0;
        const rentTotal = renderNightBreakdown(vue);

        paymentBox.hidden = false;
        paymentBox.removeAttribute('hidden');

        if (capacityBadgeEl) {
            capacityBadgeEl.textContent = 'برای ' + persianDigit(reserveConfig.mainGuest) + ' نفر ظرفیت پایه';
        }
        if (rentLabelEl) {
            rentLabelEl.textContent = 'مجموع اجاره‌بها — ' + persianDigit(nights) + ' شب';
        }
        if (rentAmountEl) {
            rentAmountEl.textContent = formatPrice(rentTotal) + ' تومان';
        }
        if (extraRowEl && extraAmountEl) {
            if (extraPrice > 0) {
                extraRowEl.hidden = false;
                extraAmountEl.textContent = formatPrice(extraPrice) + ' تومان';
            } else {
                extraRowEl.hidden = true;
            }
        }
        if (discountRowEl && discountAmountEl) {
            if (discount > 0) {
                discountRowEl.hidden = false;
                discountAmountEl.textContent = '− ' + formatPrice(discount) + ' تومان';
            } else {
                discountRowEl.hidden = true;
            }
        }
        if (totalAmountEl) {
            totalAmountEl.textContent = formatPrice(total) + ' تومان';
        }

        syncSheetLayout();
    }

    function updateDateButtons() {
        const dates = getSelectedDates();

        if (startBtn) {
            const hasStart = !!dates.start;
            startBtn.textContent = hasStart ? formatFaDate(dates.start) : labels.start;
            startBtn.classList.toggle('is-placeholder', !hasStart);
        }

        if (endBtn) {
            const hasEnd = !!dates.end;
            endBtn.textContent = hasEnd ? formatFaDate(dates.end) : labels.end;
            endBtn.classList.toggle('is-placeholder', !hasEnd);
        }

        updatePaymentSummary();
    }

    function moveCalendarIntoSheet() {
        if (calendarMoved || !calendarMount) return;

        const calendarEl = document.getElementById('main-calendar')
            || document.querySelector('#reserve-calendar-section .persian-calendar');

        if (!calendarEl) return;

        calendarHome = {
            parent: calendarEl.parentNode,
            nextSibling: calendarEl.nextSibling,
        };

        calendarMount.appendChild(calendarEl);
        calendarMoved = true;
    }

    function restoreCalendarFromSheet() {
        if (!calendarMoved || !calendarHome || !calendarHome.parent) return;

        const calendarEl = document.getElementById('main-calendar')
            || calendarMount.querySelector('.persian-calendar');

        if (!calendarEl) return;

        if (calendarHome.nextSibling) {
            calendarHome.parent.insertBefore(calendarEl, calendarHome.nextSibling);
        } else {
            calendarHome.parent.appendChild(calendarEl);
        }

        calendarMoved = false;
        calendarHome = null;
    }

    function showFormStep() {
        sheet.classList.remove('is-calendar-step');
        restoreCalendarFromSheet();
        bindReserveVueEvents();
        const vue = getReserveVue();
        if (vue && vue.start_date && vue.end_date) {
            ensureReservePricing(vue);
        }
        updateDateButtons();
        updateGuestUI();
        updatePaymentSummary();
    }

    function showCalendarStep(focus) {
        moveCalendarIntoSheet();
        sheet.classList.add('is-calendar-step');

        if (startBtn && endBtn) {
            startBtn.classList.remove('is-active');
            endBtn.classList.remove('is-active');
            if (focus === 'end') {
                endBtn.classList.add('is-active');
            } else {
                startBtn.classList.add('is-active');
            }
        }
    }

    function startDateSync() {
        stopDateSync();
        bindReserveVueEvents();
        dateSyncTimer = window.setInterval(function () {
            bindReserveVueEvents();
            updateDateButtons();
        }, 400);
    }

    function stopDateSync() {
        if (dateSyncTimer) {
            window.clearInterval(dateSyncTimer);
            dateSyncTimer = null;
        }
    }

    function finishCloseMobileReserveSheet() {
        sheet.classList.remove('is-closing', 'is-calendar-step', 'has-payment');
        sheet.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('mobile-reserve-sheet-open');
        stopDateSync();
        restoreCalendarFromSheet();
        if (startBtn) startBtn.classList.remove('is-active');
        if (endBtn) endBtn.classList.remove('is-active');
    }

    function openMobileReserveSheet() {
        const vueOnOpen = getReserveVue();
        if (vueOnOpen && vueOnOpen.guest) {
            guestCount = parseInt(vueOnOpen.guest, 10) || reserveConfig.defaultGuest;
        } else {
            guestCount = reserveConfig.defaultGuest;
        }
        updateGuestUI();
        syncGuestToVue();
        updateDateButtons();
        showFormStep();

        sheet.classList.remove('is-closing');
        sheet.setAttribute('aria-hidden', 'false');
        document.body.classList.add('mobile-reserve-sheet-open');

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                sheet.classList.add('is-open');
            });
        });

        startDateSync();
    }

    function closeMobileReserveSheet() {
        if (!sheet.classList.contains('is-open') && !sheet.classList.contains('is-closing')) {
            return;
        }

        sheet.classList.remove('is-open');
        sheet.classList.add('is-closing');

        const dock = sheet.querySelector('.mobile-reserve-sheet__dock');
        let closed = false;

        function completeClose() {
            if (closed) return;
            closed = true;
            if (dock) {
                dock.removeEventListener('transitionend', onDockTransitionEnd);
            }
            finishCloseMobileReserveSheet();
        }

        function onDockTransitionEnd(event) {
            if (event.target !== dock || event.propertyName !== 'transform') {
                return;
            }
            completeClose();
        }

        if (dock) {
            dock.addEventListener('transitionend', onDockTransitionEnd);
        }

        window.setTimeout(completeClose, 520);
    }

    function submitMobileReserveSheet() {
        syncGuestToVue();
        const dates = getSelectedDates();

        if (!dates.start || !dates.end) {
            showCalendarStep('start');
            showToast('لطفاً تاریخ ورود و خروج را انتخاب کنید', 'error');
            return;
        }

        const vue = getReserveVue();
        closeMobileReserveSheet();

        if (vue && typeof vue.submitReserve === 'function') {
            vue.submitReserve();
            return;
        }

        const form = document.querySelector('form[action*="reserve"]');
        if (form) {
            form.submit();
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('[data-mobile-reserve-trigger]')) return;
        e.preventDefault();
        e.stopImmediatePropagation();
        openMobileReserveSheet();
    }, true);

    sheet.querySelectorAll('[data-mobile-reserve-close]').forEach(function (el) {
        el.addEventListener('click', closeMobileReserveSheet);
    });

    if (startBtn) {
        startBtn.addEventListener('click', function () {
            showCalendarStep('start');
        });
    }

    if (endBtn) {
        endBtn.addEventListener('click', function () {
            showCalendarStep('end');
        });
    }

    if (calendarBackBtn) {
        calendarBackBtn.addEventListener('click', showFormStep);
    }

    if (calendarDoneBtn) {
        calendarDoneBtn.addEventListener('click', function () {
            const vue = getReserveVue();
            const dates = getSelectedDates();
            if (!dates.start || !dates.end) {
                showToast('لطفاً تاریخ ورود و خروج را انتخاب کنید', 'error');
                return;
            }
            if (vue) {
                ensureReservePricing(vue);
            }
            showFormStep();
        });
    }

    bindReserveVueEvents();

    if (guestMinusBtn) {
        guestMinusBtn.addEventListener('click', function () {
            setGuestCount(guestCount - 1);
        });
    }

    if (guestPlusBtn) {
        guestPlusBtn.addEventListener('click', function () {
            setGuestCount(guestCount + 1);
        });
    }

    updateGuestUI();

    if (submitBtn) {
        submitBtn.addEventListener('click', submitMobileReserveSheet);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sheet.classList.contains('is-open')) {
            closeMobileReserveSheet();
        }
    });

    window.openMobileReserveSheet = openMobileReserveSheet;
    window.closeMobileReserveSheet = closeMobileReserveSheet;
})();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Wait for Swiper to be available
    if (typeof Swiper === 'undefined') {
        console.error('Swiper library is not loaded');
        return;
    }
    
    // Initialize Swiper slider
    const swiperContainer = document.getElementById('homeImageSwiper');
    if (swiperContainer) {
        const slides = swiperContainer.querySelectorAll('.swiper-slide');
        const slidesCount = slides.length;
        const shouldLoop = slidesCount > 1;
        
        // Hide navigation buttons if only one slide
        if (slidesCount <= 1) {
            const navButtons = document.querySelectorAll('.slider-nav-btn');
            navButtons.forEach(btn => btn.style.display = 'none');
            const pagination = document.getElementById('sliderPagination');
            if (pagination) pagination.style.display = 'none';
        }
        
        const heroMediaTotal = slidesCount;

        // Function to update pagination dots and image counter
        function updatePagination(activeIndex) {
            const dots = document.querySelectorAll('.slider-pagination span');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === activeIndex);
            });
            updateHeroImageCounter(activeIndex, heroMediaTotal);
        }
        
        // Function to setup pagination click handlers
        function setupPaginationClicks(swiperInstance) {
            const dots = document.querySelectorAll('.slider-pagination span');
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    if (shouldLoop) {
                        swiperInstance.slideToLoop(index);
                    } else {
                        swiperInstance.slideTo(index);
                    }
                });
            });
        }
        
        initHomeGalleryLightbox();

        const swiper = new Swiper('#homeImageSwiper', {
            loop: shouldLoop,
            slidesPerView: 1,
            spaceBetween: 0,
            effect: 'slide',
            speed: 400,
            allowTouchMove: shouldLoop,
            grabCursor: shouldLoop,
            preventClicks: false,
            preventClicksPropagation: false,
            touchRatio: 1,
            touchAngle: 45,
            simulateTouch: true,
            followFinger: true,
            touchStartPreventDefault: false,
            touchMoveStopPropagation: false,
            keyboard: {
                enabled: shouldLoop,
            },
            navigation: shouldLoop ? {
                nextEl: '#sliderNext',
                prevEl: '#sliderPrev',
            } : false,
            on: {
                init: function() {
                    updateHeroImageCounter(0, heroMediaTotal);
                    if (slidesCount > 1) {
                        updatePagination(0);
                        setupPaginationClicks(this);
                    }
                },
                slideChange: function() {
                    if (slidesCount > 1) {
                        updatePagination(this.realIndex);
                    }
                },
            }
        });
    }

    // Setup favorite button — وضعیت اولیه از سرور (data-is-favorite)
    const favoriteBtn = document.getElementById('favoriteBtn');
    if (favoriteBtn) {
        const homeId = favoriteBtn.getAttribute('data-home-id');
        setFavoriteButtonState(favoriteBtn, readFavoriteButtonState(favoriteBtn));
        favoriteBtn.addEventListener('click', function() {
            toggleFavorite(homeId);
        });
    }

    @if($home->latitude && $home->longitude)
    initHomeDetailLocationMap();
    @endif
});

@if($home->latitude && $home->longitude)
function initHomeDetailLocationMap() {
    const mapEl = document.getElementById('homeDetailLocationMap');
    if (!mapEl || typeof L === 'undefined') {
        return;
    }

    const lat = {{ (float) $home->latitude }};
    const lng = {{ (float) $home->longitude }};
    const radius = 750;

    const map = L.map(mapEl, {
        zoomControl: true,
        attributionControl: true,
        scrollWheelZoom: false,
    }).setView([lat, lng], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);

    L.circle([lat, lng], {
        radius: radius,
        color: '#D39D1A',
        fillColor: '#D39D1A',
        fillOpacity: 0.22,
        weight: 2,
    }).addTo(map);

    const fitBounds = () => {
        map.invalidateSize({ pan: false });
        const bounds = L.circle([lat, lng], { radius: radius }).getBounds();
        map.fitBounds(bounds, { padding: [24, 24], maxZoom: 14 });
    };

    fitBounds();
    setTimeout(fitBounds, 200);
    setTimeout(fitBounds, 800);

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    fitBounds();
                }
            });
        }, { threshold: 0.15 });
        observer.observe(mapEl);
    }
}
@endif
</script>
<link rel="stylesheet" href="{{ asset('assets/css/datepicker-custom.css') }}">
@endsection
