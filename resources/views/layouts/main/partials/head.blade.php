<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
@include('layouts.main.partials.seo-meta')
@yield('meta')

<title>{{ $documentTitle ?? siteName() }}</title>
@include('layouts.main.partials.favicon')
@include('layouts.main.partials.pwa-head')

<!-- Bootstrap icons (local) -->
<link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}">

<!-- Custom CSS -->
<link href="{{ public_asset_version('assets/css/styles.css') }}" rel="stylesheet">

<!-- Js Script -->
{{--<script--}}
{{--    async defer--}}
{{--    src="https://maps.googleapis.com/maps/api/js?key={{ config('google.api_key') }}&region=IR&language=fa"--}}
{{--></script>--}}

@include('layouts.main.partials.analytics')
@include('partials.map-config-script')
@include('partials.map-leaflet-assets')
<script src="{{ public_asset_version('assets/js/map-utils.js') }}"></script>

@yield('top-assets')
