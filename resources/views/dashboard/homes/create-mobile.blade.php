@extends('layouts.main.main_mobile', ['title' => setting('submit-home:page-title')])

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    @include('layouts.main.partials.navbar-mobile')
    
    <!-- Page Header -->
    <div class="container px-3 py-3">
        <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h1 class="fw-bold mb-2" style="font-size: 18px; color: #333;">{{ setting('submit-home:title') }}</h1>
            <p class="mb-0" style="font-size: 14px; color: #666;">ثبت اقامتگاه جدید</p>
            <p class="mb-0 mt-2" style="font-size: 12px; color: #888; line-height: 1.7;">
                شما می‌توانید در صورت خروج از این صفحه، ادامه ثبت اقامتگاه خود را از صفحه
                <a href="{{ route('dashboard.homes.index') }}" class="text-decoration-none fw-semibold" style="color: #D39D1A;">اقامتگاه‌های من</a>
                ادامه دهید. هر مرحله به‌صورت خودکار ذخیره می‌شود.
            </p>
        </div>
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3" style="font-size: 14px;">
                <strong>خطاهای فرم:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success mt-3" style="font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Error Message -->
        @if (session('danger'))
            <div class="alert alert-danger mt-3" style="font-size: 14px;">
                {{ session('danger') }}
            </div>
        @endif
    </div>

    <!-- Progress Steps -->
    <div class="container px-3 py-3">
        <div class="mobile-edit-tabs" id="createHomeSteps">
            <button type="button" class="tab-pill step-pill active" data-step="1">اطلاعات</button>
            <button type="button" class="tab-pill step-pill" data-step="2">تصاویر</button>
            <button type="button" class="tab-pill step-pill" data-step="3">اتاق</button>
            <button type="button" class="tab-pill step-pill" data-step="4">مکان</button>
            <button type="button" class="tab-pill step-pill" data-step="5">قیمت</button>
            <button type="button" class="tab-pill step-pill" data-step="6">تخفیف</button>
            <button type="button" class="tab-pill step-pill" data-step="7">امکانات</button>
            <button type="button" class="tab-pill step-pill" data-step="8">ایمنی</button>
            <button type="button" class="tab-pill step-pill" data-step="9">بهداشت</button>
            <button type="button" class="tab-pill step-pill" data-step="10">قوانین</button>
            <button type="button" class="tab-pill step-pill" data-step="11">مدارک</button>
        </div>
    </div>

    <!-- Form Container -->
    <div class="container px-3 pb-4">
        <form action="{{ route('dashboard.homes.store', $home) }}" method="POST" enctype="multipart/form-data" id="createHomeForm">
            @csrf
            
            <!-- Step 1: Basic Information -->
            <div class="step-content active" id="step1">
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 class="fw-bold mb-3" style="font-size: 16px; color: #333;">اطلاعات کلی اقامتگاه</h5>
                    
                    <!-- Home Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label" style="font-size: 14px;">نام اقامتگاه <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control" 
                               placeholder="نام اقامتگاه خود را وارد کنید" 
                               value="{{ old('name', $home->name) }}" 
                               style="font-size: 14px;" required>
                        @error('name')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label" style="font-size: 14px;">توضیحات <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control" rows="4" 
                                  placeholder="توضیحات کامل اقامتگاه خود را بنویسید" 
                                  style="font-size: 14px;" required>{{ old('description', $home->description) }}</textarea>
                        @error('description')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Home Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label" style="font-size: 14px;">نوع اقامتگاه <span class="text-danger">*</span></label>
                        <select name="type" id="type" class="form-select" style="font-size: 14px;" required>
                            <option value="">انتخاب کنید</option>
                            <option value="vilaiy" {{ old('type', $home->type ?? '') == 'vilaiy' ? 'selected' : '' }}>ویلایی</option>
                            <option value="aparteman" {{ old('type', $home->type ?? '') == 'aparteman' ? 'selected' : '' }}>آپارتمان</option>
                            <option value="swiit" {{ old('type', $home->type ?? '') == 'swiit' ? 'selected' : '' }}>سوئیت</option>
                        </select>
                        @error('type')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="yard" class="form-label" style="font-size: 14px;">متراژ کل (متر)</label>
                            <input type="number" name="yard" id="yard" class="form-control" min="0"
                                   value="{{ old('yard', $home->yard_meter) }}" style="font-size: 14px;" inputmode="numeric">
                        </div>
                        <div class="col-6">
                            <label for="infrastructure" class="form-label" style="font-size: 14px;">متراژ زیربنا (متر)</label>
                            <input type="number" name="infrastructure" id="infrastructure" class="form-control" min="0"
                                   value="{{ old('infrastructure', $home->infrastructure_meter) }}" style="font-size: 14px;" inputmode="numeric">
                        </div>
                    </div>

                    <!-- Guest Count -->
                    <div class="mb-3">
                        <label for="main_guest" class="form-label" style="font-size: 14px;">تعداد مهمان <span class="text-danger">*</span></label>
                        <select name="main_guest" id="main_guest" class="form-select" style="font-size: 14px;" required>
                            <option value="">انتخاب کنید</option>
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ old('main_guest', $home->main_guest) == $i ? 'selected' : '' }}>{{ $i }} نفر</option>
                            @endfor
                        </select>
                        @error('main_guest')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="extra_guest" class="form-label" style="font-size: 14px;">تعداد نفرات اضافه</label>
                        <select name="extra_guest" id="extra_guest" class="form-select" style="font-size: 14px;">
                            @for($i = 0; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ (int) old('extra_guest', $home->extra_guest ?? 0) === $i ? 'selected' : '' }}>{{ $i }} نفر</option>
                            @endfor
                        </select>
                        @error('extra_guest')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="atmosphere" class="form-label" style="font-size: 14px;">فضا</label>
                            <select name="atmosphere" id="atmosphere" class="form-select" style="font-size: 14px;">
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
                            <label for="area" class="form-label" style="font-size: 14px;">منطقه</label>
                            <select name="area" id="area" class="form-select" style="font-size: 14px;">
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

            <!-- Step 2: Bedrooms -->
            <div class="step-content" id="step3">
                <x-dashboard.home.mobile-form-rooms :home="$home" />
            </div>

            <!-- Step 3: Location -->
            <div class="step-content" id="step4">
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 class="fw-bold mb-3" style="font-size: 16px; color: #333;">مکان اقامتگاه</h5>
                    
                    <!-- Province -->
                    <div class="mb-3">
                        <label for="province_id" class="form-label" style="font-size: 14px;">استان <span class="text-danger">*</span></label>
                        <select name="province_id" id="province_id" class="form-select" style="font-size: 14px;" required>
                            <option value="">انتخاب استان</option>
                            @foreach(\App\Models\Province::getFromCache() as $province)
                                <option value="{{ $province->id }}" {{ old('province_id', $home->province_id) == $province->id ? 'selected' : '' }}>
                                    {{ $province->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('province_id')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="mb-3">
                        <label for="city_id" class="form-label" style="font-size: 14px;">شهر <span class="text-danger">*</span></label>
                        <select name="city_id" id="city_id" class="form-select" style="font-size: 14px;" required>
                            <option value="">ابتدا استان را انتخاب کنید</option>
                            @if($home->city_id)
                                <option value="{{ $home->city_id }}" selected>{{ $home->city->name }}</option>
                            @endif
                        </select>
                        @error('city_id')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label for="address" class="form-label" style="font-size: 14px;">آدرس کامل <span class="text-danger">*</span></label>
                        <textarea name="address" id="address" class="form-control" rows="3" 
                                  placeholder="آدرس کامل اقامتگاه" 
                                  style="font-size: 14px;" required>{{ old('address', $home->address) }}</textarea>
                        @error('address')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Map Selection -->
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 14px;">انتخاب موقعیت مکانی</label>
                        <button type="button" class="btn btn-outline-secondary w-100" data-bs-toggle="modal" data-bs-target="#mapSelectionModal" style="font-size: 14px; border-radius: 8px; border: 1px solid #ddd; color: #666;">
                            <i class="bi bi-map me-2"></i>
                            انتخاب از روی نقشه
                        </button>
                        <small class="text-muted" style="font-size: 12px;">برای انتخاب دقیق موقعیت مکانی روی نقشه کلیک کنید</small>
                    </div>

                    <!-- Coordinates Display -->
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label" style="font-size: 14px;">عرض جغرافیایی</label>
                                <input type="text" name="latitude" id="latitude" class="form-control" 
                                       placeholder="مثال: 35.6892" 
                                       value="{{ old('latitude', $home->latitude) }}" 
                                       style="font-size: 14px;" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label" style="font-size: 14px;">طول جغرافیایی</label>
                                <input type="text" name="longitude" id="longitude" class="form-control" 
                                       placeholder="مثال: 51.3890" 
                                       value="{{ old('longitude', $home->longitude) }}" 
                                       style="font-size: 14px;" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Map preview (shown after location is selected) -->
                    <div id="locationPreviewWrap" class="mb-3" style="display: none;">
                        <label class="form-label d-block mb-2" style="font-size: 14px;">پیش‌نمایش موقعیت روی نقشه</label>
                        <div id="locationPreviewMap" style="height: 220px; width: 100%; border-radius: 12px; border: 1px solid #dee2e6; overflow: hidden; z-index: 1;"></div>
                        <small id="locationText" class="text-muted d-block mt-2" style="font-size: 12px;">موقعیت انتخاب نشده</small>
                    </div>
                </div>
            </div>

            <!-- Step 4: Images -->
            <div class="step-content" id="step2">
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 class="fw-bold mb-3" style="font-size: 16px; color: #333;">تصاویر اقامتگاه</h5>

                    @if($home->cover)
                        <div class="mb-3">
                            <span class="form-label d-block" style="font-size: 14px;">کاور فعلی (پیش‌نویس)</span>
                            <div class="rounded overflow-hidden border">
                                <img src="{{ $home->cover_path }}" alt="کاور فعلی" class="w-100" style="max-height: 180px; object-fit: cover;">
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 12px;">در صورت تمایل می‌توانید کاور جدید انتخاب کنید؛ در غیر این صورت همین تصویر حفظ می‌شود.</small>
                        </div>
                    @endif

                    @if($home->images->isNotEmpty())
                        <div class="mb-3">
                            <span class="form-label d-block" style="font-size: 14px;">تصاویر فعلی گالری</span>
                            <div class="row g-2">
                                @foreach($home->images as $image)
                                    <div class="col-4">
                                        <img src="{{ $image->image_path }}" alt="" class="w-100 rounded border" style="height: 88px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Cover Image -->
                    <div class="mb-3">
                        <label for="cover" class="form-label" style="font-size: 14px;">تصویر اصلی @if(!$home->cover)<span class="text-danger">*</span>@endif</label>
                        <input type="file" name="cover" id="cover" class="form-control" 
                               accept="image/*" style="font-size: 14px;" @if(!$home->cover) required @endif>
                        <small class="text-muted" style="font-size: 12px;">تصویر اصلی اقامتگاه؛ قبل از ارسال به‌صورت خودکار بهینه می‌شود</small>
                        @error('cover')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="coverNewPreviewWrap" style="display: none;">
                        <span class="form-label d-block" style="font-size: 14px;">پیش‌نمایش کاور جدید</span>
                        <div class="rounded overflow-hidden border mt-1">
                            <img src="" alt="" id="coverNewPreview" class="w-100" style="max-height: 180px; object-fit: cover;">
                        </div>
                    </div>

                    <!-- Gallery Images -->
                    <div class="mb-3">
                        <label for="images" class="form-label" style="font-size: 14px;">گالری تصاویر</label>
                        <div id="galleryDropZone" class="border rounded p-3" style="border: 2px dashed #ddd; background: #f8f9fa;">
                            <input type="file" name="images[]" id="images" class="form-control" 
                                   accept="image/*" multiple style="font-size: 14px; border: none; background: transparent;">
                            <div class="text-center mt-2">
                                <i class="bi bi-cloud-upload fs-1 text-muted"></i>
                                <p class="text-muted mb-0" style="font-size: 12px;">برای انتخاب چندین تصویر کلیک کنید</p>
                                <small class="text-muted" style="font-size: 11px;">حداکثر 10 تصویر؛ حجم قبل از ارسال کم می‌شود</small>
                            </div>
                        </div>
                        @error('images')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="imageCompressStatus" class="small text-muted mb-2" style="display: none;"></div>

                    <!-- Image Preview -->
                    <div id="imagePreview" class="row g-2"></div>
                </div>
            </div>

            <!-- Step 5: Pricing (هم‌تراز با edit-mobile) -->
            <div class="step-content" id="step5">
                @php
                    $mobilePriceValue = function ($key, $fallback = null) {
                        $v = old($key, $fallback);
                        if ($v === null || $v === '') {
                            return '';
                        }
                        $int = (int) round((float) $v);
                        return $int > 0 ? (string) $int : '';
                    };
                @endphp
                <div class="bg-white rounded-3 p-3 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h5 class="fw-bold mb-3" style="font-size: 16px; color: #333;">قیمت‌گذاری</h5>

                    <div class="mb-3">
                        <label for="week_price" class="form-label" style="font-size: 14px;">قیمت اول هفته (شنبه تا سه‌شنبه) <span class="text-danger">*</span></label>
                        <input type="text" name="week_price" id="week_price" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 2500000"
                               value="{{ $mobilePriceValue('week_price', $home->week_price) }}" style="font-size: 14px;" required>
                        <small id="week_price_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        @error('week_price')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="wed_price" class="form-label" style="font-size: 14px;">قیمت چهارشنبه (تومان)</label>
                        <input type="text" name="wed_price" id="wed_price" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 3000000"
                               value="{{ $mobilePriceValue('wed_price', $home->wed_price) }}" style="font-size: 14px;">
                        <small id="wed_price_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        @error('wed_price')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="thu_price" class="form-label" style="font-size: 14px;">قیمت پنجشنبه (تومان)</label>
                        <input type="text" name="thu_price" id="thu_price" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 3500000"
                               value="{{ $mobilePriceValue('thu_price', $home->thu_price) }}" style="font-size: 14px;">
                        <small id="thu_price_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        @error('thu_price')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="fri_price" class="form-label" style="font-size: 14px;">قیمت جمعه (تومان)</label>
                        <input type="text" name="fri_price" id="fri_price" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 4000000"
                               value="{{ $mobilePriceValue('fri_price', $home->fri_price) }}" style="font-size: 14px;">
                        <small id="fri_price_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        @error('fri_price')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="price_per_surplus" class="form-label" style="font-size: 14px;">مبلغ به ازای هر نفر اضافه (تومان)</label>
                        <input type="text" name="price_per_surplus" id="price_per_surplus" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 500000"
                               value="{{ $mobilePriceValue('price_per_surplus', $home->price_per_surplus) }}" style="font-size: 14px;">
                        <small id="price_per_surplus_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        @error('price_per_surplus')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="cleaning_fee" class="form-label" style="font-size: 14px;">هزینه نظافت (تومان)</label>
                        <input type="text" name="cleaning_fee" id="cleaning_fee" class="form-control price-field"
                               inputmode="numeric" autocomplete="off" placeholder="مثال: 300000"
                               value="{{ $mobilePriceValue('cleaning_fee', $home->cleaning_fee) }}" style="font-size: 14px;">
                        <small id="cleaning_fee_words" class="price-words text-muted d-block mt-1" style="font-size: 12px; display: none;"></small>
                        <small class="text-muted d-block mt-1" style="font-size: 12px; line-height: 1.6;">
                            در صورت عدم نظافت توسط مهمان و کثیف بودن اقامتگاه، این مبلغ از مهمان دریافت می‌شود.
                        </small>
                        @error('cleaning_fee')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>


            <!-- Step 6: Discount -->
            <div class="step-content" id="step6">
                <x-dashboard.home.mobile-form-discount :home="$home" />
            </div>

            <!-- Step 7: Rules -->
            <div class="step-content" id="step10">
                <x-dashboard.home.mobile-form-rules :home="$home" />
            </div>

            <!-- Step 8: Safety -->
            <div class="step-content" id="step8">
                <x-dashboard.home.mobile-form-safety :home="$home" />
            </div>

            <!-- Step 9: Options -->
            <div class="step-content" id="step7">
                <x-dashboard.home.mobile-form-options :home="$home" />
            </div>

            <!-- Step 10: Health -->
            <div class="step-content" id="step9">
                <x-dashboard.home.mobile-form-health :home="$home" />
            </div>


            <!-- Step 11: Documents -->
            <div class="step-content" id="step11">
                <x-dashboard.home.mobile-form-document :home="$home" />
            </div>

            <!-- Navigation Buttons -->
            <div class="bg-white rounded-3 p-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" id="prevBtn" class="btn btn-outline-secondary w-100" style="font-size: 14px; border-radius: 8px; border: 1px solid #ddd; color: #666; display: none;">
                            <i class="bi bi-arrow-right me-2"></i>
                            قبلی
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="nextBtn" class="btn btn-outline-primary w-100" style="font-size: 14px; border-radius: 8px; border: 1px solid #007bff; color: #007bff;">
                            بعدی
                            <i class="bi bi-arrow-left me-2"></i>
                        </button>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" id="submitBtn" class="btn btn-outline-success w-100" style="font-size: 14px; border-radius: 8px; border: 1px solid #28a745; color: #28a745; display: none;">
                        <i class="bi bi-check-circle me-2"></i>
                        ثبت اقامتگاه
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Map Selection Modal -->
    <div class="modal fade" id="mapSelectionModal" tabindex="-1" aria-labelledby="mapSelectionModalLabel" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapSelectionModalLabel" style="font-size: 16px;">انتخاب موقعیت مکانی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="mapSelection" style="width: 100%; height: 100%; min-height: 400px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="font-size: 14px; border-radius: 8px; border: 1px solid #ddd; color: #666;">انصراف</button>
                    <button type="button" class="btn" id="confirmLocation" style="font-size: 14px; border-radius: 8px; border: 1px solid #D39D1A; color: #000000;">
                        <i class="bi bi-check-circle me-2"></i>
                        تایید موقعیت
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<!-- Leaflet.js -->
<link rel="stylesheet" href="{{ asset('vendor/leaflet/dist/leaflet.css') }}" />
<script src="{{ asset('vendor/leaflet/dist/leaflet.js') }}"></script>

<style>
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    flex: 1;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 5px;
}

.step-item.active .step-number {
    background: #D39D1A;
    color: white;
}

.step-item.completed .step-number {
    background: #28a745;
    color: white;
}

.step-text {
    font-size: 10px;
    color: #6c757d;
}

.step-item.active .step-text {
    color: #D39D1A;
    font-weight: bold;
}

.step-line {
    height: 2px;
    background: #e9ecef;
    flex: 1;
    margin: 0 10px;
    margin-top: 15px;
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

.image-preview {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #ddd;
    background: white;
}

.image-preview img {
    width: 100%;
    height: 100px;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.remove-image:hover {
    background: rgba(220, 53, 69, 1);
    transform: scale(1.1);
}

.image-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 2px 5px;
    text-align: center;
}

#galleryDropZone {
    cursor: pointer;
    transition: all 0.2s;
}

#galleryDropZone:hover {
    border-color: #007bff !important;
    background-color: #f8f9fa !important;
}

