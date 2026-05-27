@extends('layouts.main.main_mobile', ['title' => 'ویرایش اقامتگاه'])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    <div class="container px-3 py-3">
        <div class="card-mobile mb-3">
            <h1 class="text-mobile-primary mb-2">ویرایش اقامتگاه</h1>
            <p class="text-mobile-secondary mb-0">{{ $home->name }}</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="font-size: 13px;">
                <strong>لطفا موارد زیر را بررسی کنید:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @include('dashboard.partials.home-edit-mobile-tabs', ['home' => $home, 'mode' => 'edit'])
    </div>

    <div class="container px-3 pb-4">
        <form action="{{ route('dashboard.homes.update', $home) }}" method="POST" enctype="multipart/form-data" id="mobileEditHomeForm">
            @csrf
            @method('PUT')

            @php
                $initialTab = request('open_tab') ?: session('open_tab') ?: 'tab-basic';
            @endphp
            <div class="tab-pane-mobile{{ $initialTab === 'tab-basic' ? ' active' : '' }}" id="tab-basic">
                <div class="card-mobile mb-3">
                <h5 class="text-mobile-primary mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    اطلاعات اصلی
                </h5>
                <div class="mb-3">
                    <label for="name" class="form-label-mobile">نام اقامتگاه <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control form-control-mobile" 
                           value="{{ old('name', $home->name) }}" required>
                    @error('name')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label-mobile">توضیحات <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" class="form-control form-control-mobile" 
                              rows="4" required>{{ old('description', $home->description) }}</textarea>
                    @error('description')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label-mobile">نوع اقامتگاه <span class="text-danger">*</span></label>
                    <select name="type" id="type" class="form-select form-control-mobile" required>
                        <option value="">انتخاب کنید</option>
                        <option value="vilaiy" {{ old('type', $home->type) == 'vilaiy' ? 'selected' : '' }}>ویلایی</option>
                        <option value="aparteman" {{ old('type', $home->type) == 'aparteman' ? 'selected' : '' }}>آپارتمان</option>
                        <option value="swiit" {{ old('type', $home->type) == 'swiit' ? 'selected' : '' }}>سوئیت</option>
                    </select>
                    @error('type')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="yard" class="form-label-mobile">متراژ کل (متر)</label>
                        <input type="number" name="yard" id="yard" class="form-control form-control-mobile" min="0"
                               value="{{ old('yard', $home->yard_meter) }}" inputmode="numeric">
                    </div>
                    <div class="col-6">
                        <label for="infrastructure" class="form-label-mobile">متراژ زیربنا (متر)</label>
                        <input type="number" name="infrastructure" id="infrastructure" class="form-control form-control-mobile" min="0"
                               value="{{ old('infrastructure', $home->infrastructure_meter) }}" inputmode="numeric">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="main_guest" class="form-label-mobile">تعداد مهمان اصلی <span class="text-danger">*</span></label>
                    <input type="number" name="main_guest" id="main_guest" class="form-control form-control-mobile" 
                           value="{{ old('main_guest', $home->main_guest) }}" min="1" max="50" required>
                    @error('main_guest')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="extra_guest" class="form-label-mobile">تعداد نفرات اضافه</label>
                    <input type="number" name="extra_guest" id="extra_guest" class="form-control form-control-mobile"
                           value="{{ old('extra_guest', $home->extra_guest ?? 0) }}" min="0" max="50" inputmode="numeric">
                    @error('extra_guest')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="atmosphere" class="form-label-mobile">فضا</label>
                        <select name="atmosphere" id="atmosphere" class="form-select form-control-mobile">
                            <option value="">انتخاب کنید</option>
                            @foreach(\App\Models\Home::ATMOSPHERES as $atmosphereItem)
                                <option value="{{ $atmosphereItem['value'] }}" {{ old('atmosphere', $home->atmosphere) === $atmosphereItem['value'] ? 'selected' : '' }}>
                                    {{ $atmosphereItem['fa_text'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('atmosphere')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label for="area" class="form-label-mobile">منطقه</label>
                        <select name="area" id="area" class="form-select form-control-mobile">
                            <option value="">انتخاب کنید</option>
                            @foreach(\App\Models\Home::AREAS as $areaItem)
                                <option value="{{ $areaItem['value'] }}" {{ old('area', $home->area) === $areaItem['value'] ? 'selected' : '' }}>
                                    {{ $areaItem['fa_text'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('area')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            </div>

            <div class="tab-pane-mobile" id="tab-rooms">
                <x-dashboard.home.mobile-form-rooms :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-location">
                <div class="card-mobile mb-3">
                <h5 class="text-mobile-primary mb-3">
                    <i class="bi bi-geo-alt me-2"></i>
                    موقعیت مکانی
                </h5>
                <div class="mb-3">
                    <label for="province_id" class="form-label-mobile">استان <span class="text-danger">*</span></label>
                    <select name="province_id" id="province_id" class="form-select form-control-mobile" required>
                        <option value="">انتخاب کنید</option>
                        @foreach(\App\Models\Province::all() as $province)
                            <option value="{{ $province->id }}" {{ old('province_id', $home->province_id) == $province->id ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('province_id')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="city_id" class="form-label-mobile">شهر <span class="text-danger">*</span></label>
                    <select name="city_id" id="city_id" class="form-select form-control-mobile" required>
                        <option value="">ابتدا استان را انتخاب کنید</option>
                        @if($home->city)
                            <option value="{{ $home->city->id }}" selected>{{ $home->city->name }}</option>
                        @endif
                    </select>
                    @error('city_id')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label-mobile">آدرس <span class="text-danger">*</span></label>
                    <textarea name="address" id="address" class="form-control form-control-mobile" 
                              rows="3" required>{{ old('address', $home->address) }}</textarea>
                    @error('address')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-mobile-secondary w-100" data-bs-toggle="modal" data-bs-target="#mapModal">
                        <i class="bi bi-map me-2"></i>
                        انتخاب موقعیت روی نقشه
                    </button>
                    <small class="text-mobile-muted d-block mt-2" style="font-size: 12px;">برای انتخاب دقیق موقعیت روی نقشه کلیک کنید</small>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="latitude_display" class="form-label-mobile">عرض جغرافیایی</label>
                        <input type="text" id="latitude_display" class="form-control form-control-mobile"
                               value="{{ old('latitude', $home->latitude) }}" readonly placeholder="—">
                    </div>
                    <div class="col-6">
                        <label for="longitude_display" class="form-label-mobile">طول جغرافیایی</label>
                        <input type="text" id="longitude_display" class="form-control form-control-mobile"
                               value="{{ old('longitude', $home->longitude) }}" readonly placeholder="—">
                    </div>
                </div>

                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $home->latitude) }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $home->longitude) }}">

                <div id="locationPreviewWrap" class="mb-3" @if(!old('latitude', $home->latitude) || !old('longitude', $home->longitude)) style="display: none;" @endif>
                    <label class="form-label-mobile d-block mb-2">پیش‌نمایش موقعیت روی نقشه</label>
                    <div id="locationPreviewMap" class="location-preview-map"></div>
                    <small id="locationText" class="text-mobile-muted d-block mt-2" style="font-size: 12px;">موقعیت انتخاب نشده</small>
                </div>
            </div>
            </div>

            <div class="tab-pane-mobile" id="tab-images">
                <div class="card-mobile mb-3">
                    <h5 class="text-mobile-primary mb-3">
                        <i class="bi bi-images me-2"></i>
                        تصاویر اقامتگاه
                    </h5>

                    <div class="mb-3">
                        <label for="cover" class="form-label-mobile">عکس اصلی</label>
                        <div class="upload-box upload-box-cover" id="coverUploadBox">
                            <input type="file" name="cover" id="cover" class="upload-input" accept="image/*">
                            <div class="upload-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <strong>انتخاب عکس اصلی</strong>
                                <small>برای کیفیت بهتر، تصویر قبل از ارسال فشرده و WebP می‌شود.</small>
                            </div>
                        </div>
                        @error('cover')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-1" style="font-size:12px;">اگر انتخاب نکنید، عکس اصلی فعلی حفظ می‌شود.</small>
                    </div>

                    <div class="cover-preview-box mb-3">
                        <span class="preview-label">پیش‌نمایش عکس اصلی</span>
                        <img src="{{ $home->cover_path }}" id="coverPreview" alt="cover preview">
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label-mobile">سایر تصاویر</label>
                        <div class="upload-box" id="galleryUploadBox">
                            <input type="file" name="images[]" id="images" class="upload-input" accept="image/*" multiple>
                            <div class="upload-content">
                                <i class="bi bi-images"></i>
                                <strong>افزودن تصاویر گالری</strong>
                                <small>چند تصویر را همزمان انتخاب کنید (انتخاب مجدد، به لیست قبلی اضافه می‌شود).</small>
                            </div>
                        </div>
                        @error('images')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <span class="preview-label">تصاویر فعلی</span>
                    </div>
                    <div class="row g-2 mb-3" id="existingImagesGrid">
                        @forelse($home->images as $image)
                            <div class="col-6 existing-image-item" data-image-id="{{ $image->id }}">
                                <div class="image-preview-card">
                                    <img src="{{ $image->image_path }}" alt="image">
                                    <label class="delete-check">
                                        <input type="checkbox" name="delete_existing_images[]" value="{{ $image->id }}">
                                        حذف
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <small class="text-muted">تصویری برای گالری ثبت نشده است.</small>
                            </div>
                        @endforelse
                    </div>

                    <div class="mb-2">
                        <span class="preview-label">پیش‌نمایش تصاویر جدید</span>
                    </div>
                    <div class="row g-2" id="newImagesPreview"></div>
                </div>
            </div>

            <div class="tab-pane-mobile" id="tab-pricing">
                @php
                    $mobilePriceValue = function ($key, $fallback = null) {
                        $v = old($key, $fallback);
                        if ($v === null || $v === '') {
                            return '';
                        }
                        return (string) (int) round((float) $v);
                    };
                @endphp
                <div class="card-mobile mb-3">
                <h5 class="text-mobile-primary mb-3">
                    <i class="bi bi-currency-dollar me-2"></i>
                    قیمت‌گذاری
                </h5>
                <div class="mb-3">
                    <label for="week_price" class="form-label-mobile">قیمت اول هفته (شنبه تا سه‌شنبه) <span class="text-danger">*</span></label>
                    <input type="text" name="week_price" id="week_price" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۲۵۰۰۰۰۰"
                           value="{{ $mobilePriceValue('week_price', $home->week_price) }}" required>
                    <small id="week_price_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    @error('week_price')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="wed_price" class="form-label-mobile">قیمت چهارشنبه (تومان)</label>
                    <input type="text" name="wed_price" id="wed_price" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۳۰۰۰۰۰۰"
                           value="{{ $mobilePriceValue('wed_price', $home->wed_price) }}">
                    <small id="wed_price_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    @error('wed_price')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="thu_price" class="form-label-mobile">قیمت پنجشنبه (تومان)</label>
                    <input type="text" name="thu_price" id="thu_price" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۳۵۰۰۰۰۰"
                           value="{{ $mobilePriceValue('thu_price', $home->thu_price) }}">
                    <small id="thu_price_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    @error('thu_price')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="fri_price" class="form-label-mobile">قیمت جمعه (تومان)</label>
                    <input type="text" name="fri_price" id="fri_price" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۴۰۰۰۰۰۰"
                           value="{{ old('fri_price', $home->fri_price) }}">
                    <small id="fri_price_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    @error('fri_price')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="price_per_surplus" class="form-label-mobile">مبلغ به ازای هر نفر اضافه (تومان)</label>
                    <input type="text" name="price_per_surplus" id="price_per_surplus" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۵۰۰۰۰۰"
                           value="{{ $mobilePriceValue('price_per_surplus', $home->price_per_surplus) }}">
                    <small id="price_per_surplus_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    @error('price_per_surplus')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="cleaning_fee" class="form-label-mobile">هزینه نظافت (تومان)</label>
                    <input type="text" name="cleaning_fee" id="cleaning_fee" class="form-control form-control-mobile price-field"
                           inputmode="numeric" autocomplete="off" placeholder="مثال: ۳۰۰۰۰۰"
                           value="{{ $mobilePriceValue('cleaning_fee', $home->cleaning_fee) }}">
                    <small id="cleaning_fee_words" class="price-words text-mobile-muted d-block mt-1" style="display: none;"></small>
                    <small class="text-mobile-muted d-block mt-1" style="font-size: 12px; line-height: 1.6;">
                        در صورت عدم نظافت توسط مهمان و کثیف بودن اقامتگاه، این مبلغ از مهمان دریافت می‌شود.
                    </small>
                    @error('cleaning_fee')
                        <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            </div>


            <div class="tab-pane-mobile{{ $initialTab === 'tab-discount' ? ' active' : '' }}" id="tab-discount">
                <x-dashboard.home.mobile-form-discount :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-rules">
                <x-dashboard.home.mobile-form-rules :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-safety">
                <x-dashboard.home.mobile-form-safety :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-options">
                <x-dashboard.home.mobile-form-options :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-health">
                <x-dashboard.home.mobile-form-health :home="$home" />
            </div>

            <div class="tab-pane-mobile" id="tab-document">
                <x-dashboard.home.mobile-form-document :home="$home" :require-upload="false" />
            </div>

            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('dashboard.homes.index') }}" class="btn btn-mobile-secondary flex-fill">
                    <i class="bi bi-arrow-right me-2"></i>
                    انصراف
                </a>
                <button type="submit" class="btn btn-mobile-primary flex-fill">
                    <i class="bi bi-check-circle me-2"></i>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">انتخاب موقعیت روی نقشه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="map" style="height: 100%; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-mobile-secondary" data-bs-dismiss="modal">انصراف</button>
                    <button type="button" class="btn btn-mobile-primary" onclick="saveLocation()">تأیید موقعیت</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>
<link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}" />

