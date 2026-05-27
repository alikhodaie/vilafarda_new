@php
    /** @var \App\Models\Home $home */
@endphp
<div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
    <div class="position-relative mb-3 home-card-image-wrap">
        <img src="{{ $home->cover_path }}" class="img-fluid rounded" alt="{{ $home->name }}"
             style="width: 100%; height: 200px; object-fit: cover;">
        <div class="home-card-status-badge">
            <span class="badge bg-success" style="font-size: 12px;">
                @if($home->status === 'accepted')
                    تایید شده
                @elseif($home->status === 'pending')
                    در انتظار تایید
                @elseif($home->status === 'rejected')
                    رد شده
                @else
                    {{ $home->status ?? 'فعال' }}
                @endif
            </span>
        </div>
    </div>

    <div class="mb-3">
        <h5 class="fw-bold mb-2" style="font-size: 16px; color: #333;">
            {{ $home->name }}
        </h5>
        <p class="text-muted mb-2" style="font-size: 14px;">
            <i class="bi bi-geo-alt me-1"></i>
            {{ $home->province->name ?? '' }} - {{ $home->city->name ?? '' }}
        </p>
        <p class="text-muted mb-0" style="font-size: 14px; line-height: 1.4;">
            {{ Str::limit(strip_tags($home->description), 100) }}
        </p>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6">
            <div class="d-flex align-items-center">
                <i class="bi bi-people text-primary me-2" style="font-size: 14px;"></i>
                <small class="text-muted" style="font-size: 12px;">
                    {{ $home->main_guest }} مهمان
                </small>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center">
                <i class="bi bi-house text-primary me-2" style="font-size: 14px;"></i>
                <small class="text-muted" style="font-size: 12px;">
                    {{ $home->typeLabel() }}
                </small>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center">
                <i class="bi bi-eye text-primary me-2" style="font-size: 14px;"></i>
                <small class="text-muted" style="font-size: 12px;">
                    {{ $home->views_count }} بازدید
                </small>
            </div>
        </div>
        <div class="col-6">
            <div class="d-flex align-items-center">
                <i class="bi bi-star text-warning me-2" style="font-size: 14px;"></i>
                <small class="text-muted" style="font-size: 12px;">
                    @if($home->hasGuestReviews())
                        {{ $home->guestRatingScoreForDisplay() }} ({{ persianNumber($home->count_comments) }} نظر)
                    @else
                        جدید
                    @endif
                </small>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="fw-bold" style="font-size: 16px; color: #D39D1A;">
                {{ $home->show_price ?? number_format($home->week_price ?? 0) . ' تومان' }}
            </span>
            <small class="text-muted d-block" style="font-size: 12px;">شبانه</small>
        </div>
        <a href="{{ route('main.homes.show', $home) }}" class="btn btn-primary" style="background: #D39D1A; border-color: #D39D1A; color: white; font-size: 14px; border-radius: 12px;">
            <i class="bi bi-eye me-1"></i>
            مشاهده
        </a>
    </div>
</div>
