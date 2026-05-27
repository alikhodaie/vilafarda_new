@props(['home'])

@php
    $safetiesById = $home->safeties->keyBy('id');
@endphp

<div class="card-mobile mb-3">
    <h5 class="text-mobile-primary mb-3">
        <i class="bi bi-shield-check me-2"></i>
        ایمنی
    </h5>
    <p class="text-mobile-muted mb-3">موارد ایمنی موجود در اقامتگاه را انتخاب کنید.</p>

    <div class="mobile-safety-list">
        @foreach(\App\Models\Safety::getFromCache() as $index => $safety)
            @php
                $checked = collect(old('safeties', []))->contains(fn ($s) => isset($s['id']) && (int) $s['id'] === $safety->id)
                    || $home->safeties->contains($safety->id);
                $description = old("safeties.$index.description", $safetiesById->get($safety->id)?->pivot?->description ?? '');
            @endphp
            <div class="mobile-safety-item">
                <label class="mobile-check-label" for="safety-{{ $safety->id }}">
                    <input type="checkbox"
                           id="safety-{{ $safety->id }}"
                           class="mobile-check-input safety-toggle"
                           name="safeties[{{ $index }}][id]"
                           value="{{ $safety->id }}"
                           data-target="safety-desc-wrap-{{ $safety->id }}"
                           @if($checked) checked @endif>
                    <span class="mobile-check-title">{{ $safety->title }}</span>
                </label>
                <div class="mobile-safety-desc-wrap @unless($checked) d-none @endunless" id="safety-desc-wrap-{{ $safety->id }}">
                    <input type="text"
                           id="safety-desc-{{ $safety->id }}"
                           class="form-control form-control-mobile safety-desc-input"
                           name="safeties[{{ $index }}][description]"
                           placeholder="{{ $safety->placeholder ?: 'توضیح تکمیلی (اختیاری)' }}"
                           value="{{ $description }}">
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        <label for="more_safety" class="form-label-mobile">موارد بیشتر</label>
        <textarea name="more_safety" id="more_safety" class="form-control form-control-mobile" rows="2"
                  placeholder="سایر نکات ایمنی">{{ old('more_safety', $home->more_safety) }}</textarea>
    </div>
</div>