<style>
.mobile-edit-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.mobile-edit-tabs::-webkit-scrollbar { display: none; }
.tab-pill {
    border: 1px solid #e7e7e7;
    background: #fff;
    color: #444;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 13px;
    white-space: nowrap;
}
.tab-pill.active {
    background: #D39D1A;
    border-color: #D39D1A;
    color: #fff;
}
.tab-pane-mobile { display: none; }
.tab-pane-mobile.active { display: block; }
.preview-label {
    font-size: 12px;
    color: #6c757d;
}
.cover-preview-box img {
    width: 100%;
    height: 180px;
    border-radius: 10px;
    object-fit: cover;
    border: 1px solid #ececec;
}
.image-preview-card {
    border: 1px solid #ececec;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
}
.image-preview-card img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}
.delete-check {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    padding: 6px 8px;
}
.upload-box {
    position: relative;
    border: 1px dashed #d7d7d7;
    border-radius: 14px;
    background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
    padding: 14px;
    text-align: center;
    transition: all 0.2s ease;
}
.upload-box.active,
.upload-box:hover {
    border-color: #D39D1A;
    box-shadow: 0 3px 10px rgba(211, 157, 26, 0.15);
}
.upload-input {
    position: absolute;
    inset: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    z-index: 2;
}
.upload-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
    color: #505050;
}
.upload-content i {
    font-size: 28px;
    color: #D39D1A;
}
.upload-content strong {
    font-size: 14px;
}
.upload-content small {
    font-size: 12px;
}
.new-image-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 8px;
    font-size: 11px;
    color: #666;
}
.remove-new-image {
    border: 0;
    background: #fbeaea;
    color: #c62828;
    border-radius: 999px;
    width: 24px;
    height: 24px;
    line-height: 24px;
    text-align: center;
    font-size: 14px;
}
.compress-hint {
    font-size: 11px;
    color: #6f6f6f;
}

