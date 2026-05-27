@php
    use App\Models\Home;

    $hasCancelPolicy = $home->reject_policy
        && isset(Home::REJECT_POLICIES[$home->reject_policy]);
    $cancelDescription = $hasCancelPolicy
        ? Home::getRejectPolicyDescription($home->reject_policy)
        : '';
    $cancelLines = $cancelDescription !== ''
        ? array_values(array_filter(array_map('trim', preg_split('/\.\s+/u', rtrim((string) $cancelDescription, '.')))))
        : [];
@endphp

@if($hasCancelPolicy && count($cancelLines))
    @if(($layout ?? 'desktop') === 'mobile')
        <hr class="home-detail-divider">

        <section id="cancel-policy" class="home-detail-section">
            <h3 class="home-detail-section__title">
                <i class="bi bi-x-circle"></i>
                @lang('title.cancel_rule_reserve')
            </h3>
            <p class="home-cancel-policy__type">
                سیاست لغو: <strong>{{ $home->rejectPolicy() }}</strong>
            </p>
            <ul class="home-cancel-policy__list">
                @foreach($cancelLines as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        </section>
    @else
        <div class="property_block_wrap mb-1" id="cancel-policy">
            <div class="property_block_wrap_header">
                <h4 class="property_block_title">@lang('title.cancel_rule_reserve')</h4>
            </div>
            <div class="block-body">
                <p class="text-muted small mb-2">
                    سیاست لغو: <strong>{{ $home->rejectPolicy() }}</strong>
                </p>
                <ul class="mb-0 ps-3" style="line-height: 1.75; color: #555;">
                    @foreach($cancelLines as $line)
                        <li class="mb-2">{{ $line }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endif
