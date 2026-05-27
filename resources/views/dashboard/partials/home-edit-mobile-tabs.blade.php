@php
    $mode = $mode ?? 'calendar';
    $openTab = request('open_tab') ?: session('open_tab');

    $tabTargetMap = [
        'basic' => 'tab-basic',
        'images' => 'tab-images',
        'rooms' => 'tab-rooms',
        'location' => 'tab-location',
        'pricing' => 'tab-pricing',
        'discount' => 'tab-discount',
        'options' => 'tab-options',
        'safety' => 'tab-safety',
        'health' => 'tab-health',
        'rules' => 'tab-rules',
        'document' => 'tab-document',
    ];
    $targetToKey = array_flip($tabTargetMap);

    $activeKey = $mode === 'calendar'
        ? 'calendar'
        : ($targetToKey[$openTab] ?? 'basic');

    $mainTabs = [
        'basic' => ['label' => 'اطلاعات', 'target' => 'tab-basic'],
        'images' => ['label' => 'تصاویر', 'target' => 'tab-images'],
        'rooms' => ['label' => 'اتاق', 'target' => 'tab-rooms'],
        'location' => ['label' => 'مکان', 'target' => 'tab-location'],
    ];

    $extraTabs = [
        'pricing' => ['label' => 'قیمت', 'target' => 'tab-pricing'],
        'options' => ['label' => 'امکانات', 'target' => 'tab-options'],
        'safety' => ['label' => 'ایمنی', 'target' => 'tab-safety'],
        'health' => ['label' => 'بهداشت', 'target' => 'tab-health'],
        'rules' => ['label' => 'قوانین', 'target' => 'tab-rules'],
        'document' => ['label' => 'مدارک', 'target' => 'tab-document'],
    ];
@endphp

<div class="mobile-edit-tabs mb-3" id="mobileEditTabs">
    @if($activeKey === 'calendar')
        <span class="tab-pill active">تقویم</span>
    @else
        <a href="{{ route('dashboard.homes.custom.date.show', $home) }}" class="tab-pill text-decoration-none">تقویم</a>
    @endif

    @if($mode === 'calendar')
        <a href="{{ route('dashboard.homes.edit', ['home' => $home, 'open_tab' => 'tab-discount']) }}"
           class="tab-pill text-decoration-none">تخفیف</a>
    @else
        <button type="button"
                class="tab-pill{{ $activeKey === 'discount' ? ' active' : '' }}"
                data-target="tab-discount">تخفیف</button>
    @endif

    @foreach($mainTabs as $key => $tab)
        @if($mode === 'calendar')
            <a href="{{ route('dashboard.homes.edit', ['home' => $home, 'open_tab' => $tab['target']]) }}"
               class="tab-pill text-decoration-none">{{ $tab['label'] }}</a>
        @else
            <button type="button"
                    class="tab-pill{{ $activeKey === $key ? ' active' : '' }}"
                    data-target="{{ $tab['target'] }}">{{ $tab['label'] }}</button>
        @endif
    @endforeach

    @if($mode === 'edit')
        @foreach($extraTabs as $key => $tab)
            <button type="button"
                    class="tab-pill{{ $activeKey === $key ? ' active' : '' }}"
                    data-target="{{ $tab['target'] }}">{{ $tab['label'] }}</button>
        @endforeach
    @endif
</div>