.mobile-option-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}
.mobile-option-chip {
    display: block;
    margin: 0;
    cursor: pointer;
}
.mobile-option-chip input {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}
.mobile-option-chip-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 12px 8px;
    border: 1px solid #ececec;
    border-radius: 12px;
    background: #fafafa;
    min-height: 88px;
}
.mobile-option-chip input:checked + .mobile-option-chip-body {
    border-color: #D39D1A;
    background: #fff9eb;
}
.mobile-option-chip-title {
    font-size: 12px;
    text-align: center;
    color: #333;
}
.mobile-check-list { display: flex; flex-direction: column; gap: 10px; }
.mobile-check-row { display: flex; flex-direction: column; gap: 8px; padding-bottom: 10px; border-bottom: 1px solid #f0f0f0; }
.mobile-check-label { display: flex; align-items: center; gap: 10px; font-size: 14px; margin: 0; cursor: pointer; }

.location-preview-map {
    height: 220px;
    width: 100%;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    overflow: hidden;
    z-index: 1;
}

.price-words {
    line-height: 1.6;
    font-size: 12px;
}

</style>

<script>
let map;
let marker;
let previewMap = null;
let previewMarker = null;
let selectedGalleryFiles = [];
let selectedCoverFile = null;
const canUseDataTransfer = typeof DataTransfer !== 'undefined';
const MAX_GALLERY_IMAGES = 30;
const existingImagesCount = {{ $home->images->count() }};

document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
    const latVal = document.getElementById('latitude').value;
    const lngVal = document.getElementById('longitude').value;
    const hasCoords = latVal && lngVal && latVal !== '' && lngVal !== '';

    if (!map) {
        map = L.map('map').setView([32.4279, 53.6880], 6);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
        }).addTo(map);

        map.on('click', function (e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
        });
    }

    if (hasCoords) {
        const lat = parseFloat(latVal);
        const lng = parseFloat(lngVal);
        if (!isNaN(lat) && !isNaN(lng)) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker([lat, lng]).addTo(map);
            map.setView([lat, lng], 15);
        }
    } else if (marker) {
        map.removeLayer(marker);
        marker = null;
        map.setView([32.4279, 53.6880], 6);
    }

    setTimeout(function () {
        if (map) {
            map.invalidateSize();
        }
    }, 200);
});

