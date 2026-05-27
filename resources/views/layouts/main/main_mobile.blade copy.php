<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    @include('layouts.main.partials.head_mobile')
    @yield('meta')
    @yield('styles')
</head>
<body>
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