@php
    $faviconUrl = settingFilePath('app:favicon');
@endphp
@if($faviconUrl)
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="shortcut icon" type="image/png" href="{{ $faviconUrl }}">
    <link rel="apple-touch-icon" href="{{ $faviconUrl }}">
@endif
