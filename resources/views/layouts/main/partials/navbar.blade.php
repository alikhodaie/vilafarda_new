<!-- ============================================================== -->
<!-- Top header  -->
<!-- ============================================================== -->
<!-- Start Navigation -->
<div class="header header-transparent change-logo">
    <div class="container">
        <nav id="navigation" class="navigation navigation-landscape">
            <div class="row">
                <div class="col-8 col-lg-12">
                    <div class="nav-header">
                        <a class="nav-brand static-logo" href="{{ route('main.index') }}">
                            <img src="{{ settingFilePath('app:logo-light') }}" class="logo" alt="{{ config('app.name') }}" />
                        </a>
                        <a class="nav-brand fixed-logo" href="{{ route('main.index') }}">
                            <img src="{{ settingFilePath('app:logo') }}" class="logo" alt="{{ config('app.name') }}" />
                        </a>
                        <div class="nav-toggle"></div>
                    </div>
                    <div class="nav-menus-wrapper" style="transition-property: none;">
                        <ul class="nav-menu">
                            @foreach(navbar() as $item)
                                <li>
                                    @if($item->children->isEmpty())
                                        <a href="{{ $item->link }}">
                                            {{ $item->title }}
                                        </a>
                                    @else
                                        <a href="#">
                                            {{ $item->title }}
                                            <span class="submenu-indicator"></span>
                                        </a>
                                        <ul class="nav-dropdown nav-submenu">
                                            @foreach($item->children as $child)
                                                <li><a href="{{ $child->link }}">{{ $child->title }}</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <ul class="nav-menu nav-menu-social align-to-right">
                            @auth()
                                <li>
                                    @include('layouts.dashboard.partials.menu')
                                </li>
                            @else
                                <li>
                                    <a class="alio_green"
                                       @if(setting('app:auth-modal-active'))
                                       href="javascript:" data-toggle="modal" data-target="#login"
                                       @else
                                       href="{{ route('main.login') }}"
                                        @endif>
                                        <i class="fas fa-sign-in-alt ml-1"></i><span class="dn-lg">@lang('title.login')</span>
                                    </a>
                                </li>
                            @endif
                            <li class="add-listing">
                                <a href="{{ route('main.submit.home') }}"  class="theme-cl">
                                    <i class="fas fa-plus-circle ml-1"></i>@lang('title.submit_home')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-4 d-lg-none">
                    <div class="nav-menu align-to-right">
                        @auth()
                            @include('layouts.dashboard.partials.menu')
                        @else
                            <a class="btn btn-link mt-3"
                               @if(setting('app:auth-modal-active'))
                               href="javascript:" data-toggle="modal" data-target="#login"
                               @else
                               href="{{ route('main.login') }}"
                                @endif>
                                <i class="fas fa-sign-in-alt ml-1"></i>
                                <span class="dn-lg">@lang('title.login')</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- End Navigation -->
<div class="clearfix"></div>
<!-- ============================================================== -->
<!-- Top header  -->
<!-- ============================================================== -->