.price-words {
    line-height: 1.6;
    color: #6c757d;
}

#confirmLocation {
    border: 1px solid #D39D1A !important;
    color: #000000 !important;
}

#confirmLocation:hover {
    background-color: #D39D1A !important;
    color: #000000 !important;
    border-color: #D39D1A !important;
}

#confirmLocation:active,
#confirmLocation:focus {
    background-color: #D39D1A !important;
    color: #000000 !important;
    border-color: #D39D1A !important;
    box-shadow: 0 0 0 0.2rem rgba(211, 157, 26, 0.25) !important;
}

.mobile-edit-tabs {
    display: flex;
    gap: 8px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}
.mobile-edit-tabs::-webkit-scrollbar { display: none; }
.tab-pill, .step-pill {
    border: 1px solid #e7e7e7;
    background: #fff;
    color: #444;
    padding: 8px 12px;
    border-radius: 999px;
    font-size: 13px;
    white-space: nowrap;
}
.tab-pill.active, .step-pill.active {
    background: #D39D1A;
    border-color: #D39D1A;
    color: #fff;
}
.step-pill.completed {
    background: #e8f5e9;
    border-color: #28a745;
    color: #1b5e20;
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
    transition: border-color 0.2s, background 0.2s;
}
.mobile-option-chip input:checked + .mobile-option-chip-body {
    border-color: #D39D1A;
    background: #fff9eb;
}
.mobile-option-chip-title {
    font-size: 12px;
    text-align: center;
    color: #333;
    line-height: 1.4;
}
.mobile-check-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.mobile-check-row {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}
.mobile-check-label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    margin: 0;
    cursor: pointer;
}

