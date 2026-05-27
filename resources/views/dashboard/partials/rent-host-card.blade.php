@php
    $host = $rent->owner;
    $hostName = $host?->first_name ?: ($host?->full_name ?? 'میزبان');
    $hostMobile = $host?->mobile ?? '';
    $hostTel = $hostMobile !== '' ? preg_replace('/\s+/', '', $hostMobile) : '';
@endphp

<div class="rent-host-card">
    <h3 class="rent-host-card__title">میزبان اقامتگاه</h3>

    <div class="rent-host-card__profile">
        <img src="{{ $host?->avatar_path ?? \App\Models\User::getDefaultAvatar() }}"
             alt="{{ $hostName }}"
             class="rent-host-card__avatar"
             loading="lazy"
             decoding="async">
        <div class="rent-host-card__info">
            <span class="rent-host-card__name">{{ $hostName }}</span>
            @if($hostMobile !== '')
                <span class="rent-host-card__phone" dir="ltr">{{ persianNumber($hostMobile) }}</span>
            @endif
        </div>
    </div>

    @if($hostTel !== '')
        <a href="tel:{{ $hostTel }}" class="rent-host-card__call-btn">
            <i class="bi bi-telephone-fill" aria-hidden="true"></i>
            تماس با میزبان
        </a>
    @endif
</div>
