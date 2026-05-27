<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    @include('layouts.main.partials.head')
</head>

<body class="yellow-skin">
    <x-auth.logout></x-auth.logout>

    <div id="app">
        <loader></loader>

        @include('layouts.main.partials.alert')

        <div id="main-wrapper">

            @include('layouts.dashboard.partials.navbar')

            @include('layouts.dashboard.partials.title')

        <!-- ============================ User Dashboard ================================== -->
            <section class="gray pt-5 pb-5">
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-3 col-md-4 col-sm-12">
                            @include('layouts.dashboard.partials.sidebar')
                        </div>

                        <div class="col-lg-9 col-md-8 col-sm-12">
                            <div class="dashboard-body">
                                <div class="dashboard-wraper">

                                    @yield('content')

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- ============================ User Dashboard End ================================== -->

            @include('layouts.main.partials.footer')

        </div>
    </div>

    @include('layouts.main.partials.script')

</body>

</html>
