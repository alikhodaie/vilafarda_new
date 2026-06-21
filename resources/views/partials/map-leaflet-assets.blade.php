@if(\App\Support\MapConfig::usesNeshanSdk())
    <link rel="stylesheet" href="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.css">
    <script src="https://static.neshan.org/sdk/leaflet/v1.9.4/neshan-sdk/v1.0.8/index.js"></script>
    <script>
        window.__neshanLeaflet = window.L;
    </script>
@else
    <link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}">
    <script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
@endif
