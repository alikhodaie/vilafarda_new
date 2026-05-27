<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ \Illuminate\Support\Str::title(config('app.name')) }} - @lang('title.newsletter')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
</head>
<body>
<div class="wrapper">
    <div class="logo"><img src="{{ settingFilePath('app:logo') }}" alt="{{ config('app.name') }}"></div>

    <div class="verificationSection">
        <h3 class="title">{{ $token }}</h3>
        <p class="message">@lang('text.reset_password_email')</p>
    </div>
</div>
<div class="footer">
    <div class="smallLogo"><img src="{{ settingFilePath('app:logo') }}" alt="{{ config('app.name') }}"></div>
    {{--        <div class="socialContainer">--}}
    {{--            <a href="https://www.facebook.com/XanPool/" target="_blank" rel="noopener noreferrer">--}}
    {{--                <div class="facebook"></div>--}}
    {{--            </a>--}}
    {{--            <a href="https://twitter.com/@XanpoolOfficial" target="_blank" rel="noopener noreferrer">--}}
    {{--                <div class="twitter"></div>--}}
    {{--            </a>--}}
    {{--            <a href="https://www.youtube.com/channel/UC8ia9PKgyJb17tCcOpTTclw" target="_blank" rel="noopener noreferrer">--}}
    {{--                <div class="youtube"></div>--}}
    {{--            </a>--}}
    {{--            <a href="https://t.me/joinchat/FF2GFRQMgdtBxq67hc3UZw" target="_blank" rel="noopener noreferrer">--}}
    {{--                <div class="telegram"></div>--}}
    {{--            </a>--}}
    {{--        </div>--}}
</div>
</body>
</html>
