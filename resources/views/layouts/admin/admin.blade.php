<!DOCTYPE html>
<html lang="en-US" dir="ltr">

<head>
    @include('layouts.admin.partials.head')
</head>

<body>

<!-- ===============================================-->
<!--    Main Content-->
<!-- ===============================================-->
<main class="main" id="top">
    <x-auth.logout></x-auth.logout>

    <div class="container" data-layout="container">
        @include('layouts.admin.partials.sidebar')

        <div class="content" id="app">
            @include('layouts.admin.partials.navbar')

            @include('layouts.admin.partials.alert')

            @yield('content')

            @include('layouts.admin.partials.footer')
        </div>
    </div>
</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->

{{--@include('layouts.admin.partials.customize')--}}

@include('layouts.admin.partials.script')

</body>

</html>
