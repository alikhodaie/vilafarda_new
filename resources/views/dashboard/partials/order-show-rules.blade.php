@php
    $home = ($order ?? $rent)->home;
@endphp

<div class="rent-rules-card">
    <h3 class="rent-rules-card__title">
        <i class="bi bi-journal-text" aria-hidden="true"></i>
        مقررات اقامتگاه
    </h3>

    @if(!empty($home->rules))
        <p class="rent-rules-card__body">{{ $home->rules }}</p>
    @else
        <p class="rent-rules-card__empty">مقررات خاصی برای این اقامتگاه ثبت نشده است.</p>
    @endif

    @if($cancelPolicy)
        <h4 class="rent-rules-card__subtitle">مقررات لغو رزرو ({{ $cancelPolicy['title'] }})</h4>
        <ul class="rent-rules-card__list">
            @foreach($cancelPolicy['lines'] as $line)
                <li>{{ $line }}</li>
            @endforeach
        </ul>
    @endif
</div>