function saveLocation() {
    if (marker) {
        const lat = marker.getLatLng().lat;
        const lng = marker.getLatLng().lng;

        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        updateLocationDisplay(lat, lng);

        const modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
        modal.hide();
    } else {
        alert('لطفاً روی نقشه کلیک کنید تا موقعیت را انتخاب کنید');
    }
}

function renderLocationPreviewMap(lat, lng) {
    const wrap = document.getElementById('locationPreviewWrap');
    const container = document.getElementById('locationPreviewMap');
    if (!wrap || !container || typeof L === 'undefined') {
        return;
    }

    wrap.style.display = 'block';

    if (!previewMap) {
        previewMap = L.map(container, {
            zoomControl: true,
            attributionControl: true,
        }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
        }).addTo(previewMap);
        previewMarker = L.marker([lat, lng]).addTo(previewMap);
    } else {
        previewMap.setView([lat, lng], 15);
        if (previewMarker) {
            previewMarker.setLatLng([lat, lng]);
        } else {
            previewMarker = L.marker([lat, lng]).addTo(previewMap);
        }
    }

    setTimeout(function () {
        if (previewMap) {
            previewMap.invalidateSize();
        }
    }, 250);
}

function updateLocationDisplay(lat, lng, renderMap) {
    const wrap = document.getElementById('locationPreviewWrap');
    const locationText = document.getElementById('locationText');
    const latDisplay = document.getElementById('latitude_display');
    const lngDisplay = document.getElementById('longitude_display');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const latFixed = Number(lat).toFixed(6);
    const lngFixed = Number(lng).toFixed(6);

    if (latInput) latInput.value = latFixed;
    if (lngInput) lngInput.value = lngFixed;
    if (latDisplay) latDisplay.value = latFixed;
    if (lngDisplay) lngDisplay.value = lngFixed;

    if (locationText) {
        locationText.textContent = 'عرض: ' + latFixed + ' — طول: ' + lngFixed;
    }
    if (wrap) {
        wrap.style.display = 'block';
    }

    const locPane = document.getElementById('tab-location');
    const shouldRenderMap = renderMap !== false
        && locPane
        && locPane.classList.contains('active');

    if (shouldRenderMap) {
        renderLocationPreviewMap(parseFloat(latFixed), parseFloat(lngFixed));
    }

    getAddressFromCoordinates(parseFloat(latFixed), parseFloat(lngFixed));
}

