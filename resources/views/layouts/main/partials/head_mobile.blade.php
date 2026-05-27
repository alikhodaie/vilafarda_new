<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
window.__homeFavoriteConfig = {
    auth: @json((bool) auth()->check()),
    appUrl: @json(rtrim(url(''), '/')),
    loginMessage: @json(__('text.please_login')),
    loginUrl: @json(route('main.login.form')),
};
</script>
<link href="{{ public_asset_version('assets/css/home-favorite.css') }}" rel="stylesheet">
@include('layouts.main.partials.seo-meta')
<title>{{ $documentTitle ?? config('app.name') }}</title>

<!-- Bootstrap CSS (RTL - local) -->
<link href="{{ asset('vendor/bootstrap/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

<!-- Bootstrap icons (local) -->
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">

<!-- CSS مخصوص موبایل -->
<link href="{{ public_asset_version('assets/css/styles.css') }}" rel="stylesheet">
<link href="{{ public_asset_version('assets/css/mobile-styles.css') }}" rel="stylesheet">
<link href="{{ public_asset_version('assets/css/mobile-consistency.css') }}" rel="stylesheet">
<link href="{{ public_asset_version('assets/css/mobile-footer.css') }}" rel="stylesheet">

<!-- Swiper CSS (local) -->
<link rel="stylesheet" href="{{ asset('vendor/swiper/swiper-bundle.min.css') }}" />

@include('layouts.main.partials.analytics')

@yield('top-assets')
