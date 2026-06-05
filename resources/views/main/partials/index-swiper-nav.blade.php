@php
    $navId = $navId ?? null;
    $side = $side ?? 'both';
    $prevId = $navId ? $navId . '-carousel-prev' : null;
    $nextId = $navId ? $navId . '-carousel-next' : null;
@endphp

@if($side === 'both' || $side === 'prev')
    <button type="button"
            @if($prevId) id="{{ $prevId }}" @endif
            class="index-carousel-nav index-carousel-nav--prev d-none d-lg-inline-flex"
            data-index-carousel-nav="prev"
            aria-label="اسکرول به راست">
        <i class="fas fa-chevron-right" aria-hidden="true"></i>
    </button>
@endif

@if($side === 'both' || $side === 'next')
    <button type="button"
            @if($nextId) id="{{ $nextId }}" @endif
            class="index-carousel-nav index-carousel-nav--next d-none d-lg-inline-flex"
            data-index-carousel-nav="next"
            aria-label="اسکرول به چپ">
        <i class="fas fa-chevron-left" aria-hidden="true"></i>
    </button>
@endif