function getAddressFromCoordinates(lat, lng) {
    fetch('https://nominatim.openstreetmap.org/reverse?format=json&lat=' + lat + '&lon=' + lng + '&accept-language=fa')
        .then(function (response) { return response.json(); })
        .then(function (data) {
            if (!data.display_name) return;
            const locationText = document.getElementById('locationText');
            if (!locationText) return;
            locationText.textContent = 'عرض: ' + lat.toFixed(6) + ' — طول: ' + lng.toFixed(6) + ' · ' + data.display_name;
        })
        .catch(function () {
            //
        });
}

function refreshLocationPreviewIfNeeded() {
    const lat = parseFloat(document.getElementById('latitude')?.value || '');
    const lng = parseFloat(document.getElementById('longitude')?.value || '');
    if (!isNaN(lat) && !isNaN(lng)) {
        renderLocationPreviewMap(lat, lng);
    }
}

// Load cities when province changes
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const citySelect = document.getElementById('city_id');
    const currentCityId = '{{ $home->city_id }}';
    
    citySelect.innerHTML = '<option value="">در حال بارگذاری...</option>';
    
    if (provinceId) {
        fetch(`/api/cities/${provinceId}`)
            .then(response => response.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">انتخاب کنید</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    if (city.id == currentCityId) {
                        option.selected = true;
                    }
                    citySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                citySelect.innerHTML = '<option value="">خطا در بارگذاری</option>';
            });
    } else {
        citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
    }
});

