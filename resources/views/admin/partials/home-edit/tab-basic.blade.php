@include('admin.partials.home-edit.help', ['text' => '<strong>اطلاعات اصلی:</strong> نام، توضیحات، نوع و ظرفیت اقامتگاه. این متن‌ها در صفحهٔ نمایش اقامتگاه برای مهمان دیده می‌شوند.'])

<div class="mb-3">
    <label for="name">@lang('title.name')</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $home->name) }}" required>
</div>

<div class="mb-3">
    <label for="description">@lang('title.description')</label>
    <textarea class="form-control" id="description" name="description" rows="5" required>{!! old('description', $home->description) !!}</textarea>
    <p class="text-muted small mb-0 mt-1">معرفی کامل اقامتگاه؛ حداکثر ۱۵۰۰ کاراکتر.</p>
</div>

<div class="row">
    <div class="col-12 col-md-4 mb-3">
        <label for="atmosphere">@lang('title.atmosphere')</label>
        <select name="atmosphere" id="atmosphere" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach(\App\Models\Home::ATMOSPHERES as $atmosphere)
                <option value="{{ $atmosphere['value'] }}"
                        {{ $atmosphere['value'] == old('atmosphere', $home->atmosphere) ? 'selected' : '' }}>{{ $atmosphere['fa_text'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="type">@lang('title.type')</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach(\App\Models\Home::TYPES as $type)
                <option value="{{ $type['value'] }}"
                        {{ $type['value'] == old('type', $home->type) ? 'selected' : '' }}>{{ $type['fa_text'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-4 mb-3">
        <label for="area">@lang('title.area')</label>
        <select name="area" id="area" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach(\App\Models\Home::AREAS as $area)
                <option value="{{ $area['value'] }}"
                        {{ $area['value'] == old('area', $home->area) ? 'selected' : '' }}>{{ $area['fa_text'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-3 mb-3">
        <label for="yard">@lang('title.yard_meter')</label>
        <input type="number" class="form-control" id="yard" name="yard" required
               value="{{ old('yard', $home->yard_meter) }}">
    </div>
    <div class="col-12 col-md-3 mb-3">
        <label for="infrastructure">@lang('title.infrastructure_meter')</label>
        <input type="number" class="form-control" id="infrastructure" name="infrastructure" required
               value="{{ old('infrastructure', $home->infrastructure_meter) }}">
    </div>
    <div class="col-12 col-md-3 mb-3">
        <label for="main_guest">@lang('title.main_guest_count')</label>
        <input type="number" class="form-control" id="main_guest" name="main_guest" required
               value="{{ old('main_guest', $home->main_guest) }}">
    </div>
    <div class="col-12 col-md-3 mb-3">
        <label for="extra_guest">@lang('title.extra_guest_count')</label>
        <input type="number" class="form-control" id="extra_guest" name="extra_guest" required
               value="{{ old('extra_guest', $home->extra_guest) }}">
        <p class="text-muted small mb-0 mt-1">نفرات اضافه با هزینهٔ جدا (تب قیمت).</p>
    </div>
</div>
