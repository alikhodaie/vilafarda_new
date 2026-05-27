@php
    $faqItems = $landingPage->faqItems();
@endphp

<div class="page-title" style="background:#f4f4f4 url({{ asset('assets/img/slider-2.jpg') }});" data-overlay="5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="breadcrumbs-wrap">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('main.index') }}">{{ config('app.name') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('main.homes.index') }}">@lang('title.homes')</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $landingPage->title }}</li>
                    </ol>
                    <h1 class="breadcrumb-title mb-0">{{ $landingPage->title }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>

@php($filterLabels = $landingPage->filterSummaryLabels())
@if($filterLabels !== [])
    <section class="pt-3 pb-0">
        <div class="container">
            <div class="d-flex flex-wrap gap-2">
                @foreach($filterLabels as $label)
                    <span class="badge bg-light text-dark border">{{ $label }}</span>
                @endforeach
            </div>
        </div>
    </section>
@endif

@if($landingPage->intro)
    <section class="pt-4 pb-2">
        <div class="container">
            <div class="landing-intro text-muted">
                {!! $landingPage->intro !!}
            </div>
        </div>
    </section>
@endif

@if($faqItems !== [])
    <section class="pb-3">
        <div class="container">
            <h2 class="h5 mb-3">@lang('title.landing_faq')</h2>
            <div class="accordion" id="landingFaq">
                @foreach($faqItems as $index => $item)
                    <div class="accordion-item">
                        <h3 class="accordion-header" id="landing-faq-h-{{ $index }}">
                            <button class="accordion-button @if($index > 0) collapsed @endif" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#landing-faq-c-{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="landing-faq-c-{{ $index }}">
                                {{ $item['question'] }}
                            </button>
                        </h3>
                        <div id="landing-faq-c-{{ $index }}" class="accordion-collapse collapse @if($index === 0) show @endif"
                             aria-labelledby="landing-faq-h-{{ $index }}" data-bs-parent="#landingFaq">
                            <div class="accordion-body">
                                {!! nl2br(e($item['answer'])) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