// Load cities for current province on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initMobileBedrooms === 'function') {
        window.initMobileBedrooms();
    }

    document.querySelectorAll('.safety-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const target = document.getElementById(this.dataset.target);
            if (target) {
                target.classList.toggle('d-none', !this.checked);
            }
        });
    });

    const tabs = document.querySelectorAll('.tab-pill[data-target]');
    const panes = document.querySelectorAll('.tab-pane-mobile');

    function syncTabRequiredAttributes() {
        panes.forEach((pane) => {
            const isActive = pane.classList.contains('active');
            pane.querySelectorAll('[data-tab-required="1"]').forEach((el) => {
                if (isActive) {
                    el.setAttribute('required', '');
                } else {
                    el.removeAttribute('required');
                }
            });
        });
    }

    function initTabRequiredSync() {
        panes.forEach((pane) => {
            pane.querySelectorAll('[required]').forEach((el) => {
                el.dataset.tabRequired = '1';
            });
        });
        syncTabRequiredAttributes();
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', function() {
            const target = this.dataset.target;
            const pane = document.getElementById(target);
            if (!pane) {
                console.warn('Tab pane not found:', target);
                return;
            }
            tabs.forEach((t) => t.classList.remove('active'));
            panes.forEach((p) => p.classList.remove('active'));
            this.classList.add('active');
            pane.classList.add('active');
            syncTabRequiredAttributes();
            if (target === 'tab-rooms' && typeof window.initMobileBedrooms === 'function') {
                window.initMobileBedrooms();
            }
            if (target === 'tab-location') {
                setTimeout(refreshLocationPreviewIfNeeded, 100);
            }
            if (target === 'tab-pricing' && typeof refreshAllPriceWords === 'function') {
                setTimeout(refreshAllPriceWords, 50);
            }
            if (target === 'tab-document' && typeof window.refreshExistingDocumentPreview === 'function') {
                setTimeout(window.refreshExistingDocumentPreview, 100);
            }
            this.scrollIntoView({behavior: 'smooth', inline: 'center', block: 'nearest'});
        });
    });

    initTabRequiredSync();

    (function () {
        const openTab = @json(request('open_tab') ?: session('open_tab'));
        if (!openTab) return;

        const pane = document.getElementById(openTab);
        if (!pane) return;

        tabs.forEach((t) => t.classList.remove('active'));
        panes.forEach((p) => p.classList.remove('active'));
        pane.classList.add('active');

        const tabBtn = document.querySelector('.tab-pill[data-target="' + openTab + '"]');
        if (tabBtn) {
            tabBtn.classList.add('active');
            tabBtn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        }

        syncTabRequiredAttributes();

        if (openTab === 'tab-rooms' && typeof window.initMobileBedrooms === 'function') {
            window.initMobileBedrooms();
        }
        if (openTab === 'tab-location') {
            setTimeout(refreshLocationPreviewIfNeeded, 100);
        }
        if (openTab === 'tab-pricing' && typeof refreshAllPriceWords === 'function') {
            setTimeout(refreshAllPriceWords, 50);
        }
        if (openTab === 'tab-document' && typeof window.refreshExistingDocumentPreview === 'function') {
            setTimeout(window.refreshExistingDocumentPreview, 100);
        }
    })();

    if (document.querySelector('.existing-doc-preview-image') && typeof window.refreshExistingDocumentPreview === 'function') {
        setTimeout(window.refreshExistingDocumentPreview, 300);
    }

    const coverInput = document.getElementById('cover');
    const coverPreview = document.getElementById('coverPreview');
    const coverUploadBox = document.getElementById('coverUploadBox');
    if (coverInput && coverPreview) {
        coverInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            selectedCoverFile = file;
            if (coverUploadBox) {
                coverUploadBox.classList.add('active');
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                coverPreview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    const imagesInput = document.getElementById('images');
    const newImagesPreview = document.getElementById('newImagesPreview');
    const galleryUploadBox = document.getElementById('galleryUploadBox');
    if (imagesInput && newImagesPreview) {
        imagesInput.addEventListener('change', function(e) {
            const freshFiles = Array.from(e.target.files).filter((file) => file.type.startsWith('image/'));
            if (freshFiles.length) {
                const availableSlots = getAvailableImageSlots();
                if (availableSlots <= 0) {
                    alert('شما به سقف 30 تصویر رسیده‌اید. ابتدا تعدادی از تصاویر فعلی را حذف کنید.');
                    if (canUseDataTransfer) {
                        imagesInput.value = '';
                    }
                    return;
                }

                if (freshFiles.length > availableSlots) {
                    alert(`حداکثر ${MAX_GALLERY_IMAGES} تصویر مجاز است. فقط ${availableSlots} تصویر دیگر می‌توانید اضافه کنید.`);
                    selectedGalleryFiles = selectedGalleryFiles.concat(freshFiles.slice(0, availableSlots));
                } else {
                    selectedGalleryFiles = selectedGalleryFiles.concat(freshFiles);
                }
                renderNewImagesPreview();
                syncGalleryInputFiles();
                if (galleryUploadBox) {
                    galleryUploadBox.classList.add('active');
                }
            }
            if (canUseDataTransfer) {
                imagesInput.value = '';
            }
        });
    }

    if (galleryUploadBox && imagesInput) {
        ['dragover', 'dragenter'].forEach((eventName) => {
            galleryUploadBox.addEventListener(eventName, function(e) {
                e.preventDefault();
                galleryUploadBox.classList.add('active');
            });
        });
        ['dragleave', 'drop'].forEach((eventName) => {
            galleryUploadBox.addEventListener(eventName, function(e) {
                e.preventDefault();
                galleryUploadBox.classList.remove('active');
            });
        });
        galleryUploadBox.addEventListener('drop', function(e) {
            const droppedFiles = Array.from(e.dataTransfer.files).filter((file) => file.type.startsWith('image/'));
            if (droppedFiles.length) {
                const availableSlots = getAvailableImageSlots();
                if (availableSlots <= 0) {
                    alert('شما به سقف 30 تصویر رسیده‌اید. ابتدا تعدادی از تصاویر فعلی را حذف کنید.');
                    return;
                }

                if (droppedFiles.length > availableSlots) {
                    alert(`حداکثر ${MAX_GALLERY_IMAGES} تصویر مجاز است. فقط ${availableSlots} تصویر دیگر می‌توانید اضافه کنید.`);
                    selectedGalleryFiles = selectedGalleryFiles.concat(droppedFiles.slice(0, availableSlots));
                } else {
                    selectedGalleryFiles = selectedGalleryFiles.concat(droppedFiles);
                }
                renderNewImagesPreview();
                syncGalleryInputFiles();
            }
        });
    }

    const provinceSelect = document.getElementById('province_id');
    const currentProvinceId = provinceSelect.value;
    
    if (currentProvinceId) {
        // Trigger the change event to load cities
        provinceSelect.dispatchEvent(new Event('change'));
    }

    const initialLat = parseFloat(document.getElementById('latitude')?.value || '');
    const initialLng = parseFloat(document.getElementById('longitude')?.value || '');
    if (!isNaN(initialLat) && !isNaN(initialLng)) {
        updateLocationDisplay(initialLat, initialLng, false);
    }

    initMobilePriceFields();

    if (typeof window.initMobileDocumentPreview === 'function') {
        window.initMobileDocumentPreview();
    }

    const form = document.getElementById('mobileEditHomeForm');
    if (form) {
        form.addEventListener('submit', async function(e) {
            const finalCount = getRemainingExistingImagesCount() + selectedGalleryFiles.length;
            if (finalCount > MAX_GALLERY_IMAGES) {
                e.preventDefault();
                alert('تعداد کل تصاویر نباید بیشتر از 30 عدد باشد.');
                return;
            }

            e.preventDefault();
            syncTabRequiredAttributes();
            normalizeAllPriceFieldsForSubmit();
            if (typeof window.prepareCompressedDocumentInput === 'function') {
                await window.prepareCompressedDocumentInput('document');
            }
            await prepareCompressedWebpFiles();
            form.submit();
        });
    }
});

