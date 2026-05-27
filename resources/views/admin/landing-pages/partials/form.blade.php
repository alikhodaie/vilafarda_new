@php
    $lp = $landingPage ?? null;
    $faqRows = old('faq', $lp ? ($lp->faq ?? []) : []);
    if ($faqRows === [] || $faqRows === null) {
        $faqRows = [['question' => '', 'answer' => '']];
    }
@endphp

@include('admin.partials.landing-pages-guide')

<div class="col-12 mb-4">
    <label class="form-label" for="filter_source_url">
        @lang('title.landing_filter_url')
        <small class="text-muted">(@lang('title.optional'))</small>
    </label>
    <input class="form-control" name="filter_source_url" id="filter_source_url" type="text" dir="ltr"
           value="{{ old('filter_source_url', $lp->filter_source_url ?? '') }}"
           placeholder="https://example.com/homes?province=1&city=2&features[]=pool">
    <small class="text-muted d-block mt-1">@lang('text.landing_filter_url_hint')</small>

    @if($lp && $lp->hasAdvancedFilters())
        <div class="alert alert-light border small mt-3 mb-0">
            <strong>@lang('title.landing_active_filters'):</strong>
            @php($summary = $lp->filterSummaryLabels())
            @if($summary !== [])
                {{ implode('، ', $summary) }}
            @else
                @lang('text.landing_filters_saved')
            @endif
            @if($lp->city)
                <span class="text-muted">— {{ $lp->city->name }}</span>
            @endif
        </div>
    @endif
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="title">@lang('title.title') (H1) <span>*</span></label>
    <input class="form-control" name="title" id="title" type="text" value="{{ old('title', $lp->title ?? '') }}" required>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="slug">@lang('title.slug') <span>*</span></label>
    <input class="form-control" name="slug" id="slug" type="text" dir="ltr"
           value="{{ old('slug', $lp->slug ?? '') }}" required
           placeholder="اجاره-ویلا-رامسر">
    <small class="text-muted">@lang('text.landing_slug_hint')</small>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="meta_title">@lang('title.meta_title')</label>
    <input class="form-control js-seo-page-title" name="meta_title" id="meta_title" type="text" maxlength="60"
           value="{{ old('meta_title', $lp->meta_title ?? '') }}">
    <small class="text-muted">@lang('text.landing_meta_title_hint')</small>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="meta_description">@lang('title.meta_description')</label>
    <textarea class="form-control" name="meta_description" id="meta_description" rows="2" maxlength="150">{{ old('meta_description', $lp->meta_description ?? '') }}</textarea>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="province">@lang('title.province')</label>
    <province-input
        route="{{ route('dashboard.provinces') }}"
        name="province"
        placeholder="@lang('text.insert_province')"
        select_label="@lang('title.select')"
        selected_label="@lang('title.selected')"
        deselect_label="@lang('title.remove')"
        no_result_text="@lang('text.empty_result')"
        no_options_text="@lang('text.empty_list')"
        old="{{ old('province', $lp->province_id ?? '') }}"
    ></province-input>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="city">@lang('title.city')</label>
    <city-input
        placeholder="@lang('text.insert_city')"
        name="city"
        select_label="@lang('title.select')"
        selected_label="@lang('title.selected')"
        deselect_label="@lang('title.remove')"
        no_result_text="@lang('text.empty_result')"
        no_options_text="@lang('text.empty_list')"
        old="{{ old('city', $lp->city_id ?? '') }}"
    ></city-input>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="home_type">@lang('title.residence_type')</label>
    <select name="home_type" id="home_type" class="form-control">
        <option value="">@lang('title.all')</option>
        @foreach($homeTypes as $type)
            <option value="{{ $type['value'] }}"
                @if(old('home_type', $lp->home_type ?? '') === $type['value']) selected @endif>
                {{ $type['fa_text'] }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-12 col-md-6 mb-4">
    <label class="form-label" for="sort">@lang('title.sort')</label>
    <input class="form-control" name="sort" id="sort" type="number" min="0"
           value="{{ old('sort', $lp->sort ?? 0) }}">
</div>

<div class="col-12 col-md-6 mb-4">
    <div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" name="city_only" id="city_only" value="1"
               @if(old('city_only', $lp->city_only ?? true)) checked @endif>
        <label class="form-check-label" for="city_only">@lang('title.landing_city_only')</label>
    </div>
</div>

<div class="col-12 col-md-6 mb-4">
    <div class="form-check mt-4">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
               @if(old('is_active', $lp->is_active ?? false)) checked @endif>
        <label class="form-check-label" for="is_active">@lang('title.active')</label>
    </div>
</div>

<div class="col-12 mb-4">
    <label class="form-label" for="intro">@lang('title.landing_intro')</label>
    <tinymce-editor
        upload_file_route="{{ route('admin.tinymce_upload') }}"
        upload_directory="{{ \App\Models\Article::getDescriptionPath() }}"
        name="intro"
        lang="{{ config('app.tiny_locale') }}"
        @if(old('intro', $lp->intro ?? null)) value="{{ old('intro', $lp->intro ?? '') }}" @endif
    ></tinymce-editor>
</div>

<div class="col-12 mb-4">
    <label class="form-label">@lang('title.landing_faq') <small class="text-muted">(@lang('title.optional'))</small></label>
    <div id="landing-faq-rows">
        @foreach($faqRows as $i => $row)
            <div class="border rounded p-3 mb-2 landing-faq-row">
                <div class="mb-2">
                    <input type="text" class="form-control" name="faq[{{ $i }}][question]"
                           placeholder="@lang('title.question')"
                           value="{{ $row['question'] ?? '' }}">
                </div>
                <textarea class="form-control" name="faq[{{ $i }}][answer]" rows="2"
                          placeholder="@lang('title.answer')">{{ $row['answer'] ?? '' }}</textarea>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-falcon-default" id="add-landing-faq">+ @lang('title.add_faq_row')</button>
</div>
