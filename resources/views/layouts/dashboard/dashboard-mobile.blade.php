<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    @include('layouts.main.partials.head_mobile')
</head>

<body class="yellow-skin">
    <x-auth.logout></x-auth.logout>

    <div id="app">
        <loader></loader>
        

        <div id="main-wrapper">

            @include('layouts.main.partials.navbar-mobile')
            <hr>
            @if(request()->is('dashboard'))
                @include('layouts.dashboard.partials.sidebar-mobile')
            @endif

            @include('layouts.main.partials.alert')


            <section>
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-lg-3 col-md-4 col-sm-12">
                            
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
        

        </div>
    </div>

    @include('layouts.main.partials.script')

</body>

</html>
