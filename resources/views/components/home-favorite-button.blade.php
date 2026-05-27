@props([
    'home',
    'class' => '',
])

@php
    $isFavorite = $home->isFavorite();
@endphp

<button
    type="button"
    class="home-favorite-btn home-favorite-btn--overlay {{ $isFavorite ? 'is-active' : '' }} {{ $class }}"
    data-home-id="{{ $home->id }}"
    onclick="if(window.HomeFavorite){window.HomeFavorite.onClick(this,event);}"
    aria-label="{{ $isFavorite ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها' }}"
    aria-pressed="{{ $isFavorite ? 'true' : 'false' }}"
>
    <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }}" aria-hidden="true"></i>
</button>
