@php
    use App\Support\HomeReviewCriteria;

    $overallDisplay = persianNumber($ratingsSummary['overall'], fmod($ratingsSummary['overall'], 1) ? 1 : 0);
    $starCount = $ratingsSummary['overall_stars'];
@endphp

@if($ratingsSummary['has_data'])
    <section class="home-ratings-summary home-detail-section" aria-label="امتیازات مهمانان">
        <h3 class="home-ratings-summary__header">
            امتیازات ({{ persianNumber($ratingsSummary['total']) }} مهمان)
            <i class="bi bi-info-circle"
               title="بر اساس نظرات تأییدشده مهمانان در یک سال اخیر"
               aria-hidden="true"></i>
        </h3>

        <div class="home-ratings-card">
            <div class="home-ratings-card__overview">
                <div class="home-ratings-score">
                    <div class="home-ratings-score__value">
                        {{ $overallDisplay }} از {{ persianNumber(5) }}
                    </div>
                    <div class="home-ratings-score__stars" aria-hidden="true">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill{{ $i <= $starCount ? '' : ' opacity-25' }}"
                               style="{{ $i > $starCount ? 'color:#ddd;' : '' }}"></i>
                        @endfor
                    </div>
                    <p class="home-ratings-score__note">بر اساس امتیازات ۱ سال اخیر</p>
                </div>

                <div class="home-ratings-distribution" aria-label="توزیع امتیازات">
                    @foreach([5, 4, 3, 2, 1] as $stars)
                        <div class="home-ratings-distribution__row">
                            <span class="home-ratings-distribution__mood" aria-hidden="true">
                                {{ HomeReviewCriteria::distributionMood($stars) }}
                            </span>
                            <div class="home-ratings-distribution__bar">
                                <div class="home-ratings-distribution__fill"
                                     style="width: {{ $ratingsSummary['distribution'][$stars] }}%;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($ratingsSummary['has_criteria'])
                <div class="home-ratings-criteria">
                    @foreach($ratingsSummary['criteria'] as $key => $average)
                        <article class="home-ratings-criterion">
                            <div class="home-ratings-criterion__top">
                                <span class="home-ratings-criterion__score">
                                    <i class="bi bi-star-fill" aria-hidden="true"></i>
                                    {{ persianNumber($average, fmod($average, 1) ? 1 : 0) }}
                                </span>
                                <i class="bi {{ HomeReviewCriteria::iconFor($key) }} home-ratings-criterion__icon"
                                   aria-hidden="true"></i>
                            </div>
                            <p class="home-ratings-criterion__label">
                                {{ HomeReviewCriteria::shortLabelFor($key) }}
                            </p>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        <hr class="home-ratings-divider">
        <h3 class="home-ratings-comments-title">نظرات</h3>
    </section>
@endif
