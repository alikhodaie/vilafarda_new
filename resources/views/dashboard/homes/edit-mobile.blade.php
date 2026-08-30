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
                    <select name="city_id" id="city_id" class="form-select form-control-mobile" required
                            data-current-city="{{ old('city_id', $home->city_id) }}">
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
                            <input type="file" name="cover" id="cover" class="upload-input" accept="image/*,.heic,.heif">
                            <div class="upload-content">
                                <i class="bi bi-cloud-arrow-up"></i>
                                <strong>انتخاب عکس اصلی</strong>
                                <small>HEIC و تصاویر بزرگ قبل از ارسال به WebP بهینه می‌شوند.</small>
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
                        <small id="coverPreviewMeta" class="text-muted d-block mt-2" style="font-size: 12px; line-height: 1.6;"></small>
                    </div>

                    <div class="mb-3">
                        <label for="images" class="form-label-mobile">سایر تصاویر</label>
                        <div class="upload-box" id="galleryUploadBox">
                            <input type="file" name="images[]" id="images" class="upload-input" accept="image/*,.heic,.heif" multiple>
                            <div class="upload-content">
                                <i class="bi bi-images"></i>
                                <strong>افزودن تصاویر گالری</strong>
                                <small>چند تصویر را همزمان انتخاب کنید؛ حجم قبل و بعد از بهینه‌سازی زیر هر عکس دیده می‌شود.</small>
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
                <button type="submit" class="btn btn-mobile-primary flex-fill" id="editSubmitBtn">
                    <i class="bi bi-check-circle me-2"></i>
                    ذخیره تغییرات
                </button>
            </div>
        </form>
    </div>

    <div id="imageCompressOverlay" class="image-compress-overlay" hidden aria-live="polite" aria-busy="true">
        <div class="image-compress-overlay__backdrop"></div>
        <div class="image-compress-overlay__card" role="status">
            <div class="image-compress-overlay__icon-wrap" aria-hidden="true">
                <div class="image-compress-overlay__ring"></div>
                <div class="image-compress-overlay__ring image-compress-overlay__ring--delay"></div>
                <i class="bi bi-images image-compress-overlay__icon"></i>
            </div>
            <p class="image-compress-overlay__title" id="imageCompressOverlayTitle">در حال بهینه‌سازی تصاویر</p>
            <p class="image-compress-overlay__file" id="imageCompressOverlayFile"></p>
            <div class="image-compress-overlay__progress-track">
                <div class="image-compress-overlay__progress-bar" id="imageCompressOverlayBar"></div>
            </div>
            <p class="image-compress-overlay__progress-text" id="imageCompressOverlayProgress"></p>
            <p class="image-compress-overlay__thanks">
                از صبر و شکیبایی شما در فرایند فشرده‌سازی تصاویر سپاسگزاریم
            </p>
        </div>
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
<script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>

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
    align-items: flex-start;
    gap: 8px;
    padding: 6px 8px;
    font-size: 11px;
    color: #666;
}
.new-image-meta {
    flex: 1;
    font-size: 10px;
    line-height: 1.45;
    text-align: right;
    word-break: break-word;
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

.image-compress-overlay {
    position: fixed;
    inset: 0;
    z-index: 10050;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.image-compress-overlay[hidden] {
    display: none !important;
}
.image-compress-overlay__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
}
.image-compress-overlay__card {
    position: relative;
    width: min(100%, 340px);
    background: rgba(255, 255, 255, 0.98);
    border-radius: 20px;
    padding: 28px 22px 24px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.22);
    text-align: center;
}
.image-compress-overlay__icon-wrap {
    position: relative;
    width: 72px;
    height: 72px;
    margin: 0 auto 18px;
}
.image-compress-overlay__ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 3px solid rgba(211, 157, 26, 0.18);
    border-top-color: #D39D1A;
    animation: imageCompressSpin 0.9s linear infinite;
}
.image-compress-overlay__ring--delay {
    inset: 8px;
    border-top-color: #f0c96a;
    animation-duration: 1.2s;
    animation-direction: reverse;
}
@keyframes imageCompressSpin {
    to { transform: rotate(360deg); }
}
.image-compress-overlay__icon {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.65rem;
    color: #D39D1A;
}
.image-compress-overlay__title {
    margin: 0 0 8px;
    font-size: 16px;
    font-weight: 700;
    color: #222;
}
.image-compress-overlay__file {
    margin: 0 0 14px;
    font-size: 12px;
    color: #666;
    line-height: 1.6;
    min-height: 1.6em;
    word-break: break-word;
}
.image-compress-overlay__progress-track {
    height: 8px;
    border-radius: 999px;
    background: #f0f0f0;
    overflow: hidden;
    margin-bottom: 8px;
}
.image-compress-overlay__progress-bar {
    height: 100%;
    width: 0%;
    border-radius: inherit;
    background: linear-gradient(90deg, #D39D1A, #f0c96a);
    transition: width 0.35s ease;
}
.image-compress-overlay__progress-bar.is-indeterminate {
    width: 40%;
    position: relative;
    animation: imageCompressIndeterminate 1.1s ease-in-out infinite;
}
@keyframes imageCompressIndeterminate {
    0% { transform: translateX(-120%); }
    100% { transform: translateX(320%); }
}
.image-compress-overlay__progress-text {
    margin: 0 0 14px;
    font-size: 12px;
    color: #888;
    min-height: 1.4em;
}
.image-compress-overlay__thanks {
    margin: 0;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    font-size: 12px;
    line-height: 1.75;
    color: #9a7b2e;
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
let selectedGalleryItems = [];
let selectedCoverItem = null;
let imageCompressBusy = 0;
let formSubmitBusy = false;
let imageCompressOverlayState = {
    total: 0,
    current: 0,
    indeterminate: true,
};

function mapL() {
    return window.MapUtils ? window.MapUtils.neshanLeaflet() : window.L;
}
const canUseDataTransfer = typeof DataTransfer !== 'undefined';
const MAX_GALLERY_IMAGES = 30;
const existingImagesCount = {{ $home->images->count() }};
const IMAGE_COMPRESS = {
    maxEdge: 1280,
    quality: 0.82,
    skipBelowBytes: 350 * 1024,
    heicQuality: 0.88,
};

document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
    const latVal = document.getElementById('latitude').value;
    const lngVal = document.getElementById('longitude').value;
    const hasCoords = latVal && lngVal && latVal !== '' && lngVal !== '';

    if (!map) {
        map = MapUtils.createMap('map', {
            center: [32.4279, 53.6880],
            zoom: 6,
        });

        map.on('click', function (e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = mapL().marker(e.latlng).addTo(map);
        });
    }

    if (hasCoords) {
        const lat = parseFloat(latVal);
        const lng = parseFloat(lngVal);
        if (!isNaN(lat) && !isNaN(lng)) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = mapL().marker([lat, lng]).addTo(map);
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
        previewMap = MapUtils.createMap(container, {
            center: [lat, lng],
            zoom: 15,
        });
        previewMarker = mapL().marker([lat, lng]).addTo(previewMap);
    } else {
        previewMap.setView([lat, lng], 15);
        if (previewMarker) {
            previewMarker.setLatLng([lat, lng]);
        } else {
            previewMarker = mapL().marker([lat, lng]).addTo(previewMap);
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
    MapUtils.reverseGeocode(lat, lng)
        .then(function (address) {
            if (!address) return;
            const locationText = document.getElementById('locationText');
            if (!locationText) return;
            locationText.textContent = 'عرض: ' + lat.toFixed(6) + ' — طول: ' + lng.toFixed(6) + ' · ' + address;
        });
}

function refreshLocationPreviewIfNeeded() {
    const lat = parseFloat(document.getElementById('latitude')?.value || '');
    const lng = parseFloat(document.getElementById('longitude')?.value || '');
    if (!isNaN(lat) && !isNaN(lng)) {
        renderLocationPreviewMap(lat, lng);
    }
}

// Load cities when province changes — شهر فعلی را تا آمدن لیست جدید پاک نکن
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const citySelect = document.getElementById('city_id');
    const currentCityId = citySelect.value || citySelect.getAttribute('data-current-city') || '{{ $home->city_id }}';
    const currentCityLabel = (citySelect.options[citySelect.selectedIndex] && citySelect.options[citySelect.selectedIndex].text)
        ? citySelect.options[citySelect.selectedIndex].text
        : '';

    if (!provinceId) {
        citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
        return;
    }

    fetch('/api/cities/' + provinceId)
        .then(response => response.json())
        .then(cities => {
            citySelect.innerHTML = '<option value="">انتخاب کنید</option>';
            let found = false;
            (cities || []).forEach(city => {
                const option = document.createElement('option');
                option.value = city.id;
                option.textContent = city.name;
                if (String(city.id) === String(currentCityId)) {
                    option.selected = true;
                    found = true;
                }
                citySelect.appendChild(option);
            });
            if (!found && currentCityId) {
                const keep = document.createElement('option');
                keep.value = currentCityId;
                keep.textContent = currentCityLabel || 'شهر انتخاب‌شده';
                keep.selected = true;
                citySelect.appendChild(keep);
            }
            if (citySelect.value) {
                citySelect.setAttribute('data-current-city', citySelect.value);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (!citySelect.value && currentCityId) {
                citySelect.innerHTML = '<option value="">انتخاب کنید</option>';
                const keep = document.createElement('option');
                keep.value = currentCityId;
                keep.textContent = currentCityLabel || 'شهر انتخاب‌شده';
                keep.selected = true;
                citySelect.appendChild(keep);
            }
        });
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
    const coverPreviewMeta = document.getElementById('coverPreviewMeta');
    if (coverInput && coverPreview) {
        coverInput.addEventListener('change', async function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) {
                return;
            }
            if (!isRasterImageFile(file)) {
                alert('فقط فایل تصویری مجاز است (JPG، PNG، WebP یا HEIC).');
                return;
            }
            if (coverUploadBox) {
                coverUploadBox.classList.add('active');
            }
            try {
                const item = await prepareImageFile(file);
                selectedCoverItem = item;
                setFileInputFromFile(coverInput, item.file);
                coverPreview.src = URL.createObjectURL(item.file);
                if (coverPreviewMeta) {
                    coverPreviewMeta.textContent = formatImageMetaLine(item.meta);
                }
            } catch (err) {
                console.warn('cover prepare failed', err);
                selectedCoverItem = { file: file, meta: buildImageMeta(file, file) };
                if (coverPreviewMeta) {
                    coverPreviewMeta.textContent = formatImageMetaLine(selectedCoverItem.meta);
                }
            }
        });
    }

    const imagesInput = document.getElementById('images');
    const newImagesPreview = document.getElementById('newImagesPreview');
    const galleryUploadBox = document.getElementById('galleryUploadBox');
    if (imagesInput && newImagesPreview) {
        imagesInput.addEventListener('change', async function(e) {
            if (!e.target.files || !e.target.files.length) {
                return;
            }
            await addGalleryFiles(e.target.files);
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
        galleryUploadBox.addEventListener('drop', async function(e) {
            const droppedFiles = Array.from(e.dataTransfer.files);
            if (droppedFiles.length) {
                await addGalleryFiles(droppedFiles);
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
            if (formSubmitBusy) {
                e.preventDefault();
                return;
            }

            const finalCount = getRemainingExistingImagesCount() + selectedGalleryItems.length;
            if (finalCount > MAX_GALLERY_IMAGES) {
                e.preventDefault();
                alert('تعداد کل تصاویر نباید بیشتر از 30 عدد باشد.');
                return;
            }

            e.preventDefault();
            formSubmitBusy = true;
            const submitBtn = document.getElementById('editSubmitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
            }

            syncTabRequiredAttributes();
            normalizeAllPriceFieldsForSubmit();

            const coverInput = document.getElementById('cover');
            if (!selectedCoverItem && coverInput) {
                coverInput.disabled = true;
            }

            try {
                if (typeof window.prepareCompressedDocumentInput === 'function') {
                    await window.prepareCompressedDocumentInput('document');
                }

                syncGalleryInputFiles();
                if (selectedCoverItem) {
                    setFileInputFromFile(document.getElementById('cover'), selectedCoverItem.file);
                }
                const citySelectForSubmit = document.getElementById('city_id');
                if (citySelectForSubmit && !citySelectForSubmit.value && citySelectForSubmit.getAttribute('data-current-city')) {
                    const keepId = citySelectForSubmit.getAttribute('data-current-city');
                    let foundOpt = false;
                    Array.prototype.forEach.call(citySelectForSubmit.options, function (opt) {
                        if (String(opt.value) === String(keepId)) {
                            opt.selected = true;
                            foundOpt = true;
                        }
                    });
                    if (!foundOpt) {
                        const keep = document.createElement('option');
                        keep.value = keepId;
                        keep.textContent = 'شهر انتخاب‌شده';
                        keep.selected = true;
                        citySelectForSubmit.appendChild(keep);
                    }
                }

                if (editNeedsManualSubmit()) {
                    showImageCompressOverlay({
                        title: 'در حال ذخیره تغییرات',
                        indeterminate: true,
                        fileName: '',
                    });
                    const fd = new FormData(form);
                    fd.delete('images[]');
                    selectedGalleryItems.forEach(function (item) {
                        if (item && item.file) {
                            fd.append('images[]', item.file);
                        }
                    });
                    if (!selectedCoverItem) {
                        fd.delete('cover');
                    } else if (selectedCoverItem.file) {
                        fd.set('cover', selectedCoverItem.file);
                    }
                    const citySelect = document.getElementById('city_id');
                    const provinceSelect = document.getElementById('province_id');
                    if (provinceSelect && provinceSelect.value) {
                        fd.set('province_id', provinceSelect.value);
                    }
                    const cityValue = (citySelect && citySelect.value)
                        || (citySelect && citySelect.getAttribute('data-current-city'))
                        || '';
                    if (cityValue) {
                        fd.set('city_id', cityValue);
                    }
                    const res = await fetch(form.action, {
                        method: 'POST',
                        body: fd,
                        credentials: 'same-origin',
                        redirect: 'follow',
                    });
                    window.location.href = res.url;
                    return;
                }

                form.submit();
            } catch (err) {
                console.warn('edit submit failed', err);
                formSubmitBusy = false;
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                if (coverInput) {
                    coverInput.disabled = false;
                }
                hideImageCompressOverlay();
                alert('ارسال تغییرات انجام نشد. اتصال را بررسی کنید و دوباره ذخیره کنید.');
            }
        });
    }
});

function syncGalleryInputFiles() {
    const input = document.getElementById('images');
    if (!input || !canUseDataTransfer) return;
    try {
        const dt = new DataTransfer();
        selectedGalleryItems.forEach(function (item) {
            if (item && item.file) {
                dt.items.add(item.file);
            }
        });
        input.files = dt.files;
    } catch (err) {
        // مرورگرهایی که DataTransfer را برای input فایل محدود می‌کنند
    }
}

function editNeedsManualSubmit() {
    const coverInput = document.getElementById('cover');
    const imagesInput = document.getElementById('images');
    if (selectedCoverItem && (!coverInput || !coverInput.files || !coverInput.files.length)) {
        return true;
    }
    if (selectedGalleryItems.length && (!imagesInput || !imagesInput.files || !imagesInput.files.length)) {
        return true;
    }
    return false;
}

function getMarkedForDeleteCount() {
    return document.querySelectorAll('input[name="delete_existing_images[]"]:checked').length;
}

function getRemainingExistingImagesCount() {
    return Math.max(0, existingImagesCount - getMarkedForDeleteCount());
}

function getAvailableImageSlots() {
    return Math.max(0, MAX_GALLERY_IMAGES - (getRemainingExistingImagesCount() + selectedGalleryItems.length));
}

function escapeHtml(str) {
    return String(str || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/"/g, '&quot;');
}

function canvasSupportsWebp() {
    try {
        const canvas = document.createElement('canvas');
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    } catch (e) {
        return false;
    }
}

function getOutputImageFormat() {
    if (canvasSupportsWebp()) {
        return { mime: 'image/webp', ext: 'webp' };
    }
    return { mime: 'image/jpeg', ext: 'jpg' };
}

function isRasterImageFile(file) {
    if (!file) {
        return false;
    }
    const type = (file.type || '').toLowerCase();
    const name = (file.name || '').toLowerCase();
    if (type.startsWith('image/')) {
        return true;
    }
    return /\.(jpe?g|png|gif|webp|bmp|heic|heif)$/i.test(name);
}

function isHeicFile(file) {
    if (!file) {
        return false;
    }
    const type = (file.type || '').toLowerCase();
    const name = (file.name || '').toLowerCase();
    return /heic|heif/.test(type) || /\.heic$|\.heif$/.test(name);
}

function getFileExtensionLabel(file) {
    const name = (file && file.name) ? file.name : '';
    const ext = name.split('.').pop();
    return ext ? ext.toUpperCase() : 'تصویر';
}

function buildImageMeta(originalFile, processedFile) {
    const originalExt = getFileExtensionLabel(originalFile);
    const processedExt = getFileExtensionLabel(processedFile);
    const formatChanged = originalExt !== processedExt;

    return {
        originalName: originalFile.name || 'image',
        processedName: processedFile.name || originalFile.name || 'image.jpg',
        originalSize: originalFile.size || 0,
        processedSize: processedFile.size || 0,
        formatLabel: formatChanged ? (originalExt + ' → ' + processedExt) : processedExt,
        optimized: (processedFile.size || 0) < (originalFile.size || 0),
    };
}

function formatFileSize(bytes) {
    if (bytes >= 1024 * 1024) {
        return (bytes / 1024 / 1024).toFixed(1) + ' MB';
    }
    return Math.max(1, Math.round(bytes / 1024)) + ' KB';
}

function formatImageMetaLine(meta) {
    const lines = [];
    lines.push(meta.processedName);
    if (meta.optimized && meta.originalSize > meta.processedSize) {
        lines.push(formatFileSize(meta.originalSize) + ' → ' + formatFileSize(meta.processedSize) + ' (بهینه‌شده)');
    } else {
        lines.push(formatFileSize(meta.processedSize));
    }
    lines.push('فرمت: ' + meta.formatLabel);
    return lines.join(' · ');
}

function shouldSkipClientCompress(file) {
    if (!isRasterImageFile(file)) {
        return true;
    }
    if (file.size <= IMAGE_COMPRESS.skipBelowBytes) {
        return true;
    }
    const type = (file.type || '').toLowerCase();
    const name = (file.name || '').toLowerCase();
    if (type === 'image/gif' || /\.gif$/i.test(name)) {
        return true;
    }
    return false;
}

function setFileInputFromFile(input, file) {
    if (!input || !file) {
        return false;
    }
    try {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        return input.files && input.files.length > 0;
    } catch (err) {
        return false;
    }
}

function renderImageCompressOverlay() {
    const overlay = document.getElementById('imageCompressOverlay');
    const titleEl = document.getElementById('imageCompressOverlayTitle');
    const fileEl = document.getElementById('imageCompressOverlayFile');
    const barEl = document.getElementById('imageCompressOverlayBar');
    const progressEl = document.getElementById('imageCompressOverlayProgress');
    if (!overlay || !titleEl || !fileEl || !barEl || !progressEl) {
        return;
    }

    const state = imageCompressOverlayState;
    if (state.indeterminate || !state.total) {
        barEl.style.width = '38%';
        barEl.classList.add('is-indeterminate');
        progressEl.textContent = 'لطفاً چند لحظه صبر کنید…';
    } else {
        barEl.classList.remove('is-indeterminate');
        const percent = Math.min(100, Math.round((state.current / state.total) * 100));
        barEl.style.width = percent + '%';
        progressEl.textContent = 'تصویر ' + state.current + ' از ' + state.total + ' (' + percent + '٪)';
    }

    fileEl.textContent = state.fileName || '';
    if (state.title) {
        titleEl.textContent = state.title;
    }
}

function showImageCompressOverlay(options) {
    const overlay = document.getElementById('imageCompressOverlay');
    if (!overlay) {
        return;
    }
    imageCompressOverlayState = Object.assign({
        total: 0,
        current: 0,
        indeterminate: true,
        fileName: '',
        title: 'در حال بهینه‌سازی تصاویر',
    }, options || {});
    overlay.hidden = false;
    renderImageCompressOverlay();
}

function updateImageCompressOverlay(patch) {
    imageCompressOverlayState = Object.assign({}, imageCompressOverlayState, patch || {});
    renderImageCompressOverlay();
}

function hideImageCompressOverlay() {
    const overlay = document.getElementById('imageCompressOverlay');
    if (overlay) {
        overlay.hidden = true;
    }
    imageCompressOverlayState = { total: 0, current: 0, indeterminate: true };
}

function beginImageCompress(message, options) {
    imageCompressBusy += 1;
    if (imageCompressBusy === 1) {
        showImageCompressOverlay(Object.assign({ title: message }, options || {}));
    } else {
        updateImageCompressOverlay(Object.assign({ title: message || imageCompressOverlayState.title }, options || {}));
    }
}

function endImageCompress() {
    imageCompressBusy = Math.max(0, imageCompressBusy - 1);
    if (imageCompressBusy === 0) {
        hideImageCompressOverlay();
    }
}

async function convertHeicToJpeg(file) {
    if (typeof heic2any !== 'function') {
        throw new Error('heic2any unavailable');
    }
    const result = await heic2any({
        blob: file,
        toType: 'image/jpeg',
        quality: IMAGE_COMPRESS.heicQuality,
    });
    const blob = Array.isArray(result) ? result[0] : result;
    if (!blob) {
        throw new Error('heic conversion empty');
    }
    const baseName = (file.name || 'image').replace(/\.[^.]+$/i, '');
    return new File([blob], baseName + '.jpg', {
        type: 'image/jpeg',
        lastModified: Date.now(),
    });
}

function compressImageFile(file) {
    if (shouldSkipClientCompress(file)) {
        return Promise.resolve(file);
    }

    return new Promise(function (resolve) {
        const url = URL.createObjectURL(file);
        const img = new Image();
        const timer = setTimeout(function () {
            URL.revokeObjectURL(url);
            resolve(file);
        }, 25000);

        img.onload = function () {
            clearTimeout(timer);
            URL.revokeObjectURL(url);

            let w = img.naturalWidth;
            let h = img.naturalHeight;
            if (!w || !h) {
                resolve(file);
                return;
            }

            const maxEdge = IMAGE_COMPRESS.maxEdge;
            if (w > maxEdge || h > maxEdge) {
                if (w >= h) {
                    h = Math.round(h * maxEdge / w);
                    w = maxEdge;
                } else {
                    w = Math.round(w * maxEdge / h);
                    h = maxEdge;
                }
            }

            const canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                resolve(file);
                return;
            }
            ctx.drawImage(img, 0, 0, w, h);

            const output = getOutputImageFormat();
            canvas.toBlob(function (blob) {
                if (!blob || blob.size >= file.size * 0.95) {
                    resolve(file);
                    return;
                }
                const baseName = (file.name || 'image').replace(/\.[^.]+$/i, '');
                resolve(new File([blob], baseName + '.' + output.ext, {
                    type: output.mime,
                    lastModified: Date.now(),
                }));
            }, output.mime, IMAGE_COMPRESS.quality);
        };

        img.onerror = function () {
            clearTimeout(timer);
            URL.revokeObjectURL(url);
            resolve(file);
        };

        img.src = url;
    });
}

async function prepareImageFile(file, options) {
    const silent = options && options.silent;
    const originalFile = file;
    let working = file;

    if (isHeicFile(file)) {
        if (!silent) {
            beginImageCompress('در حال تبدیل HEIC…', {
                fileName: file.name,
                indeterminate: true,
            });
        }
        try {
            working = await convertHeicToJpeg(file);
        } finally {
            if (!silent) {
                endImageCompress();
            }
        }
    }

    if (!silent) {
        beginImageCompress('در حال بهینه‌سازی و تبدیل به WebP…', {
            fileName: working.name || file.name,
            indeterminate: true,
        });
    }
    let processed;
    try {
        processed = await compressImageFile(working);
    } finally {
        if (!silent) {
            endImageCompress();
        }
    }

    return {
        file: processed,
        meta: buildImageMeta(originalFile, processed),
    };
}

async function addGalleryFiles(files) {
    const incoming = Array.from(files).filter(function (file) {
        return isRasterImageFile(file);
    });

    if (incoming.length === 0 && files.length > 0) {
        alert('فقط فایل تصویری مجاز است (JPG، PNG، WebP یا HEIC).');
        return;
    }

    const availableSlots = getAvailableImageSlots();
    if (availableSlots <= 0) {
        alert('شما به سقف 30 تصویر رسیده‌اید. ابتدا تعدادی از تصاویر فعلی را حذف کنید.');
        return;
    }

    const toProcess = incoming.slice(0, availableSlots);
    if (toProcess.length < incoming.length) {
        alert('حداکثر ' + MAX_GALLERY_IMAGES + ' تصویر مجاز است. فقط ' + availableSlots + ' تصویر دیگر می‌توانید اضافه کنید.');
    }
    if (toProcess.length === 0) {
        return;
    }

    showImageCompressOverlay({
        title: 'در حال پردازش گالری تصاویر',
        total: toProcess.length,
        current: 0,
        indeterminate: false,
        fileName: toProcess[0].name,
    });

    for (let i = 0; i < toProcess.length; i++) {
        updateImageCompressOverlay({
            current: i + 1,
            total: toProcess.length,
            fileName: toProcess[i].name,
            title: isHeicFile(toProcess[i])
                ? 'در حال تبدیل و فشرده‌سازی…'
                : 'در حال بهینه‌سازی تصاویر',
            indeterminate: false,
        });

        try {
            const item = await prepareImageFile(toProcess[i], { silent: true });
            selectedGalleryItems.push(item);
        } catch (err) {
            console.warn('gallery prepare failed', err);
            selectedGalleryItems.push({
                file: toProcess[i],
                meta: buildImageMeta(toProcess[i], toProcess[i]),
            });
        }
    }

    hideImageCompressOverlay();
    syncGalleryInputFiles();
    renderNewImagesPreview();
    const galleryUploadBox = document.getElementById('galleryUploadBox');
    if (galleryUploadBox) {
        galleryUploadBox.classList.add('active');
    }
}

function renderNewImagesPreview() {
    const container = document.getElementById('newImagesPreview');
    if (!container) return;
    container.innerHTML = '';

    selectedGalleryItems.forEach(function (item, index) {
        const col = document.createElement('div');
        col.className = 'col-6';
        const url = URL.createObjectURL(item.file);
        col.innerHTML =
            '<div class="image-preview-card">' +
                '<img src="' + url + '" alt="new-image-' + index + '">' +
                '<div class="new-image-actions">' +
                    '<span class="new-image-meta">' + escapeHtml(formatImageMetaLine(item.meta)) + '</span>' +
                    '<button type="button" class="remove-new-image" data-index="' + index + '">×</button>' +
                '</div>' +
            '</div>';
        container.appendChild(col);
    });

    container.querySelectorAll('.remove-new-image').forEach(function (button) {
        button.addEventListener('click', function () {
            const index = Number(this.dataset.index);
            selectedGalleryItems.splice(index, 1);
            syncGalleryInputFiles();
            renderNewImagesPreview();
        });
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
