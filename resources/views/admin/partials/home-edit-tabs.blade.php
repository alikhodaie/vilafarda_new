@php
    $openTab = old('open_tab', request('open_tab') ?: session('open_tab') ?: 'tab-admin');

    $tabs = [
        'tab-admin' => 'مدیریت',
        'tab-media' => 'رسانه',
        'tab-basic' => 'اطلاعات',
        'tab-rooms' => 'اتاق',
        'tab-location' => 'مکان',
        'tab-pricing' => 'قیمت',
        'tab-discount' => 'تخفیف',
        'tab-options' => 'امکانات',
        'tab-health' => 'بهداشت',
        'tab-safety' => 'ایمنی',
        'tab-rules' => 'قوانین',
    ];
@endphp

<nav class="admin-home-edit-tabs mb-3" id="adminHomeEditTabs" aria-label="بخش‌های ویرایش اقامتگاه">
    <a href="{{ route('admin.homes.date.show', $home) }}" class="admin-home-edit-tab-pill text-decoration-none">
        <span class="fas fa-calendar-alt ms-1"></span> تقویم
    </a>
    @foreach($tabs as $target => $label)
        <button type="button"
                class="admin-home-edit-tab-pill{{ $openTab === $target ? ' active' : '' }}"
                data-target="{{ $target }}"
                aria-selected="{{ $openTab === $target ? 'true' : 'false' }}">
            {{ $label }}
        </button>
    @endforeach
</nav>
