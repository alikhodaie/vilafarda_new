@props([
    'home',
    'src',
    'width' => 400,
    'height' => 200,
    'eager' => false,
])

<img src="{{ $src }}"
     alt="{{ homeImageAlt($home) }}"
     width="{{ $width }}"
     height="{{ $height }}"
     decoding="async"
     {{ $attributes->merge(['class' => 'img-fluid mx-auto home-listing-card__img']) }}
     @if($eager)
         fetchpriority="high"
         loading="eager"
     @else
         loading="lazy"
     @endif
/>
