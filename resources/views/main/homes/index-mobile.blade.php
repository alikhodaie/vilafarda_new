@extends('layouts.main.main_mobile')

@section('meta')
    @include('main.homes.partials.homes-seo-pagination-meta')
@endsection

<link href="{{ asset('assets/css/map-travel-sheet.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/mobile-price-range.css') }}" rel="stylesheet">

<style>
.modal-backdrop {
    z-index: 9998 !important;
}
.modal {
    z-index: 9999 !important;
}

/* نقشه تمام‌صفحه — زیر مودال‌های فیلتر */
.map-explorer-modal {
    z-index: 10000 !important;
}

/* فیلترها هنگام باز بودن نقشه — روی نقشه */
.modal.modal-above-map-explorer {
    z-index: 10060 !important;
}

body.map-filter-modal-open .modal-backdrop:last-of-type {
    z-index: 10059 !important;
}

/* استایل‌های کارت‌های اقامتگاه‌های نزدیک */
.home-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.home-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.home-card:active {
    transform: translateY(-2px);
}

/* استایل برای overlay نام روی عکس */
.home-card .position-relative::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 80px;
    background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 60%, transparent 100%);
    pointer-events: none;
    border-radius: 0 0 16px 16px;
}

/* استایل دکمه مشاهده */
.home-card a.btn {
    transition: all 0.2s ease;
    pointer-events: auto;
}

.home-card a.btn:hover {
    background: #B8860B !important;
    transform: scale(1.05);
}

/* استایل overlay فاصله */
.home-card .position-absolute[style*="top: 12px"] {
    z-index: 10;
}

/* دکمه ثابت نقشه — بالای نوار پایین */
.homes-mobile-map-fab {
    position: fixed;
    bottom: calc(5.25rem + env(safe-area-inset-bottom, 0px));
    left: 50%;
    transform: translateX(-50%);
    z-index: 1056;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    color: #fff;
    background: #000;
    border: none;
    border-radius: 20px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.22);
    white-space: nowrap;
}

.homes-mobile-map-fab .bi {
    font-size: 14px;
}

.homes-mobile-map-fab:active {
    transform: translateX(-50%) scale(0.97);
}

.homes-mobile-content-bottom-space {
    padding-bottom: calc(7.5rem + env(safe-area-inset-bottom, 0px)) !important;
}

/* نقشه تمام‌صفحه — مشابه مرجع */
.map-explorer-modal .modal-content {
    border: none;
    border-radius: 0;
    background: #f5f5f5;
}

.map-explorer-shell {
    position: relative;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    overflow: hidden;
}

#mapExplorerMap {
    width: 100%;
    height: 100%;
    z-index: 1;
}

.map-explorer-top {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1002;
    padding: 12px 12px 0;
    pointer-events: none;
}

.map-explorer-top > * {
    pointer-events: auto;
}

.map-explorer-top-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.map-explorer-back {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #1a1a1a;
}

.map-explorer-filters {
    flex: 1;
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.map-explorer-filters::-webkit-scrollbar {
    display: none;
}

.map-explorer-filter-pill {
    flex-shrink: 0;
    border: none;
    background: #fff;
    color: #1a1a1a;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 11px;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    line-height: 1.3;
}

.map-explorer-filter-pill .bi {
    font-size: 12px;
}

.map-explorer-filter-pill.is-active {
    background: #1a1a1a;
    color: #fff;
    font-size: 10px;
    padding: 7px 10px;
}

.map-explorer-filter-pill.is-active .bi {
    font-size: 11px;
}

.map-explorer-hint {
    margin-top: 10px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 12px;
    color: #555;
    line-height: 1.5;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: flex-start;
    gap: 8px;
}

.map-explorer-hint.is-hidden {
    display: none;
}

.map-explorer-hint-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #D39D1A;
    margin-top: 5px;
    flex-shrink: 0;
}

.map-explorer-controls {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 1002;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.map-explorer-control-btn {
    width: 42px;
    height: 42px;
    border: none;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #1a1a1a;
    font-size: 18px;
    font-weight: 700;
}

.map-explorer-preview {
    position: absolute;
    left: 12px;
    right: 12px;
    bottom: calc(88px + env(safe-area-inset-bottom, 0px));
    z-index: 1003;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.18);
    padding: 12px;
}

.map-explorer-preview-close {
    position: absolute;
    top: 10px;
    left: 10px;
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 50%;
    background: #f0f0f0;
    color: #666;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.map-explorer-preview-link {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    text-decoration: none;
    color: inherit;
    padding-top: 4px;
}

.map-explorer-preview-thumb {
    width: 88px;
    height: 88px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
}

.map-explorer-preview-body {
    flex: 1;
    min-width: 0;
    padding-left: 28px;
}

