@php
    $favicon48 = settingFaviconUrl(48);
    $favicon192 = settingFaviconUrl(192);
    $favicon512 = settingFaviconUrl(512);
@endphp
@if($favicon512)
    <link rel="icon" href="{{ url('/favicon.ico') }}" sizes="48x48">
    @if($favicon48)
        <link rel="icon" type="image/png" sizes="48x48" href="{{ $favicon48 }}">
    @endif
    @if($favicon192)
        <link rel="icon" type="image/png" sizes="192x192" href="{{ $favicon192 }}">
    @endif
    <link rel="icon" type="image/png" sizes="512x512" href="{{ $favicon512 }}">
    <link rel="shortcut icon" type="image/png" href="{{ $favicon48 ?: $favicon512 }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $favicon192 ?: $favicon512 }}">
@endif
