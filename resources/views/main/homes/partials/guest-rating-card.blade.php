@if($home->hasGuestReviews())
    @php $stars = $home->guestRatingStars(); @endphp
    <div class="foot-rates {{ $class ?? '' }}">
        <span class="elio_rate good">{{ $home->guestRatingScoreForDisplay() }}</span>
        <div class="_rate_stio {{ $starsWrapperClass ?? '' }}">
            @for($i = 0; $i < $stars; $i++)
                <i class="fa fa-star"></i>
            @endfor
            @for($i = $stars; $i < 5; $i++)
                <i class="far fa-star"></i>
            @endfor
        </div>
        <span style="font-size: {{ $commentFontSize ?? '11px' }}; margin-right: 1px;">
            ({{ __('title.count_comments', ['count' => persianNumber($home->count_comments)]) }})
        </span>
    </div>
@endif
