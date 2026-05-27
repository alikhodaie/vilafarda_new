@php($show_fixed_buttons = $show_fixed_buttons ?? true)
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    @include('layouts.main.partials.head')
</head>

<body class="yellow-skin">
    <x-auth.logout></x-auth.logout>

    <div id="app">
        <loader></loader>
        <div class="preloader"></div>

        @include('layouts.main.partials.alert')

        <div id="main-wrapper">
            @if(request()->routeIs('main.index'))
                @include('layouts.main.partials.navbar')
            @else
                @include('layouts.dashboard.partials.navbar')
            @endif

            @yield('content')
            @if(!isset($has_footer) || $has_footer)
                @include('layouts.main.partials.footer')
            @endif

            <!-- ============================ Contact Button ================================== -->
{{--            <a class="contact-button @if(! $show_fixed_buttons) d-none d-md-flex @endif" href="#" data-toggle="modal" data-target="#autho-message">--}}
{{--                <i class="fa fa-phone-alt text-light"></i>--}}
{{--            </a>--}}
            <!-- ============================ Contact Button End ================================== -->

            @if(setting('app:auth-modal-active'))
                <!-- Log In Modal -->
                    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="registermodal" aria-hidden="true">
                        <div class="modal-dialog modal-xl login-pop-form" role="document">
                            <div class="modal-content overli" id="registermodal">
                                <div class="modal-body p-0">
                                    <div class="resp_log_wrap">
                                        <div class="resp_log_thumb" style="background:url({{ settingFilePath('app:auth-modal-img') }})no-repeat;"></div>
                                        <div class="resp_log_caption">
                                            <span class="mod-close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
                                            <div class="edlio_152">
                                                <ul class="nav nav-pills tabs_system center" id="pills-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="pills-login-tab" data-toggle="pill" href="#pills-login" role="tab" aria-controls="pills-login" aria-selected="true"><i class="fas fa-sign-in-alt ml-2"></i>ورود</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="pills-signup-tab" data-toggle="pill" href="#pills-signup" role="tab" aria-controls="pills-signup" aria-selected="false"><i class="fas fa-user-plus ml-2"></i>ثبت نام</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="pills-login-tab">
                                                    <x-auth.login></x-auth.login>
                                                </div>
                                                <div class="tab-pane fade" id="pills-signup" role="tabpanel" aria-labelledby="pills-signup-tab">
                                                    <x-auth.register></x-auth.register>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Modal -->
            @endif

            <!-- Send Message -->
                <div class="modal fade" id="autho-message" tabindex="-1" role="dialog" aria-labelledby="authomessage" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
                        <div class="modal-content" id="authomessage">
                            <span class="mod-close" data-dismiss="modal" aria-hidden="true"><i class="ti-close"></i></span>
                            <div class="modal-body">
                                <h4 class="modal-header-title">@lang('title.contact-us')</h4>
                                <div class="login-form">
                                    @include('main.partials.contact-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

                <a id="back2Top" class="top-scroll @if(! $show_fixed_buttons) d-none d-md-inline @endif" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
        </div>

        <share-modal></share-modal>
    </div>

    @include('layouts.main.partials.script')

</body>

</html>
