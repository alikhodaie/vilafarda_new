<!-- ============================ Call To Action ================================== -->
{{--<section class="theme-bg call_action_wrap-wrap">--}}
{{--    <div class="container">--}}
{{--        <div class="row">--}}
{{--            <div class="col-lg-12">--}}

{{--                <div class="call_action_wrap">--}}
{{--                    <div class="call_action_wrap-head">--}}
{{--                        <h3>{{ setting('app:contact-title') }}</h3>--}}
{{--                        <span>{{ setting('app:contact-description') }}</span>--}}
{{--                    </div>--}}
{{--                    <a href="#" class="btn btn-call_action_wrap" data-toggle="modal" data-target="#autho-message">{{ setting('app:contact-btn-text') }}</a>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}

<!-- ============================ Call To Action End ================================== -->

<!-- ============================ Footer Start ================================== -->
<footer class="light-footer mt-5 pt-5">
    <div class="footer-middle">
        <div class="container">
            <div class="row">

                <div class="col-12 col-md-5">
                    <div class="footer_widget">
                        <img src="{{ settingFilePath('app:logo') }}" class="img-footer small mb-2" alt="{{ config('app.name') }}" width="120" height="40" loading="lazy" decoding="async" />
                        <h4 class="extream mb-3">{{ setting('app:newsletter-title') }}</h4>
                        <p>{{ setting('app:newsletter-description') }}</p>
                        <div class="foot-news-last">
                            <newsletter
                                route="{{ route('main.newsletter.subscribe') }}"
                                email_text="@lang('title.email')"
                                subscribe_text="@lang('title.subscribe')"
                            ></newsletter>
                        </div>
                    </div>
                </div>

                @php
                    $footer = footerSettings();
                @endphp
                <div class="col-12 col-md-5 mr-auto">
                    <div class="row">

                        <div class="col-lg-4 col-md-4">
                            <div class="footer_widget">
                                <h4 class="widget_title">{{ $footer['first_menu_title'] }}</h4>
                                <ul class="footer-menu">
                                    @foreach($footer['first_menu'] ?? [] as $item)
                                        <li><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="footer_widget">
                                <h4 class="widget_title">{{ $footer['second_menu_title'] }}</h4>
                                <ul class="footer-menu">
                                    @foreach($footer['second_menu'] ?? [] as $item)
                                        <li><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4">
                            <div class="footer_widget">
                                <h4 class="widget_title">{{ $footer['third_menu_title'] }}</h4>
                                <ul class="footer-menu">
                                    @foreach($footer['third_menu'] ?? [] as $item)
                                        <li><a href="{{ $item['link'] }}">{{ $item['title'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="footer_widget">
                        @if(!empty($footer['enamad_url']) && !empty($footer['enamad_image_url']))
                            <a referrerpolicy="origin" target="_blank" rel="noopener noreferrer" href="{{ $footer['enamad_url'] }}">
                                <img referrerpolicy="origin" src="{{ $footer['enamad_image_url'] }}" alt="@lang('title.footer_enamad')" style="cursor:pointer; max-width:200px; height:auto;">
                            </a>
                        @endif
                        @if(!empty($footer['socials']))
                            <div class="d-flex justify-content-between mt-3 flex-wrap gap-2">
                                @foreach($footer['socials'] as $social)
                                    @if(empty($social['link']))
                                        @continue
                                    @endif
                                    <a href="{{ $social['link'] }}" target="_blank" rel="noopener noreferrer" title="{{ $social['title'] ?? '' }}">
                                        @php
                                            $socialIconUrl = (($social['icon_type'] ?? 'font') === 'image' && !empty($social['icon']))
                                                ? footerSocialIconUrl($social)
                                                : null;
                                        @endphp
                                        @if($socialIconUrl)
                                            <img src="{{ $socialIconUrl }}" alt="{{ $social['title'] ?? '' }}" width="30" height="30" style="object-fit:contain;">
                                        @else
                                            <i class="bi {{ footerSocialIconClass($social) }}" style="font-size:1.75rem;"></i>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ============================ Footer End ================================== -->