function syncGalleryInputFiles() {
    const input = document.getElementById('images');
    if (!input || !canUseDataTransfer) return;
    const dt = new DataTransfer();
    selectedGalleryFiles.forEach((file) => dt.items.add(file));
    input.files = dt.files;
}

function getMarkedForDeleteCount() {
    return document.querySelectorAll('input[name="delete_existing_images[]"]:checked').length;
}

function getRemainingExistingImagesCount() {
    return Math.max(0, existingImagesCount - getMarkedForDeleteCount());
}

function getAvailableImageSlots() {
    return Math.max(0, MAX_GALLERY_IMAGES - (getRemainingExistingImagesCount() + selectedGalleryFiles.length));
}

function renderNewImagesPreview() {
    const container = document.getElementById('newImagesPreview');
    if (!container) return;
    container.innerHTML = '';

    selectedGalleryFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(event) {
            const col = document.createElement('div');
            col.className = 'col-6';
            col.innerHTML = `
                <div class="image-preview-card">
                    <img src="${event.target.result}" alt="new-image-${index}">
                    <div class="new-image-actions">
                        <span>${(file.size / 1024 / 1024).toFixed(2)}MB</span>
                        <button type="button" class="remove-new-image" data-index="${index}">×</button>
                    </div>
                </div>
            `;
            container.appendChild(col);
        };
        reader.readAsDataURL(file);
    });

    setTimeout(() => {
        container.querySelectorAll('.remove-new-image').forEach((button) => {
            button.addEventListener('click', function() {
                const index = Number(this.dataset.index);
                selectedGalleryFiles.splice(index, 1);
                syncGalleryInputFiles();
                renderNewImagesPreview();
            });
        });
    }, 0);
}

async function prepareCompressedWebpFiles() {
    const coverInput = document.getElementById('cover');
    const imagesInput = document.getElementById('images');

    if (selectedCoverFile) {
        const convertedCover = await convertImageToWebp(selectedCoverFile, 1200, 0.82);
        if (convertedCover && coverInput) {
            const dt = new DataTransfer();
            dt.items.add(convertedCover);
            coverInput.files = dt.files;
        }
    }

    if (selectedGalleryFiles.length && imagesInput) {
        const convertedGallery = [];
        for (const file of selectedGalleryFiles) {
            const converted = await convertImageToWebp(file, 1280, 0.78);
            convertedGallery.push(converted || file);
        }
        selectedGalleryFiles = convertedGallery;
        syncGalleryInputFiles();
    }
}

function convertImageToWebp(file, maxSize = 1280, quality = 0.8) {
    return new Promise((resolve) => {
        if (!file.type.startsWith('image/')) {
            resolve(file);
            return;
        }

        const image = new Image();
        const objectUrl = URL.createObjectURL(file);

        image.onload = () => {
            const canvas = document.createElement('canvas');
            let { width, height } = image;

            if (width > maxSize || height > maxSize) {
                if (width > height) {
                    height = Math.round((height * maxSize) / width);
                    width = maxSize;
                } else {
                    width = Math.round((width * maxSize) / height);
                    height = maxSize;
                }
            }

            canvas.width = width;
            canvas.height = height;
            const context = canvas.getContext('2d');
            context.drawImage(image, 0, 0, width, height);

            canvas.toBlob((blob) => {
                URL.revokeObjectURL(objectUrl);
                if (!blob) {
                    resolve(file);
                    return;
                }
                const baseName = file.name.replace(/\.[^/.]+$/, '');
                resolve(new File([blob], `${baseName}.webp`, { type: 'image/webp' }));
            }, 'image/webp', quality);
        };

        image.onerror = () => {
            URL.revokeObjectURL(objectUrl);
            resolve(file);
        };

        image.src = objectUrl;
    });
}