</style>

<script>
if (typeof window.initMobileBedrooms === 'function') {
    window.initMobileBedrooms();
}

if (typeof window.initMobileDocumentPreview === 'function') {
    window.initMobileDocumentPreview();
}

window.createHomeDraftUrl = @json(route('dashboard.homes.draft', $home));
const initialDraftStep = Math.min(Math.max({{ (int) ($home->draft_step ?? 1) }}, 1), 11);

let currentStep = 1;
const totalSteps = 11;

async function persistDraftStep(step) {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
    const fd = new FormData();
    fd.append('_token', token);
    fd.append('step', String(step));

    if (step === 2) {
        const coverEl = document.getElementById('cover');
        if (coverEl && coverEl.files && coverEl.files.length) {
            fd.append('cover', coverEl.files[0]);
        }
        const imagesEl = document.getElementById('images');
        if (imagesEl && imagesEl.files && imagesEl.files.length) {
            for (let i = 0; i < imagesEl.files.length; i++) {
                fd.append('images[]', imagesEl.files[i]);
            }
        }
    } else if (step === 11) {
        const documentEl = document.getElementById('document');
        if (documentEl && documentEl.files && documentEl.files.length) {
            fd.append('document', documentEl.files[0]);
        }
    } else {
        const root = document.getElementById('step' + step);
        if (root) {
            root.querySelectorAll('input, select, textarea').forEach(function (el) {
                if (!el.name || el.type === 'file' || el.name === '_token') {
                    return;
                }
                if (el.type === 'checkbox' && !el.checked) {
                    return;
                }
                fd.append(el.name, el.value);
            });
        }
    }

    const res = await fetch(window.createHomeDraftUrl, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': token,
        },
        body: fd,
        credentials: 'same-origin',
    });

    if (res.ok) {
        const data = await res.json().catch(function () { return {}; });
        if (data.warnings && data.warnings.length) {
            alert(data.warnings.join('\n\n'));
        }
        return true;
    }

    if (res.status === 422) {
        const data = await res.json().catch(function () { return {}; });
        let msg = 'اطلاعات مرحله را بررسی کنید.';
        if (data.errors) {
            msg = Object.values(data.errors).flat().join('\n');
        }
        alert(msg);
        return false;
    }

    let errMsg = 'ذخیرهٔ پیش‌نویس انجام نشد.';
    try {
        const errData = await res.json();
        if (errData.message) {
            errMsg = errData.message;
        }
    } catch (e) {
        //
    }
    alert(errMsg);
    return false;
}

