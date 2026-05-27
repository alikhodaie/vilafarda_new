@if(! empty($deadline))
    <div class="rent-deadline-card" role="timer" aria-live="polite">
        <p class="rent-deadline-card__label">
            <i class="bi bi-clock" aria-hidden="true"></i>
            {{ $label }}
        </p>
        <count-down-timer
            time="{{ $deadline->toIso8601String() }}"
            prop_now="{{ now()->toIso8601String() }}"
            color="#c0392b"
            text="{{ $label }}"
            text_expired="@lang('text.time_expired')"
        ></count-down-timer>
    </div>
@endif
