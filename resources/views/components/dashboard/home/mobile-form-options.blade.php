@props(['home'])

@php
    $selectedOptions = collect(old('options', $home->options->pluck('id')->all()));
@endphp


    <div class="card-mobile mb-3">
        <h5 class="text-mobile-primary mb-3">
            <i class="bi bi-grid me-2"></i>
            امکانات
        </h5>
        <p class="text-mobile-muted mb-3">امکانات موجود در اقامتگاه را تیک بزنید.</p>

        <div class="mobile-option-grid">
            @foreach(\App\Models\Option::getFromCache() as $option)
                <label class="mobile-option-chip" for="option-{{ $option->id }}">
                    <input type="checkbox"
                           id="option-{{ $option->id }}"
                           class="form-check-input"
                           name="options[]"
                           value="{{ $option->id }}"
                           @if($selectedOptions->contains($option->id)) checked @endif>
                    <span class="mobile-option-chip-body">
                        <x-option-icon :option="$option" :size="22" />
                        <span class="mobile-option-chip-title">{{ $option->title }}</span>
                    </span>
                </label>
            @endforeach
        </div>
        @error('options')
            <div class="text-danger mt-2" style="font-size: 12px;">{{ $message }}</div>
        @enderror
        @error('options.*')
            <div class="text-danger mt-2" style="font-size: 12px;">{{ $message }}</div>
        @enderror
    </div>
