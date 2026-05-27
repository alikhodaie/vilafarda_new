@extends('layouts.main.main_mobile', ['title' => $landingPage->title, 'landingPage' => $landingPage, 'homes' => $homes])

@section('content')
    @include('layouts.main.partials.navbar-mobile')

    <div class="px-3 pt-3 pb-2" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #fff; border-radius: 0 0 16px 16px;">
        <nav aria-label="breadcrumb" class="mb-2" style="font-size: 12px; opacity: 0.85;">
            <a href="{{ route('main.index') }}" class="text-white text-decoration-none">{{ config('app.name') }}</a>
            <span class="mx-1">/</span>
            <a href="{{ route('main.homes.index') }}" class="text-white text-decoration-none">@lang('title.homes')</a>
            <span class="mx-1">/</span>
            <span>{{ $landingPage->title }}</span>
        </nav>
        <h1 class="fw-bold mb-2" style="font-size: 20px;">{{ $landingPage->title }}</h1>
        @php($filterLabels = $landingPage->filterSummaryLabels())
        @if($filterLabels !== [])
            <div class="d-flex flex-wrap gap-1 mt-2">
                @foreach($filterLabels as $label)
                    <span class="badge rounded-pill" style="background: rgba(255,255,255,0.2); font-size: 11px;">{{ $label }}</span>
                @endforeach
            </div>
        @endif
        @if($landingPage->intro)
            <div class="landing-intro-mobile mt-2" style="font-size: 14px; opacity: 0.92; line-height: 1.7;">
                {!! \Illuminate\Support\Str::limit(strip_tags($landingPage->intro), 280) !!}
            </div>
        @endif
    </div>

    @php($faqItems = $landingPage->faqItems())
    @if($faqItems !== [])
        <div class="px-3 py-3">
            <h2 class="h6 mb-2">@lang('title.landing_faq')</h2>
            @foreach($faqItems as $item)
                <details class="mb-2 border rounded p-2">
                    <summary class="fw-semibold" style="cursor: pointer;">{{ $item['question'] }}</summary>
                    <p class="mb-0 mt-2 small text-muted">{!! nl2br(e($item['answer'])) !!}</p>
                </details>
            @endforeach
        </div>
    @endif

    <div class="px-3 pb-2">
        <p class="text-muted small mb-0">
            {{ persianNumber($homes->total()) }} @lang('title.residence')
        </p>
    </div>

    <div class="row pt-2 px-2">
        @forelse($homes as $home)
            <div class="col-12 mb-4">
                @include('main.homes.partials.new-home-card', ['home' => $home, 'is_today' => $isTodayPrice])
            </div>
        @empty
            <div class="col-12 alert alert-danger text-center mx-2">@lang('text.empty search')</div>
        @endforelse
    </div>

    @if($homes->hasPages())
        <div class="d-flex justify-content-center pb-4 px-3">
            {{ $homes->links() }}
        </div>
    @endif
@endsection
