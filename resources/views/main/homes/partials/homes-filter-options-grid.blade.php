@php
    $optionsIdPrefix = $optionsIdPrefix ?? 'default';
    $filterOptionsList = $filterOptions ?? \App\Models\Option::getFromCache();
    $selectedOptionsList = $selectedOptions ?? [];
    $optionsVisibleLimit = 8;
    $visibleOptions = $filterOptionsList->take($optionsVisibleLimit);
    $moreOptions = $filterOptionsList->slice($optionsVisibleLimit)->values();
    $hasMoreOptions = $moreOptions->isNotEmpty();
    $morePanelId = 'filterOptionsMore-' . $optionsIdPrefix;
    $hasSelectedInMore = $hasMoreOptions && $moreOptions->contains(fn ($option) => in_array((int) $option->id, $selectedOptionsList, true));
@endphp

<div class="homes-filter-options-grid" data-filter-options-root>
    <div class="row g-2">
        @foreach($visibleOptions as $option)
            <div class="col-6">
                @include('main.homes.partials.homes-filter-option-item', [
                    'option' => $option,
                    'optionsIdPrefix' => $optionsIdPrefix,
                    'selectedOptionsList' => $selectedOptionsList,
                ])
            </div>
        @endforeach
    </div>

    @if($hasMoreOptions)
        <div
            id="{{ $morePanelId }}"
            class="homes-filter-options-more {{ $hasSelectedInMore ? 'is-open' : '' }}"
            @unless($hasSelectedInMore) hidden @endunless
        >
            <div class="row g-2 homes-filter-options-more__grid">
                @foreach($moreOptions as $option)
                    <div class="col-6">
                        @include('main.homes.partials.homes-filter-option-item', [
                            'option' => $option,
                            'optionsIdPrefix' => $optionsIdPrefix,
                            'selectedOptionsList' => $selectedOptionsList,
                        ])
                    </div>
                @endforeach
            </div>
        </div>

        <button
            type="button"
            class="homes-filter-options-more-btn"
            data-filter-options-toggle
            aria-controls="{{ $morePanelId }}"
            aria-expanded="{{ $hasSelectedInMore ? 'true' : 'false' }}"
        >
            <span data-filter-options-toggle-label>{{ $hasSelectedInMore ? 'نمایش کمتر' : 'موارد بیشتر' }}</span>
            <i class="bi {{ $hasSelectedInMore ? 'bi-chevron-up' : 'bi-chevron-down' }}" data-filter-options-toggle-icon aria-hidden="true"></i>
        </button>
    @endif
</div>
