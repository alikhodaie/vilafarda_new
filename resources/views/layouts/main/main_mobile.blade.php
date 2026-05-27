<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('layouts.main.partials.head_mobile')
    @yield('meta')
    @yield('styles')
</head>
<body class="mb-5" data-auth-check="{{ auth()->check() ? '1' : '0' }}" data-login-message="{{ __('text.please_login') }}" data-app-base="{{ url('/') }}">
    <x-auth.logout></x-auth.logout>

    <div id="app">
        <loader></loader>
        <div class="preloader"></div>

        @include('layouts.main.partials.alert')

        <div id="main-wrapper">
            @yield('content')
        </div>

        <share-modal></share-modal>
    </div>

    @include('layouts.main.partials.script')
    @yield('scripts')
</body>
</html> 