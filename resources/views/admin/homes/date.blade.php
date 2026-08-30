@extends('layouts.admin.admin', ['title' => __('title.edit home calender'), 'active' => 'homes'])

@section('content')
    <x-admin.card title="{{ __('title.edit home calender') }} — {{ $home->name }}">
        <div class="home-calendar-desktop">
            <div class="home-calendar-mobile__picker">
                @include('admin.homes.partials.custom-date', ['home' => $home, 'stacked' => true])
            </div>
            <p class="home-calendar-card__hint mt-3 mb-0">
                با کلیک بر روی یک یا چند روز، تغییرات را بصورت یکجا اعمال کنید.
            </p>
        </div>

        <div class="col-12 mt-5 d-flex justify-content-center">
            <a href="{{ route('admin.homes.edit', $home) }}" class="btn btn-falcon-default me-2">@lang('title.edit')</a>
            <a href="{{ route('admin.homes.index') }}" class="btn btn-falcon-danger">@lang('title.return')</a>
        </div>
    </x-admin.card>
@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/home-calendar-mobile.css') }}">
    <style>
        .home-calendar-desktop {
            background: #fff;
            color: #222;
            border-radius: 12px;
            padding: 8px 8px 4px;
        }
        .home-calendar-desktop .home-calendar-card__hint {
            color: #666;
        }
    </style>
@endsection