.map-explorer-preview-title {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.4;
    margin: 0 0 6px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.map-explorer-preview-meta {
    font-size: 12px;
    color: #666;
    margin: 0 0 8px;
    line-height: 1.5;
}

.map-explorer-preview-price {
    font-size: 13px;
    color: #1a1a1a;
    margin: 0;
    font-weight: 600;
}

.map-explorer-preview-badge {
    display: inline-block;
    margin-top: 6px;
    background: #f0f0f0;
    color: #666;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 8px;
}

.map-explorer-footer {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1002;
    padding: 0 16px calc(16px + env(safe-area-inset-bottom, 0px));
    text-align: center;
    pointer-events: none;
}

.map-explorer-footer > * {
    pointer-events: auto;
}

.map-explorer-results-btn {
    width: 100%;
    max-width: 280px;
    margin: 0 auto 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    background: #2b2b2b;
    color: #fff;
    border-radius: 999px;
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 600;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
}

.map-explorer-results-summary {
    margin: 0;
    font-size: 12px;
    color: #333;
    text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
}

.map-price-marker {
    background: transparent !important;
    border: none !important;
}

.map-price-bubble {
    background: #fff;
    color: #1a1a1a;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    border: 1px solid #e8e8e8;
    text-align: center;
}

.custom-home-marker,
.custom-user-marker {
    background: transparent !important;
    border: none !important;
}

/* Dark map tiles */
.map-tiles {
    filter: grayscale(100%) invert(100%) contrast(90%);
}

/* Custom Popup Styles */
.leaflet-popup-content-wrapper {
    border-radius: 12px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15) !important;
    padding: 0 !important;
}

.leaflet-popup-content {
    margin: 0 !important;
    padding: 12px !important;
    direction: rtl !important;
    text-align: right !important;
}

.leaflet-popup-tip {
    background: white !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
}

.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 12px !important;
    padding: 0 !important;
}

.custom-popup .leaflet-popup-content {
    margin: 0 !important;
    padding: 12px !important;
}

.custom-popup {
    cursor: pointer;
}

.custom-popup .popup-home-content:hover {
    opacity: 0.95;
}

.custom-popup .leaflet-popup-content-wrapper:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.2) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.custom-popup .btn:hover {
    background: #B8860B !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(211, 157, 26, 0.3);
    transition: all 0.2s ease;
}

.custom-popup h6:hover {
    color: #D39D1A !important;
    transition: color 0.2s ease;
}

.popup-home-content {
    transition: all 0.2s ease;
}

.popup-home-content:active {
    transform: scale(0.98);
}

.leaflet-container a.leaflet-popup-close-button {
    color: #333 !important;
    font-size: 20px !important;
    font-weight: bold !important;
    padding: 8px !important;
}

/* Custom Pagination Styles */
.pagination {
    margin: 0;
    padding: 0;
    list-style: none;
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    align-items: center;
    gap: 8px;
}

.pagination .page-item {
    margin: 0;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: #fff;
    color: #6c757d;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.pagination .page-link:hover {
    background: #D39D1A;
    border-color: #D39D1A;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(211, 157, 26, 0.3);
}

.pagination .page-item.active .page-link {
    background: #D39D1A;
    border-color: #D39D1A;
    color: #fff;
    box-shadow: 0 4px 8px rgba(211, 157, 26, 0.3);
}

.pagination .page-item.disabled .page-link {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pagination .page-item.disabled .page-link:hover {
    background: #f8f9fa;
    border-color: #e9ecef;
    color: #adb5bd;
    transform: none;
    box-shadow: none;
}

/* Previous/Next buttons */
.pagination .page-item:first-child .page-link,
.pagination .page-item:last-child .page-link {
    width: auto;
    padding: 0 16px;
    font-size: 13px;
    font-weight: 600;
}

/* Ellipsis */
.pagination .page-item--ellipsis .page-link {
    width: auto;
    min-width: 32px;
    padding: 0 4px;
    background: transparent;
    border: none;
    color: #6c757d;
    cursor: default;
    box-shadow: none;
    letter-spacing: 2px;
}

.pagination .page-item--ellipsis .page-link:hover {
    background: transparent;
    border: none;
    color: #6c757d;
    transform: none;
    box-shadow: none;
}

/* Responsive pagination */
@media (max-width: 576px) {
    .pagination .page-link {
        width: 36px;
        height: 36px;
        font-size: 13px;
    }
    
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        padding: 0 12px;
        font-size: 12px;
    }
    
    .pagination-wrapper {
        padding: 12px !important;
    }
}

/* Modal Styles */
.modal-content {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
}

.modal-header {
    border-bottom: 1px solid #e9ecef !important;
    padding: 16px 20px !important;
}

.modal-header .modal-title {
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #333 !important;
}

.modal-header .btn-close {
    padding: 0.5rem !important;
    margin: 0 !important;
}

.modal-body {
    padding: 20px !important;
}

.modal-footer {
    border-top: 1px solid #e9ecef !important;
    padding: 16px 20px !important;
}

