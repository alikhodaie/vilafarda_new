@props(['home'])

@php
    $selectedHealths = collect(old('healths', $home->healths->pluck('id')->all()));
@endphp

<div class="card-mobile mb-3">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-droplet me-2"></i>
        اقلام بهداشتی
    </h5>

    <div class="mobile-check-list mb-3">
        @foreach(\App\Models\Health::getFromCache() as $health)
            <label class="mobile-check-label" for="health-{{ $health->id }}">
                <input type="checkbox"
                       id="health-{{ $health->id }}"
                       class="mobile-check-input"
                       name="healths[]"
                       value="{{ $health->id }}"
                       @if($selectedHealths->contains($health->id)) checked @endif>
                <span class="mobile-check-title">{{ $health->title }}</span>
            </label>
        @endforeach
    </div>

    <div>
        <label for="more_health" class="form-label-mobile">موارد بیشتر</label>
        <textarea name="more_health" id="more_health" class="form-control form-control-mobile" rows="2"
                  placeholder="سایر اقلام بهداشتی">{{ old('more_health', $home->more_health) }}</textarea>
    </div>
</div>
