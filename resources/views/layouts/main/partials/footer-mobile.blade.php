@php
    $footer = footerSettings();
    $navLinks = footerNavbarLinks();
    $appName = config('app.name');
@endphp
<footer class="mobile-site-footer d-lg-none" aria-label="فوتر سایت">
    <div class="mobile-site-footer__inner">
        <section class="mobile-site-footer__section" aria-labelledby="footer-install-title">
            <h2 id="footer-install-title" class="mobile-site-footer__heading">نصب اپلیکیشن {{ $appName }}</h2>
            <div class="mobile-site-footer__install-grid">
                <a href="{{ route('main.add-to-home.ios') }}" class="mobile-site-footer__install-btn">
                    <i class="bi bi-apple" aria-hidden="true"></i>
                    <span class="mobile-site-footer__install-label">افزودن به صفحه اصلی</span>
                    <span class="mobile-site-footer__install-platform">آیفون</span>
                </a>
                <a href="{{ route('main.add-to-home.android') }}" class="mobile-site-footer__install-btn">
                    <i class="bi bi-android2" aria-hidden="true"></i>
                    <span class="mobile-site-footer__install-label">افزودن به صفحه اصلی</span>
                    <span class="mobile-site-footer__install-platform">اندروید</span>
                </a>
            </div>
        </section>

        @if(!empty($navLinks))
            <section class="mobile-site-footer__section" aria-labelledby="footer-nav-title">
                <h2 id="footer-nav-title" class="mobile-site-footer__heading">لینک‌های دسترسی</h2>
                <ul class="mobile-site-footer__access-links">
                    @foreach($navLinks as $link)
                        <li>
                            <a href="{{ $link['link'] }}">{{ $link['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif

        @php
            $socialsWithLink = collect($footer['socials'] ?? [])->filter(fn ($s) => !empty($s['link']))->values();
        @endphp
        @if($socialsWithLink->isNotEmpty())
            <section class="mobile-site-footer__section" aria-labelledby="footer-social-title">
                <h2 id="footer-social-title" class="mobile-site-footer__heading">با ما همراه شوید</h2>
                <div class="mobile-site-footer__social-row">
                    @foreach($socialsWithLink as $social)
                        @php($platform = footerSocialPlatform($social))
                        <a href="{{ $social['link'] }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="mobile-site-footer__social-card mobile-site-footer__social-card--{{ $platform }}"
                           title="{{ $social['title'] ?? '' }}">
                            <span class="mobile-site-footer__social-card-icon" aria-hidden="true">
                                @php
                                    $socialIconUrl = (($social['icon_type'] ?? 'font') === 'image' && !empty($social['icon']))
                                        ? footerSocialIconUrl($social)
                                        : null;
                                @endphp
                                @if($socialIconUrl)
                                    <img src="{{ $socialIconUrl }}" alt="" width="28" height="28" loading="lazy">
                                @else
                                    <i class="bi {{ footerSocialIconClass($social) }}"></i>
                                @endif
                            </span>
                            @if(!empty($social['follower_count']))
                                <span class="mobile-site-footer__social-card-count">{{ $social['follower_count'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        @if(!empty($footer['enamad_url']) && !empty($footer['enamad_image_url']))
            <section class="mobile-site-footer__section" aria-labelledby="footer-trust-title">
                <h2 id="footer-trust-title" class="mobile-site-footer__heading">{{ footerTrustSectionTitle() }}</h2>
                <div class="mobile-site-footer__trust">
                    <a href="{{ $footer['enamad_url'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       referrerpolicy="origin"
                       class="mobile-site-footer__trust-badge">
                        <img src="{{ $footer['enamad_image_url'] }}"
                             alt="@lang('title.footer_enamad')"
                             loading="lazy"
                             referrerpolicy="origin">
                    </a>
                </div>
            </section>
        @endif

        <p class="mobile-site-footer__copyright mb-0">
            &copy; {{ jdate()->getYear() }} {{ $appName }}
        </p>
    </div>
</footer>