/* Form Controls */
.form-control {
    font-size: 14px !important;
    border-radius: 8px !important;
    border: 1px solid #ddd !important;
    padding: 10px 12px !important;
    transition: border-color 0.2s ease !important;
}

.form-control:focus {
    border-color: #D39D1A !important;
    box-shadow: 0 0 0 0.2rem rgba(211, 157, 26, 0.25) !important;
    outline: none !important;
}

.form-select {
    font-size: 14px !important;
    border-radius: 8px !important;
    border: 1px solid #ddd !important;
    padding: 10px 12px !important;
    transition: border-color 0.2s ease !important;
}

.form-select:focus {
    border-color: #D39D1A !important;
    box-shadow: 0 0 0 0.2rem rgba(211, 157, 26, 0.25) !important;
    outline: none !important;
}

.form-label {
    font-size: 14px !important;
    color: #333 !important;
    font-weight: 500 !important;
    margin-bottom: 8px !important;
}

.form-check-input {
    width: 18px !important;
    height: 18px !important;
    margin-top: 0.25rem !important;
    cursor: pointer !important;
}

.form-check-input:checked {
    background-color: #D39D1A !important;
    border-color: #D39D1A !important;
}

.form-check-input:focus {
    border-color: #D39D1A !important;
    box-shadow: 0 0 0 0.2rem rgba(211, 157, 26, 0.25) !important;
}

.form-check-label {
    font-size: 12px !important;
    color: #555 !important;
    cursor: pointer !important;
    padding-right: 8px !important;
}

/* Buttons */
.btn-primary {
    background: #D39D1A !important;
    border-color: #D39D1A !important;
    color: white !important;
    font-size: 14px !important;
    border-radius: 12px !important;
    padding: 8px 16px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.btn-primary:hover {
    background: #B8860B !important;
    border-color: #B8860B !important;
    color: white !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 4px 8px rgba(211, 157, 26, 0.3) !important;
}

.btn-secondary {
    background: #6c757d !important;
    border-color: #6c757d !important;
    color: white !important;
    font-size: 14px !important;
    border-radius: 12px !important;
    padding: 8px 16px !important;
    font-weight: 500 !important;
    transition: all 0.2s ease !important;
}

.btn-secondary:hover {
    background: #5a6268 !important;
    border-color: #5a6268 !important;
    color: white !important;
}

/* Responsive Modal */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 10px !important;
    }
    
    .modal-content {
        border-radius: 12px !important;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 12px 16px !important;
    }
}

/* Page Header Styles */
.page-header-card {
    background: #ffffff !important;
    border-radius: 12px !important;
    padding: 16px !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1) !important;
    border: none !important;
    margin-bottom: 0 !important;
}

.page-header-card .page-header-title {
    font-size: 18px !important;
    font-weight: 700 !important;
    color: #333333 !important;
    margin: 0 0 8px 0 !important;
    line-height: 1.4 !important;
    text-align: right !important;
}

.page-header-card .page-header-subtitle {
    font-size: 14px !important;
    color: #666666 !important;
    margin: 0 !important;
    line-height: 1.5 !important;
    text-align: right !important;
}

@media (max-width: 576px) {
    .page-header-card {
        padding: 12px !important;
        border-radius: 10px !important;
    }
    
    .page-header-card .page-header-title {
        font-size: 16px !important;
        margin-bottom: 6px !important;
    }
    
    .page-header-card .page-header-subtitle {
        font-size: 13px !important;
    }
}

@media (max-width: 400px) {
    .page-header-card {
        padding: 10px !important;
    }
    
    .page-header-card .page-header-title {
        font-size: 15px !important;
    }
    
    .page-header-card .page-header-subtitle {
        font-size: 12px !important;
    }
}

/* Filter Badges Scrollable Styles */
.filter-badges-scroll {
    scrollbar-width: thin;
    scrollbar-color: #D39D1A #f0f0f0;
    -ms-overflow-style: -ms-autohiding-scrollbar;
}

.filter-badges-scroll::-webkit-scrollbar {
    height: 4px;
}

.filter-badges-scroll::-webkit-scrollbar-track {
    background: #f0f0f0;
    border-radius: 10px;
}

.filter-badges-scroll::-webkit-scrollbar-thumb {
    background: #D39D1A;
    border-radius: 10px;
}

.filter-badges-scroll::-webkit-scrollbar-thumb:hover {
    background: #B8860B;
}

.filter-badge-btn {
    flex-shrink: 0;
}

.filter-badge-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.filter-badge-btn.active:hover {
    background: #B8860B !important;
    box-shadow: 0 4px 8px rgba(211, 157, 26, 0.4) !important;
}

.filter-badge-btn:active {
    transform: translateY(0);
}