// Step navigation
document.getElementById('nextBtn').addEventListener('click', async function () {
    if (!validateCurrentStep()) {
        return;
    }
    if (currentStep >= totalSteps) {
        return;
    }
    const ok = await persistDraftStep(currentStep);
    if (!ok) {
        return;
    }
    showStep(currentStep + 1);
});

document.getElementById('prevBtn').addEventListener('click', function() {
    if (currentStep > 1) {
        showStep(currentStep - 1);
    }
});

function showStep(step) {
    // Hide all steps
    document.querySelectorAll('.step-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Show current step
    document.getElementById(`step${step}`).classList.add('active');
    
    document.querySelectorAll('.step-pill').forEach((pill) => {
        const n = parseInt(pill.dataset.step, 10);
        pill.classList.toggle('active', n === step);
        pill.classList.toggle('completed', n < step);
    });
    
    // Update buttons
    document.getElementById('prevBtn').style.display = step > 1 ? 'block' : 'none';
    document.getElementById('nextBtn').style.display = step < totalSteps ? 'block' : 'none';
    document.getElementById('submitBtn').style.display = step === totalSteps ? 'block' : 'none';
    
    currentStep = step;

    if (step === 4) {
        const lat = parseFloat(document.getElementById('latitude').value);
        const lng = parseFloat(document.getElementById('longitude').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            setTimeout(function () {
                updateLocationDisplay(lat, lng);
            }, 200);
        }
    }

    if (step === 5) {
        refreshAllPriceWords();
    }

    if (step === 3 && typeof window.initMobileBedrooms === 'function') {
        window.initMobileBedrooms();
    }
}

function validateCurrentStep() {
    const currentStepElement = document.getElementById(`step${currentStep}`);
    const requiredFields = currentStepElement.querySelectorAll('[required]');
    
    for (let field of requiredFields) {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            field.focus();
            return false;
        } else {
            field.classList.remove('is-invalid');
        }
    }
    
    return true;
}

