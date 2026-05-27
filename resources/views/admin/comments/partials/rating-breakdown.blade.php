@php
    use App\Support\HomeReviewCriteria;

    $scores = $comment->orderedRatingScores();
    $averageRounded = $comment->averageRatingScore();
@endphp

<div class="col-12 mb-4">
    <label class="form-label d-block">@lang('title.score')</label>

    @if($comment->hasDetailedRatings())
        <div class="border rounded p-3 bg-light">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3 pb-3 border-bottom">
                <span class="fw-bold">میانگین امتیاز</span>
                <div class="d-flex align-items-center gap-2">
                    @include('admin.comments.partials.rating-stars', [
                        'score' => $averageRounded,
                        'size' => '1.15rem',
                    ])
                    <span class="badge bg-warning text-dark">{{ $comment->averageRatingScoreLabel() }}</span>
                </div>
            </div>

            <div class="row g-3">
                @foreach($scores as $key => $value)
                    <div class="col-12 col-lg-6">
                        <div class="d-flex flex-column gap-1">
                            <span class="small text-700">{{ HomeReviewCriteria::labelFor($key) }}</span>
                            @include('admin.comments.partials.rating-stars', [
                                'score' => (int) $value,
                                'size' => '0.95rem',
                            ])
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif((int) $comment->score > 0)
        <div class="border rounded p-3 bg-light">
            @include('admin.comments.partials.rating-stars', [
                'score' => (int) $comment->score,
                'size' => '1.15rem',
            ])
        </div>
    @else
        <p class="text-muted mb-0 small">امتیازی ثبت نشده است.</p>
    @endif
</div>