const PRICE_WORD_ONES = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
const PRICE_WORD_TENS = ['', 'ده', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
const PRICE_WORD_TEENS = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
const PRICE_WORD_HUNDREDS = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
const PRICE_WORD_SCALES = ['', 'هزار', 'میلیون', 'میلیارد', 'تریلیون'];

function toPersianDigits(str) {
    return String(str).replace(/\d/g, function (d) {
        return '۰۱۲۳۴۵۶۷۸۹'[parseInt(d, 10)];
    });
}

function priceChunkToWords(n) {
    const parts = [];
    const hundred = Math.floor(n / 100);
    const ten = Math.floor((n % 100) / 10);
    const one = n % 10;

    if (hundred) {
        parts.push(PRICE_WORD_HUNDREDS[hundred]);
    }
    if (ten === 1) {
        parts.push(PRICE_WORD_TEENS[one]);
    } else {
        if (ten) {
            parts.push(PRICE_WORD_TENS[ten]);
        }
        if (one) {
            parts.push(PRICE_WORD_ONES[one]);
        }
    }

    return parts.join(' و ');
}

function numberToPersianWords(num) {
    const value = Math.floor(Number(num) || 0);
    if (value <= 0) {
        return '';
    }

    const chunks = [];
    let remaining = value;
    let scale = 0;

    while (remaining > 0 && scale < PRICE_WORD_SCALES.length) {
        const part = remaining % 1000;
        if (part) {
            let words = priceChunkToWords(part);
            if (PRICE_WORD_SCALES[scale]) {
                words += ' ' + PRICE_WORD_SCALES[scale];
            }
            chunks.unshift(words);
        }
        remaining = Math.floor(remaining / 1000);
        scale += 1;
    }

    return chunks.join(' و ') + ' تومان';
}

function parsePriceFieldValue(input) {
    if (!input) {
        return 0;
    }
    let raw = String(input.value || '')
        .replace(/[۰-۹]/g, function (d) {
            return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        })
        .replace(/[٬,\s]/g, '');
    const num = parseFloat(raw.replace(/[^\d.]/g, ''));
    if (isNaN(num) || num < 0) {
        return 0;
    }
    return Math.trunc(num);
}

function formatPriceFieldDisplay(input, withGrouping) {
    if (!input) {
        return;
    }
    const value = parsePriceFieldValue(input);
    if (value <= 0) {
        input.value = '';
        return;
    }
    let str = String(value);
    if (withGrouping) {
        str = str.replace(/\B(?=(\d{3})+(?!\d))/g, '٬');
    }
    input.value = toPersianDigits(str);
}

function updatePriceWords(inputId) {
    const input = document.getElementById(inputId);
    const wordsEl = document.getElementById(inputId + '_words');
    if (!input || !wordsEl) {
        return;
    }

    const value = parsePriceFieldValue(input);
    if (value > 0) {
        wordsEl.textContent = numberToPersianWords(value);
        wordsEl.style.display = 'block';
    } else {
        wordsEl.textContent = '';
        wordsEl.style.display = 'none';
    }
}

function refreshAllPriceWords() {
    document.querySelectorAll('.price-field').forEach(function (input) {
        formatPriceFieldDisplay(input, true);
        updatePriceWords(input.id);
    });
}

function normalizeAllPriceFieldsForSubmit() {
    document.querySelectorAll('.price-field').forEach(function (input) {
        const value = parsePriceFieldValue(input);
        input.value = value > 0 ? String(value) : '';
    });
}

function initMobilePriceFields() {
    document.querySelectorAll('.price-field').forEach(function (input) {
        if (input.dataset.priceBound === '1') {
            return;
        }
        input.dataset.priceBound = '1';

        input.addEventListener('focus', function () {
            const value = parsePriceFieldValue(input);
            if (value > 0) {
                formatPriceFieldDisplay(input, false);
            }
        });

        input.addEventListener('input', function () {
            formatPriceFieldDisplay(input, false);
            updatePriceWords(input.id);
        });

        input.addEventListener('blur', function () {
            formatPriceFieldDisplay(input, true);
            updatePriceWords(input.id);
        });

        input.addEventListener('change', function () {
            formatPriceFieldDisplay(input, true);
            updatePriceWords(input.id);
        });
    });

    refreshAllPriceWords();
}
</script>
@endsection
