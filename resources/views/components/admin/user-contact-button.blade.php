@props(['home', 'profile' => null])

@php
    $user = $home->user;
    $profile ??= app(\App\Services\HostAdminProfileService::class)->buildForHome($home);
    $orderResponse = $profile['order_response'] ?? [];
    $guestReviews = $profile['guest_reviews'] ?? [];
@endphp

<button type="button"
        class="btn btn-link btn-sm p-0 text-500 admin-user-contact-trigger"
        data-bs-toggle="modal"
        data-bs-target="#adminUserContactModal"
        data-home-id="{{ $home->id }}"
        data-home-name="{{ $home->name }}"
        data-user-id="{{ $user->id }}"
        data-user-name="{{ $user->full_name }}"
        data-user-mobile="{{ $user->mobile ?? '' }}"
        data-user-email="{{ $user->email ?? '' }}"
        @if(! empty($profile['edit_url']))
        data-user-edit-url="{{ $profile['edit_url'] }}"
        @endif
        data-orders-accepted="{{ (int) ($orderResponse['accepted'] ?? 0) }}"
        data-orders-rejected="{{ (int) ($orderResponse['rejected'] ?? 0) }}"
        data-orders-approval-percent="{{ $orderResponse['approval_percent_display'] ?? '' }}"
        data-orders-rejection-percent="{{ $orderResponse['rejection_percent_display'] ?? '' }}"
        data-orders-tier-label="{{ $orderResponse['tier_label'] ?? '' }}"
        data-orders-tier-color="{{ $orderResponse['tier_color'] ?? 'secondary' }}"
        data-guest-reviews-count="{{ (int) ($guestReviews['count'] ?? 0) }}"
        data-guest-reviews-score="{{ $guestReviews['average_score_display'] ?? '' }}"
        data-guest-tier-label="{{ $guestReviews['tier_label'] ?? '' }}"
        data-guest-tier-color="{{ $guestReviews['tier_color'] ?? 'secondary' }}"
        title="اطلاعات تماس"
        aria-label="اطلاعات تماس {{ $user->full_name }}">
    <span class="fas fa-address-card"></span>
</button>
