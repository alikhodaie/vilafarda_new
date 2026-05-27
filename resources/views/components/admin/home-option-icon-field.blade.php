@props(['option' => null])

@php
    use App\Models\Option;
    use App\Support\BootstrapIconRegistry;

    $featuredIcons = BootstrapIconRegistry::featured();
    $iconType = old('icon_type', $option?->icon_type ?? Option::ICON_TYPE_FONT);
    $selectedClass = old('icon_class', ($option && $option->isFontIcon()) ? $option->icon : ($featuredIcons[0] ?? 'bi-house'));
    $imagePreview = ($option && ! $option->isFontIcon() && $option->icon) ? $option->icon_path : '';
    $isFont = $iconType === Option::ICON_TYPE_FONT;
    $totalIcons = count(BootstrapIconRegistry::all());
@endphp

<div
    class="col-12 mb-5 home-option-icon-field"
    data-home-option-icon-field
    data-all-icons='@json(BootstrapIconRegistry::all())'
    data-featured-icons='@json($featuredIcons)'
    data-search-aliases='@json(BootstrapIconRegistry::searchAliases())'
    data-selected-icon="{{ $selectedClass }}"
>
    <label class="form-label d-block mb-3">@lang('title.icon')</label>

    <div class="btn-group mb-4" role="group" aria-label="@lang('validation.attributes.icon_type')">
        <input type="radio" class="btn-check" name="icon_type" id="icon_type_font" value="{{ Option::ICON_TYPE_FONT }}" @checked($isFont)>
        <label class="btn btn-outline-primary" for="icon_type_font">آیکون از فونت</label>

        <input type="radio" class="btn-check" name="icon_type" id="icon_type_image" value="{{ Option::ICON_TYPE_IMAGE }}" @checked(! $isFont)>
        <label class="btn btn-outline-primary" for="icon_type_image">تصویر سفارشی</label>
    </div>

    <div data-icon-panel="font" class="{{ $isFont ? '' : 'd-none' }}">
        <input type="hidden" name="icon_class" id="icon_class" value="{{ $selectedClass }}">

        <div class="text-center mb-3 p-3 border rounded bg-light">
            <i id="home-option-icon-preview" class="bi {{ $selectedClass }} text-primary" style="font-size: 4rem; line-height: 1;" aria-hidden="true"></i>
            <div class="text-muted small mt-2" id="home-option-icon-label">{{ $selectedClass }}</div>
        </div>

        <div class="mb-2">
            <input type="search" class="form-control" id="home-option-icon-search" placeholder="جستجو: تاب، استخر، وایفای، parking، wifi …" autocomplete="off">
        </div>
        <p class="text-muted small mb-3" id="home-option-icon-hint">
            {{ number_format($totalIcons) }} آیکون Bootstrap Icons — پیش‌فرض: پرکاربردترین‌ها. برای بقیه جستجو کنید.
        </p>

        <div class="home-option-icon-grid border rounded p-2 bg-white" id="home-option-icon-grid" role="listbox" aria-label="انتخاب آیکون"></div>

        @error('icon_class')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>

    <div data-icon-panel="image" class="{{ $isFont ? 'd-none' : '' }}">
        <div class="form-group row">
            <div class="col-md-12 text-center">
                <img
                    id="home-option-image-preview"
                    width="200"
                    height="200"
                    alt="@lang('title.icon')"
                    src="{{ $imagePreview }}"
                    class="{{ $imagePreview ? '' : 'd-none' }}"
                    style="object-fit: contain;"
                />
                <div id="home-option-image-placeholder" class="text-muted py-4 {{ $imagePreview ? 'd-none' : '' }}">
                    تصویری انتخاب نشده
                </div>
            </div>
            <div class="col-md-12 mt-3">
                <input
                    type="file"
                    class="form-control"
                    name="icon"
                    id="home-option-icon-file"
                    accept="image/*"
                >
            </div>
        </div>

        @error('icon')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>

    @error('icon_type')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror
</div>