function validateAllSteps() {
    // Check required fields from all steps
    const requiredFields = [
        'name', 'description', 'type', 'main_guest',
        'province_id', 'city_id', 'address',
        'week_price'
    ];
    
    let isValid = true;
    
    for (let fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (field && !field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    }
    
    const documentField = document.getElementById('document');
    if (documentField && documentField.required && (!documentField.files || !documentField.files.length)) {
        documentField.classList.add('is-invalid');
        isValid = false;
    } else if (documentField) {
        documentField.classList.remove('is-invalid');
    }

    if (!isValid) {
        alert('لطفاً تمام فیلدهای اجباری را پر کنید');
    }
    
    return isValid;
}

// Province change handler
document.getElementById('province_id').addEventListener('change', function() {
    const provinceId = this.value;
    const citySelect = document.getElementById('city_id');
    
    citySelect.innerHTML = '<option value="">در حال بارگذاری...</option>';
    
    if (provinceId) {
        fetch(`/api/cities/${provinceId}`)
            .then(response => response.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">انتخاب شهر</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            })
            .catch(() => {
                citySelect.innerHTML = '<option value="">خطا در بارگذاری</option>';
            });
    } else {
        citySelect.innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
    }
});

// Image preview + client-side compression before upload
let selectedFiles = [];
let imageCompressBusy = 0;

const IMAGE_COMPRESS = {
    maxEdge: 1920,
    quality: 0.82,
    skipBelowBytes: 350 * 1024,
};

function isRasterImageFile(file) {
    if (!file) {
        return false;
    }
    if ((file.type || '').startsWith('image/')) {
        return true;
    }
    return /\.(jpe?g|png|gif|webp|bmp)$/i.test(file.name || '');
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
    if (/heic|heif/.test(type) || /\.heic$|\.heif$/.test(name)) {
        return true;
    }
    if (type === 'image/gif' || /\.gif$/i.test(name)) {
        return true;
    }
    return false;
}

function setFileInputFromFile(input, file) {
    if (!input || !file) {
        return;
    }
    try {
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
    } catch (err) {
        // برخی مرورگرهای موبایل اجازهٔ ست کردن files را نمی‌دهند.
    }
}

function formatFileSize(bytes) {
    if (bytes >= 1024 * 1024) {
        return (bytes / 1024 / 1024).toFixed(1) + 'MB';
    }
    return Math.max(1, Math.round(bytes / 1024)) + 'KB';
}

function setImageCompressStatus(message) {
    const el = document.getElementById('imageCompressStatus');
    if (!el) {
        return;
    }
    if (message) {
        el.textContent = message;
        el.style.display = 'block';
    } else {
        el.textContent = '';
        el.style.display = 'none';
    }
}

function beginImageCompress() {
    imageCompressBusy += 1;
    setImageCompressStatus('در حال بهینه‌سازی تصاویر…');
}

function endImageCompress() {
    imageCompressBusy = Math.max(0, imageCompressBusy - 1);
    if (imageCompressBusy === 0) {
        setImageCompressStatus('');
    }
}

function compressImageFile(file) {
    if (shouldSkipClientCompress(file)) {
        return Promise.resolve(file);
    }

    return new Promise(function (resolve) {
        const url = URL.createObjectURL(file);
        const img = new Image();

        img.onload = function () {
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

            canvas.toBlob(function (blob) {
                if (!blob || blob.size >= file.size * 0.95) {
                    resolve(file);
                    return;
                }
                const baseName = (file.name || 'image').replace(/\.[^.]+$/, '');
                resolve(new File([blob], baseName + '.jpg', {
                    type: 'image/jpeg',
                    lastModified: Date.now(),
                }));
            }, 'image/jpeg', IMAGE_COMPRESS.quality);
        };

        img.onerror = function () {
            URL.revokeObjectURL(url);
            resolve(file);
        };

        img.src = url;
    });
}

async function compressImageFileWithUi(file) {
    beginImageCompress();
    try {
        return await compressImageFile(file);
    } finally {
        endImageCompress();
    }
}

function showCoverPreview(file) {
    const wrap = document.getElementById('coverNewPreviewWrap');
    const img = document.getElementById('coverNewPreview');
    if (!file || !wrap || !img) {
        return;
    }
    const reader = new FileReader();
    reader.onload = function (ev) {
        img.src = ev.target.result;
        wrap.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

document.getElementById('cover').addEventListener('change', async function (e) {
    const input = e.target;
    const wrap = document.getElementById('coverNewPreviewWrap');
    const img = document.getElementById('coverNewPreview');
    const file = input.files && input.files[0];

    if (!file || !isRasterImageFile(file)) {
        wrap.style.display = 'none';
        if (img) {
            img.removeAttribute('src');
        }
        return;
    }

    const processed = await compressImageFileWithUi(file);
    if (processed !== file) {
        setFileInputFromFile(input, processed);
    }
    showCoverPreview(processed);
});

async function addGalleryFiles(files) {
    const incoming = Array.from(files).filter(function (file) {
        return isRasterImageFile(file);
    });

    for (let i = 0; i < incoming.length; i++) {
        if (selectedFiles.length >= 10) {
            break;
        }
        const processed = await compressImageFileWithUi(incoming[i]);
        selectedFiles.push(processed);
    }

    updateInputFiles();
    updateImagePreview();
}

document.getElementById('images').addEventListener('change', async function (e) {
    if (!e.target.files || !e.target.files.length) {
        return;
    }
    await addGalleryFiles(e.target.files);
});

function updateInputFiles() {
    const input = document.getElementById('images');
    const dt = new DataTransfer();

    selectedFiles.forEach(file => {
        dt.items.add(file);
    });

    try {
        input.files = dt.files;
    } catch (e) {
        // برخی مرورگرهای موبایل اجازهٔ ست کردن files از DataTransfer را نمی‌دهند؛ پیش‌نمایش همچنان کار می‌کند.
    }
}

function updateImagePreview() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    selectedFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'col-6 col-md-4';
            div.innerHTML = `
                <div class="image-preview">
                    <img src="${e.target.result}" alt="Preview ${index + 1}">
                    <button type="button" class="remove-image" onclick="removeImage(${index})">
                        <i class="bi bi-x"></i>
                    </button>
                    <div class="image-info">
                        <small class="text-muted">${formatFileSize(file.size)}</small>
                    </div>
                </div>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedFiles.splice(index, 1);
    updateInputFiles();
    updateImagePreview();
}

// Drag and drop functionality
const dropZone = document.getElementById('galleryDropZone');
const fileInput = document.getElementById('images');

dropZone.addEventListener('click', function (e) {
    if (e.target !== fileInput) {
        fileInput.click();
    }
});

dropZone.addEventListener('dragover', function (e) {
    e.preventDefault();
    dropZone.style.borderColor = '#007bff';
    dropZone.style.backgroundColor = '#e3f2fd';
});

dropZone.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dropZone.style.borderColor = '#ddd';
    dropZone.style.backgroundColor = '#f8f9fa';
});

dropZone.addEventListener('drop', async function (e) {
    e.preventDefault();
    dropZone.style.borderColor = '#ddd';
    dropZone.style.backgroundColor = '#f8f9fa';

    await addGalleryFiles(e.dataTransfer.files);
});

// Form submission
document.getElementById('createHomeForm').addEventListener('submit', async function(e) {
    const requiredFields = ['name', 'description', 'type', 'main_guest', 'province_id', 'city_id', 'address', 'week_price'];
    let isValid = true;

    for (let fieldId of requiredFields) {
        const field = document.getElementById(fieldId);
        if (field && !field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else if (field) {
            field.classList.remove('is-invalid');
        }
    }

    if (!isValid) {
        e.preventDefault();
        alert('لطفاً تمام فیلدهای اجباری را پر کنید');
        return;
    }

    const docInput = document.getElementById('document');
    if (docInput && docInput.files && docInput.files.length && typeof window.prepareCompressedDocumentInput === 'function') {
        e.preventDefault();
        await window.prepareCompressedDocumentInput('document');
        e.target.submit();
    }
});

// Initialize (ادامه از همان مرحلهٔ ذخیره‌شده)

document.querySelectorAll('.safety-toggle').forEach(function (toggle) {
    toggle.addEventListener('change', function () {
        const target = document.getElementById(this.dataset.target);
        if (target) {
            target.classList.toggle('d-none', !this.checked);
        }
    });
});

document.querySelectorAll('.step-pill').forEach(function (pill) {
    pill.addEventListener('click', function () {
        const step = parseInt(this.dataset.step, 10);
        if (!isNaN(step)) {
            showStep(step);
        }
    });
});

showStep(initialDraftStep);

// مبلغ به حروف فارسی زیر فیلدهای قیمت
const PRICE_WORD_ONES = ['', 'یک', 'دو', 'سه', 'چهار', 'پنج', 'شش', 'هفت', 'هشت', 'نه'];
const PRICE_WORD_TENS = ['', 'ده', 'بیست', 'سی', 'چهل', 'پنجاه', 'شصت', 'هفتاد', 'هشتاد', 'نود'];
const PRICE_WORD_TEENS = ['ده', 'یازده', 'دوازده', 'سیزده', 'چهارده', 'پانزده', 'شانزده', 'هفده', 'هجده', 'نوزده'];
const PRICE_WORD_HUNDREDS = ['', 'یکصد', 'دویست', 'سیصد', 'چهارصد', 'پانصد', 'ششصد', 'هفتصد', 'هشتصد', 'نهصد'];
const PRICE_WORD_SCALES = ['', 'هزار', 'میلیون', 'میلیارد', 'تریلیون'];

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
    if (value === 0) {
        return 'صفر تومان';
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
        updatePriceWords(input.id);
    });
}

function normalizePriceFieldInput(input) {
    if (!input) {
        return;
    }
    const value = parsePriceFieldValue(input);
    input.value = value > 0 ? String(value) : '';
}

document.querySelectorAll('.price-field').forEach(function (input) {
    input.addEventListener('focus', function () {
        if (input.value === '0') {
            input.value = '';
            updatePriceWords(input.id);
        }
    });

    input.addEventListener('input', function () {
        normalizePriceFieldInput(input);
        updatePriceWords(input.id);
    });

    input.addEventListener('change', function () {
        normalizePriceFieldInput(input);
        if (input.value === '0') {
            input.value = '';
        }
        updatePriceWords(input.id);
    });
});

refreshAllPriceWords();

// Map functionality
let map;
let marker;
let previewMap = null;
let previewMarker = null;
let selectedLat = null;
let selectedLng = null;

// Initialize map when modal is shown
document.getElementById('mapSelectionModal').addEventListener('shown.bs.modal', function() {
    setTimeout(() => {
        if (!map) {
            // Initialize map centered on Iran
            map = L.map('mapSelection').setView([32.4279, 53.6880], 6);
            
            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
            
            // Add click event to map
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;
                
                // Remove existing marker
                if (marker) {
                    map.removeLayer(marker);
                }
                
                // Add new marker
                marker = L.marker([lat, lng]).addTo(map);
                
                // Update selected coordinates
                selectedLat = lat;
                selectedLng = lng;
                
                // Update coordinates display
                document.getElementById('latitude').value = lat.toFixed(6);
                document.getElementById('longitude').value = lng.toFixed(6);
                
            });
            
            // If coordinates already exist, show them
            const existingLat = document.getElementById('latitude').value;
            const existingLng = document.getElementById('longitude').value;
            
            if (existingLat && existingLng) {
                const lat = parseFloat(existingLat);
                const lng = parseFloat(existingLng);
                
                map.setView([lat, lng], 15);
                marker = L.marker([lat, lng]).addTo(map);
                selectedLat = lat;
                selectedLng = lng;
                updateLocationDisplay(lat, lng);
            }
        }
    }, 100);
});

// Confirm location button
document.getElementById('confirmLocation').addEventListener('click', function() {
    if (selectedLat && selectedLng) {
        // Update form fields
        document.getElementById('latitude').value = selectedLat.toFixed(6);
        document.getElementById('longitude').value = selectedLng.toFixed(6);
        
        // Update location display
        updateLocationDisplay(selectedLat, selectedLng);
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('mapSelectionModal'));
        modal.hide();
    } else {
        alert('لطفاً روی نقشه کلیک کنید تا موقعیت را انتخاب کنید');
    }
});

function renderLocationPreviewMap(lat, lng) {
    const wrap = document.getElementById('locationPreviewWrap');
    const container = document.getElementById('locationPreviewMap');
    if (!wrap || !container) {
        return;
    }

    wrap.style.display = 'block';

    if (!previewMap) {
        previewMap = L.map(container, {
            zoomControl: true,
            attributionControl: true
        }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
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

// Function to update location display
function updateLocationDisplay(lat, lng) {
    const wrap = document.getElementById('locationPreviewWrap');
    const locationText = document.getElementById('locationText');

    if (locationText) {
        locationText.textContent = 'عرض: ' + lat.toFixed(6) + ' — طول: ' + lng.toFixed(6);
    }
    if (wrap) {
        wrap.style.display = 'block';
    }

    if (currentStep === 2) {
        renderLocationPreviewMap(lat, lng);
    }

    getAddressFromCoordinates(lat, lng);
}

// Reverse geocoding function (optional)
function getAddressFromCoordinates(lat, lng) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&accept-language=fa`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                const locationText = document.getElementById('locationText');
                const lat = parseFloat(document.getElementById('latitude').value);
                const lng = parseFloat(document.getElementById('longitude').value);
                const coords = (!isNaN(lat) && !isNaN(lng))
                    ? ('عرض: ' + lat.toFixed(6) + ' — طول: ' + lng.toFixed(6) + ' · ')
                    : '';
                locationText.textContent = coords + data.display_name;
            }
        })
        .catch(error => {
            console.log('Error getting address:', error);
        });
}
</script>
@endsection