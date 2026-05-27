@include('admin.partials.home-edit.help', ['text' => '<strong>موقعیت:</strong> استان و شهر را انتخاب کنید، آدرس را بنویسید و با نقشه مختصات دقیق را ثبت کنید (همان قابلیت نسخهٔ موبایل).'])

<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <label for="admin-home-province">@lang('title.province')</label>
        <select name="province" id="admin-home-province" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}"
                        {{ old('province', $home->province_id) == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-6 mb-3">
        <label for="admin-home-city">@lang('title.city')</label>
        <select name="city" id="admin-home-city" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}" data-province="{{ $city->province_id }}"
                        {{ old('city', $home->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="address">@lang('title.address')</label>
    <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $home->address) }}</textarea>
</div>

<button type="button" class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#adminMapModal">
    <span class="fas fa-map-marked-alt ms-1"></span> انتخاب موقعیت روی نقشه
</button>

<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <label for="admin_latitude_display" class="form-label small">عرض جغرافیایی</label>
        <input type="text" id="admin_latitude_display" class="form-control form-control-sm" readonly
               value="{{ old('latitude', $home->latitude) }}" placeholder="—">
    </div>
    <div class="col-6 col-md-3">
        <label for="admin_longitude_display" class="form-label small">طول جغرافیایی</label>
        <input type="text" id="admin_longitude_display" class="form-control form-control-sm" readonly
               value="{{ old('longitude', $home->longitude) }}" placeholder="—">
    </div>
    <div class="col-12 col-md-3">
        <label for="latitude" class="form-label small">@lang('title.latitude') (ویرایش دستی)</label>
        <input type="number" step="any" class="form-control form-control-sm" id="latitude" name="latitude" required
               value="{{ old('latitude', $home->latitude) }}">
    </div>
    <div class="col-12 col-md-3">
        <label for="longitude" class="form-label small">@lang('title.longitude') (ویرایش دستی)</label>
        <input type="number" step="any" class="form-control form-control-sm" id="longitude" name="longitude" required
               value="{{ old('longitude', $home->longitude) }}">
    </div>
</div>

<div id="adminLocationPreviewWrap" class="mb-3" @if(!old('latitude', $home->latitude) || !old('longitude', $home->longitude)) style="display: none;" @endif>
    <label class="form-label small d-block mb-2">پیش‌نمایش موقعیت</label>
    <div id="adminLocationPreviewMap" class="admin-location-preview-map rounded border"></div>
    <small id="adminLocationText" class="text-muted d-block mt-2"></small>
</div>