.list-group-item.active {
    background-color: #D39D1A !important;
    border-color: #D39D1A !important;
    color: white !important;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.list-group-item.active:hover {
    background-color: #B8860B !important;
}

/* نوار جستجوی موبایل — مشابه صفحه اصلی + چیپ فیلتر */
.homes-mobile-search-wrap {
    width: 100%;
}

.homes-mobile-search-form {
    display: flex;
    align-items: stretch;
    gap: 8px;
    width: 100%;
}

.homes-mobile-search-input-wrap {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #fff;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 6px 10px;
    min-height: 40px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.homes-mobile-search-input-wrap:focus-within {
    border-color: #D39D1A;
    box-shadow: 0 2px 8px rgba(211, 157, 26, 0.2);
}

.homes-mobile-search-input-wrap.has-chips {
    padding-top: 8px;
}

.homes-mobile-search-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    max-height: 56px;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
}

.homes-search-chip {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    max-width: 100%;
    padding: 2px 4px 2px 8px;
    background: #f5f0e6;
    border: 1px solid #e8d9b8;
    border-radius: 14px;
    font-size: 11px;
    font-weight: 600;
    color: #5c4a1a;
    line-height: 1.3;
}

.homes-search-chip__label {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 120px;
}

.homes-search-chip__remove {
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    padding: 0;
    border: none;
    border-radius: 50%;
    background: transparent;
    color: #8a7340;
    font-size: 14px;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.homes-search-chip__remove:hover {
    background: rgba(211, 157, 26, 0.2);
    color: #3d3010;
}

.homes-mobile-search-input {
    flex: 1;
    min-width: 0;
    border: none;
    background: transparent;
    padding: 2px 4px;
    font-size: 13px;
    color: #333;
    outline: none;
    font-family: inherit;
    line-height: 1.4;
}

.homes-mobile-search-input::placeholder {
    color: #999;
    font-size: 12px;
}

.homes-mobile-search-btn {
    flex-shrink: 0;
    width: 40px;
    min-width: 40px;
    height: 40px;
    align-self: center;
    border: none;
    border-radius: 10px;
    background: #D39D1A;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(211, 157, 26, 0.3);
    transition: background 0.2s ease, transform 0.15s ease;
}

.homes-mobile-search-btn .bi {
    font-size: 16px;
}

.homes-mobile-search-btn:active {
    transform: scale(0.97);
    background: #B8860B;
}

.filter-badge-btn.filter-badge-btn--compact span.filter-badge-btn__label {
    max-width: 72px;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="page-header-card">
            <h1 class="page-header-title">{{ $homesSeoContext['h1'] ?? 'اجاره ویلا و سوئیت' }}</h1>
            <p class="page-header-subtitle">جستجو و رزرو آنلاین ویلا، سوئیت و اقامتگاه</p>
            
        </div>
    </div>

    <div class="container px-3 py-2">
        @include('main.homes.partials.homes-filter-section')
    </div>

    <!-- دکمه ثابت نقشه (بالای نوار پایین) -->
    <button type="button" id="fixedMapBtn" class="homes-mobile-map-fab d-md-none" aria-label="نقشه">
        <i class="bi bi-map" aria-hidden="true"></i>
        <span>نقشه</span>
    </button>

    <!-- Homes List -->
    <div class="container px-3 pb-4 homes-mobile-content-bottom-space">
        @if($homes->count() > 0)
            @foreach($homes as $home)
                @include('main.homes.partials.mobile-home-card', ['home' => $home])
            @endforeach

            <!-- Pagination -->
            @if($homes->hasPages())
                <div class="d-flex justify-content-center mt-4 mb-4">
                    <div class="pagination-wrapper" style="background: #f8f9fa; padding: 16px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        @include('main.homes.partials.homes-pagination')
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-3 p-4 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <i class="bi bi-house fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">@lang('text.empty search')</h5>
                <p class="text-muted mb-3" style="font-size: 14px;">
                    متأسفانه اقامتگاهی با این شرایط پیدا نشد
                </p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal" style="background: #D39D1A; border-color: #D39D1A; color: white; border-radius: 12px;">
                    <i class="bi bi-funnel me-2"></i>
                    تغییر فیلترها
                </button>
            </div>
        @endif
    </div>


    <!-- نقشه تمام‌صفحه با فیلتر و پیش‌نمایش -->
    <div class="modal fade map-explorer-modal" id="mapExplorerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="map-explorer-shell">
                    <div id="mapExplorerMap"></div>

                    <div class="map-explorer-top">
                        <div class="map-explorer-top-row">
                            <button type="button" class="map-explorer-back" data-bs-dismiss="modal" aria-label="بستن نقشه">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                            <div class="map-explorer-filters">
                                <button type="button" class="map-explorer-filter-pill {{ request('start_at') && request('end_at') ? 'is-active' : '' }}" id="mapFilterDateBtn">
                                    <i class="bi bi-calendar3"></i>
                                    <span id="mapFilterDateLabel">{{ request('start_at') && request('end_at') ? request('start_at') . ' - ' . request('end_at') : 'تاریخ سفر' }}</span>
                                </button>
                                <button type="button" class="map-explorer-filter-pill {{ request('guest_count') ? 'is-active' : '' }}" id="mapFilterGuestBtn">
                                    <i class="bi bi-people"></i>
                                    <span id="mapFilterGuestLabel">{{ $guestLabel ?? 'تعداد نفرات' }}</span>
                                </button>
                                <button type="button" class="map-explorer-filter-pill" id="mapFilterMoreBtn">
                                    <i class="bi bi-sliders"></i>
                                    <span>سایر فیلترها</span>
                                </button>
                </div>
                </div>
                        <div id="mapFilterHint" class="map-explorer-hint {{ request('start_at') && request('end_at') && request('guest_count') ? 'is-hidden' : '' }}">
                            <span class="map-explorer-hint-dot"></span>
                            <span>برای مشاهده نتایج دقیق‌تر، تاریخ سفر و تعداد نفرات را انتخاب نمایید</span>
            </div>
        </div>

                    <div class="map-explorer-controls">
                        <button type="button" class="map-explorer-control-btn" id="mapZoomInBtn" aria-label="بزرگ‌نمایی">+</button>
                        <button type="button" class="map-explorer-control-btn" id="mapZoomOutBtn" aria-label="کوچک‌نمایی">−</button>
                        <button type="button" class="map-explorer-control-btn" id="mapMyLocationBtn" aria-label="موقعیت من">
                            <i class="bi bi-crosshair"></i>
                        </button>
    </div>

                    <div id="mapPropertyPreview" class="map-explorer-preview" style="display: none;">
                        <button type="button" class="map-explorer-preview-close" id="mapPreviewCloseBtn" aria-label="بستن">
                            <i class="bi bi-x-lg"></i>
                        </button>
                        <a href="#" id="mapPreviewLink" class="map-explorer-preview-link">
                            <img src="" alt="" id="mapPreviewImage" class="map-explorer-preview-thumb">
                            <div class="map-explorer-preview-body">
                                <h6 class="map-explorer-preview-title" id="mapPreviewTitle"></h6>
                                <p class="map-explorer-preview-meta" id="mapPreviewMeta"></p>
                                <p class="map-explorer-preview-price" id="mapPreviewPrice"></p>
                                <span class="map-explorer-preview-badge" id="mapPreviewBadge" style="display: none;"></span>
                </div>
                        </a>
                    </div>

                    <div class="map-explorer-footer">
                        <button type="button" class="map-explorer-results-btn" id="mapViewResultsBtn" data-bs-dismiss="modal">
                            <span>مشاهده نتایج</span>
                            <i class="bi bi-chevron-up"></i>
                        </button>
                        <p class="map-explorer-results-summary" id="mapResultsSummary">در حال بارگذاری...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('main.homes.partials.homes-filter-modals')
    @include('main.homes.partials.homes-travel-filter-sheets')

@endsection

@section('scripts')
    <script src="{{ asset('assets/js/guest-rating.js') }}"></script>
    <script src="{{ asset('assets/js/map-travel-jalali-calendar.js') }}"></script>
    <script src="{{ asset('assets/js/homes-mobile-search.js') }}"></script>
    <script src="{{ asset('assets/js/mobile-price-range.js') }}"></script>
    <script src="{{ asset('assets/js/map-travel-filter.js') }}"></script>
    <script src="{{ asset('assets/js/homes-filters.js') }}"></script>
    <script src="{{ asset('assets/js/homes-date-filter.js') }}"></script>

    <script>
        window.homesDateFilterConfig = {
            minDate: @json(\App\Models\Order::getMinReserveDate()->format('Y-m-d')),
            maxDate: @json(\App\Models\Order::getMaxReserveDate()->format('Y-m-d')),
            startLabel: @json(__('title.date_enter')),
            endLabel: @json(__('title.date_quit')),
        };
        window.provinceMapCenters = @json($provinceMapCenters ?? []);
    </script>

    <script>
        // Map explorer
        const initialMapSearch = new URLSearchParams(window.location.search);
        const shouldOpenMapExplorer = initialMapSearch.get('map') === '1';
        if (shouldOpenMapExplorer) {
            initialMapSearch.delete('map');
        }

        const mapExplorerState = {
            map: null,
            markers: [],
            userMarker: null,
            homes: [],
            selectedHomeId: null,
            filters: initialMapSearch,
        };

        const L = MapUtils.neshanLeaflet();

        const HOME_MARKER_HTML = `<div style="background: #1a1a1a; width: 28px; height: 28px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-house-fill" style="color: white; font-size: 14px;"></i></div>`;

        function formatMapPriceShort(price) {
            const n = Number(price) || 0;
            if (n >= 1000000) {
                const millions = n / 1000000;
                const text = millions % 1 === 0 ? millions.toFixed(0) : millions.toFixed(1);
                return text + ' میلیون';
            }
            if (n >= 1000) {
                return Math.round(n / 1000).toLocaleString('fa-IR') + ' هزار';
            }
            return n.toLocaleString('fa-IR');
        }

        function createHomeMarkerIcon(home, isSelected) {
            if (isSelected) {
                return L.divIcon({
                    className: 'map-price-marker',
                    html: `<div class="map-price-bubble">${formatMapPriceShort(home.price)}</div>`,
                    iconSize: [80, 32],
                    iconAnchor: [40, 32],
                });
            }
            return L.divIcon({
                className: 'custom-home-marker',
                html: HOME_MARKER_HTML,
                iconSize: [28, 28],
                iconAnchor: [14, 14],
            });
        }

        function updateMapFilterHint() {
            const hint = document.getElementById('mapFilterHint');
            const hasDate = mapExplorerState.filters.get('start_at') && mapExplorerState.filters.get('end_at');
            const hasGuest = mapExplorerState.filters.get('guest_count');
            if (hint) {
                hint.classList.toggle('is-hidden', !!(hasDate && hasGuest));
            }
        }

        function shortJalaliRangeLabel(startAt, endAt) {
            if (!startAt || !endAt) return 'تاریخ سفر';
            const fmt = function (str) {
                const p = String(str).split('/');
                if (p.length < 3) return str;
                const pd = window.MapJalaliUtils ? window.MapJalaliUtils.toPersianNum : function (v) { return v; };
                return pd(p[2]) + '/' + pd(p[1]);
            };
            return fmt(startAt) + ' - ' + fmt(endAt);
        }

        function updateMapFilterLabels() {
            const dateLabel = document.getElementById('mapFilterDateLabel');
            const guestLabel = document.getElementById('mapFilterGuestLabel');
            const dateBtn = document.getElementById('mapFilterDateBtn');
            const guestBtn = document.getElementById('mapFilterGuestBtn');
            const startAt = mapExplorerState.filters.get('start_at');
            const endAt = mapExplorerState.filters.get('end_at');
            const guestCount = mapExplorerState.filters.get('guest_count');

            if (dateLabel) {
                dateLabel.textContent = shortJalaliRangeLabel(startAt, endAt);
            }
            if (guestLabel) {
                if (!guestCount) {
                    guestLabel.textContent = 'تعداد نفرات';
                } else if (guestCount === '10') {
                    guestLabel.textContent = '10+ نفر';
                } else {
                    guestLabel.textContent = `${guestCount} نفر`;
                }
            }
            if (dateBtn) {
                dateBtn.classList.toggle('is-active', !!(startAt && endAt));
            }
            if (guestBtn) {
                guestBtn.classList.toggle('is-active', !!guestCount);
            }
            updateMapFilterHint();
        }

        function hideMapPropertyPreview() {
            const preview = document.getElementById('mapPropertyPreview');
            if (preview) {
                preview.style.display = 'none';
            }
            mapExplorerState.selectedHomeId = null;
            renderMapMarkers();
        }

        function showMapPropertyPreview(home) {
            const preview = document.getElementById('mapPropertyPreview');
            if (!preview || !home) {
                return;
            }

            mapExplorerState.selectedHomeId = home.id;
            renderMapMarkers();

            document.getElementById('mapPreviewImage').src = home.cover_path || '/images/placeholder.jpg';
            document.getElementById('mapPreviewImage').alt = home.name;
            document.getElementById('mapPreviewTitle').textContent = home.name;
            document.getElementById('mapPreviewLink').href = home.link;

            const bedroomText = home.bedroom_count ? `${home.bedroom_count} خوابه` : '';
            const meterText = home.infrastructure_meter ? `${home.infrastructure_meter.toLocaleString('fa-IR')} متر` : '';
            const guestText = home.max_guests ? `تا ${home.max_guests} نفر` : '';
            const ratingText = (typeof hasGuestRating === 'function' && hasGuestRating(home))
                ? `<i class="bi bi-star-fill text-warning"></i> ${home.guest_score_display || home.guest_score} (${home.count_comments || 0} نظر مهمان)`
                : 'جدید';
            const metaParts = [bedroomText, meterText, guestText].filter(Boolean);
            document.getElementById('mapPreviewMeta').innerHTML = `${metaParts.join(' · ')}${metaParts.length ? ' · ' : ''}${ratingText}`;

            const priceText = home.price
                ? `هر شب از ${Number(home.price).toLocaleString('fa-IR')} تومان`
                : 'قیمت تماس بگیرید';
            document.getElementById('mapPreviewPrice').textContent = priceText;

            const badge = document.getElementById('mapPreviewBadge');
            if (home.successful_bookings_count > 0) {
                badge.textContent = `+${home.successful_bookings_count.toLocaleString('fa-IR')} رزرو موفق`;
                badge.style.display = 'inline-block';
                } else {
                badge.style.display = 'none';
            }

            preview.style.display = 'block';
        }

        function clearMapMarkers() {
            if (!mapExplorerState.map) {
                return;
            }
            mapExplorerState.markers.forEach(marker => mapExplorerState.map.removeLayer(marker));
            mapExplorerState.markers = [];
            if (mapExplorerState.userMarker) {
                mapExplorerState.map.removeLayer(mapExplorerState.userMarker);
                mapExplorerState.userMarker = null;
            }
        }

        function renderMapMarkers() {
            if (!mapExplorerState.map) {
                return;
            }
            clearMapMarkers();

            mapExplorerState.homes.forEach(home => {
                const isSelected = mapExplorerState.selectedHomeId === home.id;
                const marker = L.marker([home.latitude, home.longitude], {
                    icon: createHomeMarkerIcon(home, isSelected),
                    zIndexOffset: isSelected ? 1000 : 0,
                }).addTo(mapExplorerState.map);

                marker.on('click', function(e) {
                    L.DomEvent.stopPropagation(e);
                    showMapPropertyPreview(home);
                });

                mapExplorerState.markers.push(marker);
            });
        }

        function updateMapResultsSummary(count, minPrice) {
            const summary = document.getElementById('mapResultsSummary');
            if (!summary) {
                return;
            }
            if (!count) {
                summary.textContent = 'اقامتگاهی یافت نشد';
                return;
            }
            const minText = minPrice ? Number(minPrice).toLocaleString('fa-IR') : '0';
            summary.textContent = `${count.toLocaleString('fa-IR')} اقامتگاه از ${minText} تومان`;
        }

        function fitMapToHomes() {
            if (!mapExplorerState.map || !mapExplorerState.homes.length) {
                return;
            }
            const bounds = L.latLngBounds(mapExplorerState.homes.map(h => [h.latitude, h.longitude]));
            mapExplorerState.map.fitBounds(bounds, { padding: [80, 80], maxZoom: 14 });
        }

        function fitMapToProvinceOrHomes() {
            if (!mapExplorerState.map) {
                return;
            }

            const provinceId = mapExplorerState.filters.get('province');
            const provinceConfig = provinceId ? window.provinceMapCenters?.[provinceId] : null;

            if (!provinceConfig) {
                fitMapToHomes();
                return;
            }

            const maxZoom = provinceConfig.zoom || 8;

            if (mapExplorerState.homes.length >= 2) {
                const bounds = L.latLngBounds(mapExplorerState.homes.map(h => [h.latitude, h.longitude]));
                mapExplorerState.map.fitBounds(bounds, { padding: [80, 80], maxZoom: maxZoom });
                return;
            }

            if (mapExplorerState.homes.length === 1) {
                const home = mapExplorerState.homes[0];
                mapExplorerState.map.setView([home.latitude, home.longitude], maxZoom);
                return;
            }

            mapExplorerState.map.setView(
                [provinceConfig.latitude, provinceConfig.longitude],
                maxZoom
            );
        }

        function loadMapHomes() {
            const summary = document.getElementById('mapResultsSummary');
            if (summary) {
                summary.textContent = 'در حال بارگذاری...';
            }
            hideMapPropertyPreview();

            const query = mapExplorerState.filters.toString();
            const url = `{{ route('main.homes.map-data') }}${query ? '?' + query : ''}`;

            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('map-data failed');
                    }
                    return response.json();
                })
                .then(data => {
                    mapExplorerState.homes = data.homes || [];
                    updateMapResultsSummary(data.count || 0, data.min_price || 0);
                    renderMapMarkers();
                    fitMapToProvinceOrHomes();
                })
                .catch(() => {
                    mapExplorerState.homes = [];
                    updateMapResultsSummary(0, 0);
                    if (summary) {
                        summary.textContent = 'خطا در بارگذاری نقشه';
                    }
                });
        }

        function initMapExplorer() {
            if (mapExplorerState.map) {
                setTimeout(() => mapExplorerState.map.invalidateSize(), 150);
                return;
            }

            mapExplorerState.map = MapUtils.createMap('mapExplorerMap', {
                center: [35.6892, 51.3890],
                zoom: 6,
                zoomControl: false,
            });

            mapExplorerState.map.on('click', hideMapPropertyPreview);
        }

        function isMapExplorerOpen() {
            return document.getElementById('mapExplorerModal')?.classList.contains('show');
        }

        function openModalAboveMap(modalEl) {
            if (!modalEl) {
                        return;
                    }
            if (isMapExplorerOpen()) {
                document.body.classList.add('map-filter-modal-open');
            }
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        function openMapExplorerModal() {
            const modalEl = document.getElementById('mapExplorerModal');
            if (!modalEl) {
                return;
            }
            updateMapFilterLabels();
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        document.querySelectorAll('.modal-above-map-explorer').forEach(modalEl => {
            modalEl.addEventListener('hidden.bs.modal', function() {
                if (!document.querySelector('.modal-above-map-explorer.show')) {
                    document.body.classList.remove('map-filter-modal-open');
                }
            });
        });

        const fixedMapBtn = document.getElementById('fixedMapBtn');
        if (fixedMapBtn) {
            fixedMapBtn.addEventListener('click', openMapExplorerModal);
        }

        const mapExplorerModalEl = document.getElementById('mapExplorerModal');
        if (mapExplorerModalEl) {
            mapExplorerModalEl.addEventListener('shown.bs.modal', function() {
                setTimeout(() => {
                    initMapExplorer();
                    loadMapHomes();
                }, 120);
            });

            mapExplorerModalEl.addEventListener('hidden.bs.modal', function() {
                hideMapPropertyPreview();
            });
        }

        document.getElementById('mapZoomInBtn')?.addEventListener('click', () => {
            mapExplorerState.map?.zoomIn();
        });
        document.getElementById('mapZoomOutBtn')?.addEventListener('click', () => {
            mapExplorerState.map?.zoomOut();
        });
        document.getElementById('mapMyLocationBtn')?.addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('مرورگر شما از GPS پشتیبانی نمی‌کند');
                return;
            }
            const btn = this;
            btn.disabled = true;
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    if (mapExplorerState.map) {
                        if (mapExplorerState.userMarker) {
                            mapExplorerState.map.removeLayer(mapExplorerState.userMarker);
                        }
                    const userIcon = L.divIcon({
                        className: 'custom-user-marker',
                        html: `<div style="background: #D39D1A; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;"><i class="bi bi-geo-alt-fill" style="color: white; font-size: 18px;"></i></div>`,
                        iconSize: [32, 32],
                            iconAnchor: [16, 16],
                        });
                        mapExplorerState.userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(mapExplorerState.map);
                        mapExplorerState.map.setView([lat, lng], 13);
                    }
                    btn.disabled = false;
                },
                function(error) {
                    alert('خطا در دریافت موقعیت: ' + error.message);
                    btn.disabled = false;
                }
            );
        });

        document.getElementById('mapPreviewCloseBtn')?.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            hideMapPropertyPreview();
        });

        if (window.MapTravelFilter) {
            MapTravelFilter.init({
                minDate: @json(\App\Models\Order::getMinReserveDate()->format('Y-m-d')),
                maxDate: @json(\App\Models\Order::getMaxReserveDate()->format('Y-m-d')),
                maxGuests: 20,
                labels: {
                    start: @json(__('title.date_enter')),
                    end: @json(__('title.date_quit')),
                },
            });

            MapTravelFilter.onDateApplied(function(payload) {
                if (payload.start_at && payload.end_at) {
                    mapExplorerState.filters.set('start_at', payload.start_at);
                    mapExplorerState.filters.set('end_at', payload.end_at);
                } else {
                    mapExplorerState.filters.delete('start_at');
                    mapExplorerState.filters.delete('end_at');
                }
                updateMapFilterLabels();
                if (mapExplorerModalEl?.classList.contains('show')) {
                    loadMapHomes();
                }
            });

            MapTravelFilter.onGuestApplied(function(payload) {
                if (payload.guest_count) {
                    mapExplorerState.filters.set('guest_count', payload.guest_count);
                } else {
                    mapExplorerState.filters.delete('guest_count');
                }
                updateMapFilterLabels();
                if (mapExplorerModalEl?.classList.contains('show')) {
                    loadMapHomes();
                }
            });
        }

        document.getElementById('mapFilterDateBtn')?.addEventListener('click', function() {
            if (window.MapTravelFilter) {
                MapTravelFilter.openDateFilter({
                    start_at: mapExplorerState.filters.get('start_at') || '',
                    end_at: mapExplorerState.filters.get('end_at') || '',
                });
            }
        });

        document.getElementById('mapFilterGuestBtn')?.addEventListener('click', function() {
            if (window.MapTravelFilter) {
                MapTravelFilter.openGuestFilter({
                    guest_count: mapExplorerState.filters.get('guest_count') || '',
                });
            }
        });

        // When "other filters" modal applies, reopen map if it was open
        document.getElementById('mapFilterMoreBtn')?.addEventListener('click', function() {
            sessionStorage.setItem('reopenMapExplorer', '1');
            openModalAboveMap(document.getElementById('filterModal'));
        });

        document.getElementById('filterModal')?.querySelector('form')?.addEventListener('submit', function() {
            sessionStorage.setItem('reopenMapExplorer', '1');
        });

        if (sessionStorage.getItem('reopenMapExplorer') === '1') {
            sessionStorage.removeItem('reopenMapExplorer');
            const reopenedParams = new URLSearchParams(window.location.search);
            reopenedParams.delete('map');
            mapExplorerState.filters = reopenedParams;
            setTimeout(openMapExplorerModal, 400);
        } else if (shouldOpenMapExplorer) {
            setTimeout(openMapExplorerModal, 400);
        }
    </script>
@endsection
