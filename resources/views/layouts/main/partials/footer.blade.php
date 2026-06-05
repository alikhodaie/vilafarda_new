<link rel="stylesheet" href="{{ public_asset_version('assets/css/mobile-footer.css') }}">

@include('layouts.main.partials.footer-site', [
    'footerClass' => 'mobile-site-footer site-footer--desktop d-none d-lg-block',
    'showLogo' => true,
])
