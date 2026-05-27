@php
    $score = max(0, min(5, (int) ($score ?? 0)));
    $max = 5;
@endphp
<span class="admin-rating-stars d-inline-flex align-items-center gap-1">
    @for($i = 1; $i <= $max; $i++)
        <i class="bi bi-star-fill {{ $i <= $score ? 'text-warning' : 'text-muted opacity-25' }}"
           style="font-size: {{ $size ?? '1rem' }};"></i>
    @endfor
    @if($showValue ?? true)
        <span class="text-muted small ms-1">{{ persianNumber($score) }} از {{ persianNumber($max) }}</span>
    @endif
</span>
