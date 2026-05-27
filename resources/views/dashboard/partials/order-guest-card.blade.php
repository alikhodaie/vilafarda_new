@php
    $guest = $order->renter;
    $guestName = $guest?->first_name ?: ($guest?->full_name ?? 'مهمان');
    $guestMobile = $guest?->mobile ?? '';
    $guestTel = $guestMobile !== '' ? preg_replace('/\s+/', '', $guestMobile) : '';
@endphp

<div class="rent-host-card">
    <h3 class="rent-host-card__title">مهمان</h3>

    <div class="rent-host-card__profile">
        <img src="{{ $guest?->avatar_path ?? \App\Models\User::getDefaultAvatar() }}"
             alt="{{ $guestName }}"
             class="rent-host-card__avatar"
             loading="lazy"
             decoding="async">
        <div class="rent-host-card__info">
            <span class="rent-host-card__name">{{ $guestName }}</span>
            @if($guestMobile !== '')
                <span class="rent-host-card__phone" dir="ltr">{{ persianNumber($guestMobile) }}</span>
            @endif
        </div>
    </div>

    @if($guestTel !== '')
        <a href="tel:{{ $guestTel }}" class="rent-host-card__call-btn">
            <i class="bi bi-telephone-fill" aria-hidden="true"></i>
            تماس با مهمان
        </a>
    @endif
</div>
