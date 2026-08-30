@extends('layouts.admin.admin', ['title' => __('title.edit home calender'), 'active' => 'homes'])

@section('content')
    <div class="admin-home-calendar-mobile">
        <div class="card home-calendar-card">
            <div class="card-body">
                <h2 class="home-calendar-card__heading">
                    <i class="bi bi-calendar3" aria-hidden="true"></i>
                    <span class="home-calendar-card__heading-label">تقویم اقامتگاه</span>
                    <span class="home-calendar-card__home-name">{{ $home->name }}</span>
                </h2>

                <div class="home-calendar-mobile__picker">
                    @include('admin.homes.partials.custom-date', ['home' => $home, 'stacked' => true])
                </div>

                <div class="home-calendar-card__intro">
                    <p class="home-calendar-card__title">پر یا خالی شدن تقویم · تعیین نرخ روزهای خاص</p>
                    <p class="home-calendar-card__hint">
                        با کلیک بر روی یک یا چند روز، تغییرات را بصورت یکجا اعمال کنید.
                    </p>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('admin.homes.edit', $home) }}" class="btn btn-falcon-default flex-grow-1">@lang('title.edit')</a>
            <a href="{{ route('admin.homes.index') }}" class="btn btn-falcon-danger flex-grow-1">@lang('title.return')</a>
        </div>
    </div>
@endsection

@section('bottom-assets')
    <link rel="stylesheet" href="{{ asset('assets/css/home-calendar-mobile.css') }}">
    <style>
        .admin-home-calendar-mobile .home-calendar-card {
            background: #fff;
            color: #222;
        }
        .admin-home-calendar-mobile .home-calendar-card__heading,
        .admin-home-calendar-mobile .home-calendar-card__heading-label {
            color: #222;
        }
        .content {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
    </style>
@endsection
