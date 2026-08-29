@php
    $footer = footerSettings();
    $navLinks = footerNavbarLinks();
    $appName = siteName();
    $showLogo = $showLogo ?? false;
    $footerClass = $footerClass ?? 'mobile-site-footer';
@endphp

<footer class="{{ $footerClass }}" aria-label="فوتر سایت">
    <div class="mobile-site-footer__inner">
        @if($showLogo)
            <div class="site-footer__logo-wrap">
                <img src="{{ settingFilePath('app:logo') }}"
                     class="site-footer__logo"
                     alt="{{ $appName }}"
                     loading="lazy"
                     decoding="async">
            </div>
        @endif

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
                    <span class="mobile-site-footer__install-label">دانلود اپلیکیشن</span>
                </a>
            </div>
        </section>

        <section class="mobile-site-footer__section" aria-labelledby="footer-sitelinks-title">
            <h2 id="footer-sitelinks-title" class="mobile-site-footer__heading">دسترسی سریع</h2>
            <ul class="mobile-site-footer__access-links">
                @foreach(prioritySitelinks() as $link)
                    <li>
                        <a href="{{ $link['url'] }}">{{ $link['name'] }}</a>
                    </li>
                @endforeach
            </ul>
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
                                @if(($social['icon_type'] ?? 'font') === 'image' && !empty($social['icon']))
                                    <img src="{{ footerSocialIconUrl($social) }}" alt="" width="28" height="28" loading="lazy">
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

        @if(footerEnamadHtml())
            <section class="mobile-site-footer__section" aria-labelledby="footer-trust-title">
                <h2 id="footer-trust-title" class="mobile-site-footer__heading">{{ footerTrustSectionTitle() }}</h2>
                <div class="mobile-site-footer__trust">
                    <div class="mobile-site-footer__trust-badge">{!! footerEnamadHtml() !!}</div>
                </div>
            </section>
        @endif

        <p class="mobile-site-footer__copyright mb-0">
            &copy; {{ jdate()->getYear() }} {{ $appName }}
        </p>
    </div>
</footer>
