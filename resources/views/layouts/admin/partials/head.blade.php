
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">


<!-- ===============================================-->
<!--    Document Title-->
<!-- ===============================================-->
<title>{{ __('title.admin') }} | {{ $title }}</title>


<!-- ===============================================-->
<!--    Favicons-->
<!-- ===============================================-->
{{--<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-touch-icon.png">--}}
{{--<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">--}}
{{--<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">--}}
{{--<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicons/favicon.ico">--}}
{{--<link rel="manifest" href="assets/img/favicons/manifest.json">--}}
{{--<meta name="msapplication-TileImage" content="assets/img/favicons/mstile-150x150.png">--}}
{{--<meta name="theme-color" content="#ffffff">--}}
<script src="{{ asset('assets/admin/js/config.js') }}"></script>
<script src="{{ asset('assets/vendors/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>


<!-- ===============================================-->
<!--    Stylesheets-->
<!-- ===============================================-->
<link href="{{ asset('assets/vendors/overlayscrollbars/OverlayScrollbars.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/admin/css/theme-rtl.min.css') }}" rel="stylesheet" id="style-rtl">
<link href="{{ asset('assets/admin/css/theme.min.css') }}" rel="stylesheet" id="style-default">
<link href="{{ asset('assets/admin/css/user-rtl.min.css') }}" rel="stylesheet" id="user-style-rtl">
<link href="{{ asset('assets/admin/css/user.min.css') }}" rel="stylesheet" id="user-style-default">
<link href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">

<!-- Js Script -->
{{--<script--}}
{{--    async defer--}}
{{--    src="https://maps.googleapis.com/maps/api/js?key={{ config('google.api_key') }}&region=IR&language=fa"--}}
{{--></script>--}}
<script>
    var isRTL = JSON.parse(localStorage.getItem('isRTL'));
    if (isRTL) {
        var linkDefault = document.getElementById('style-default');
        var userLinkDefault = document.getElementById('user-style-default');
        linkDefault.setAttribute('disabled', true);
        userLinkDefault.setAttribute('disabled', true);
        document.querySelector('html').setAttribute('dir', 'rtl');
    } else {
        var linkRTL = document.getElementById('style-rtl');
        var userLinkRTL = document.getElementById('user-style-rtl');
        linkRTL.setAttribute('disabled', true);
        userLinkRTL.setAttribute('disabled', true);
    }
</script>

@yield('top-assets')

