@php
    $openTab = old('open_tab', request('open_tab') ?: session('open_tab') ?: 'tab-admin');
@endphp
@extends('layouts.admin.admin', ['title' => __('title.edit home'), 'active' => 'homes'])

@section('content')
    <div v-pre class="admin-home-edit-wrap">
        <x-admin.card title="{{ __('title.edit home') }}">

            <div class="px-3 pt-3 pb-2 border-bottom bg-light">
                <div class="d-flex flex-wrap align-items-center gap-3 small text-muted">
                    <span>
                        <span class="fas fa-hashtag ms-1"></span>
                        کد: {{ $home->id }}
                    </span>
                    <span>
                        <span class="fas fa-clock ms-1"></span>
                        @lang('title.created at'): {{ $home->persianCreatedAt() }}
                    </span>
                </div>
            </div>

            <div class="p-3">
                @include('admin.partials.home-edit-tabs', ['home' => $home])

                <form action="{{ route('admin.homes.update', $home) }}" enctype="multipart/form-data" method="POST"
                      id="admin-home-edit-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="open_tab" id="adminOpenTabField" value="{{ $openTab }}">

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-admin' ? ' active' : '' }}" id="tab-admin">
                        @include('admin.partials.home-edit.tab-admin', compact('home', 'usersForSelect'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-media' ? ' active' : '' }}" id="tab-media">
                        @include('admin.partials.home-edit.tab-media', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-basic' ? ' active' : '' }}" id="tab-basic">
                        @include('admin.partials.home-edit.tab-basic', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-rooms' ? ' active' : '' }}" id="tab-rooms">
                        @include('admin.partials.home-edit.tab-rooms', compact('home', 'sleepPrivatePlaces', 'sleepSharePlace'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-location' ? ' active' : '' }}" id="tab-location">
                        @include('admin.partials.home-edit.tab-location', compact('home', 'provinces', 'cities'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-pricing' ? ' active' : '' }}" id="tab-pricing">
                        @include('admin.partials.home-edit.tab-pricing', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-discount' ? ' active' : '' }}" id="tab-discount">
                        @include('admin.partials.home-edit.tab-discount', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-options' ? ' active' : '' }}" id="tab-options">
                        @include('admin.partials.home-edit.tab-options', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-health' ? ' active' : '' }}" id="tab-health">
                        @include('admin.partials.home-edit.tab-health', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-safety' ? ' active' : '' }}" id="tab-safety">
                        @include('admin.partials.home-edit.tab-safety', compact('home'))
                    </div>

                    <div class="admin-home-edit-tab-pane{{ $openTab === 'tab-rules' ? ' active' : '' }}" id="tab-rules">
                        @include('admin.partials.home-edit.tab-rules', compact('home'))
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-center gap-2 flex-wrap sticky-bottom bg-white py-2">
                        <button type="submit" class="btn btn-falcon-success px-4">
                            <span class="fas fa-save ms-1"></span> @lang('title.edit')
                        </button>
                        <a href="{{ route('admin.homes.index') }}" class="btn btn-falcon-danger px-4">@lang('title.return')</a>
                    </div>
                </form>

                @if(!$home->images->isEmpty())
                    <form action="{{ route('admin.homes.image.bulk-delete', $home) }}" method="POST"
                          id="form-bulk-delete-images" class="d-none" aria-hidden="true">
                        @csrf
                    </form>
                @endif
            </div>
        </x-admin.card>
    </div>

    <div class="modal fade" id="adminMapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">انتخاب موقعیت روی نقشه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="adminMap" style="height: 420px; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-primary" onclick="adminSaveMapLocation()">تأیید موقعیت</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}">
    <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-city-filter.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-gallery-ui.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-tabs.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-bedrooms.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-map.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-price-fields.js') }}"></script>
    <script src="{{ asset('assets/admin/js/home-edit-reject-policy.js') }}"></script>
    <style>
        .admin-home-edit-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
        }
        .admin-home-edit-tab-pill {
            border: 1px solid #d8e2ef;
            background: #fff;
            color: #344050;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.8125rem;
            white-space: nowrap;
            cursor: pointer;
            transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        }
        .admin-home-edit-tab-pill:hover {
            border-color: rgba(211, 157, 26, 0.55);
            color: #344050;
        }
        .admin-home-edit-tab-pill.active {
            background: #D39D1A;
            border-color: #D39D1A;
            color: #fff;
        }
        .admin-home-edit-tab-pane { display: none; }
        .admin-home-edit-tab-pane.active { display: block; }
        .admin-upload-dropzone { transition: border-color .2s ease, background-color .2s ease; }
        .admin-upload-dropzone:hover { border-color: rgba(211, 157, 26, 0.65) !important; background-color: #fffdf8 !important; }
        .admin-gallery-tile { transition: box-shadow .15s ease, outline .15s ease; }
        .admin-gallery-tile.border-warning { box-shadow: 0 .25rem .75rem rgba(211, 157, 26, 0.25); }
        .admin-location-preview-map { height: 220px; width: 100%; z-index: 1; }
        .admin-option-chip:has(.admin-option-check:checked) {
            border-color: #D39D1A !important;
            background-color: #fff9eb !important;
        }
        .price-words { line-height: 1.6; font-size: 0.75rem; }
        @media (max-width: 767.98px) {
            .admin-home-edit-wrap .sticky-bottom {
                position: sticky;
                bottom: 0;
                z-index: 5;
                margin-left: -1rem;
                margin-right: -1rem;
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.admin-option-check').forEach(function (input) {
                input.addEventListener('change', function () {
                    var label = input.closest('.admin-option-chip');
                    if (!label) return;
                    label.classList.toggle('border-warning', input.checked);
                    label.classList.toggle('bg-light', input.checked);
                });
            });

            document.querySelectorAll('.admin-safety-toggle').forEach(function (toggle) {
                toggle.addEventListener('change', function () {
                    var target = document.getElementById(toggle.dataset.target);
                    if (target) {
                        target.classList.toggle('d-none', !toggle.checked);
                    }
                });
            });
        });
    </script>
@endsection
