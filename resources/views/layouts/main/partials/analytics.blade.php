@php
    use App\Services\AnalyticsService;
@endphp

@if(AnalyticsService::shouldTrack())
    @php($ga4Id = AnalyticsService::measurementId())
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', @json($ga4Id), {
            anonymize_ip: true,
            send_page_view: true
        });
    </script>
    @stack('analytics-events')
@endif
