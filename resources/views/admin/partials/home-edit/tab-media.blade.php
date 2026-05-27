@include('admin.partials.home-edit.help', ['text' => '<strong>رسانه:</strong> کاور، گالری، ویدیو و مدارک. تصاویر جدید با ذخیرهٔ فرم آپلود می‌شوند؛ برای حذف تصاویر فعلی می‌توانید از چک‌باکس «حذف» یا ابزار حذف دسته‌ای استفاده کنید.'])
@include('admin.partials.seo-performance-guide')

<div class="card border-primary mb-4">
    <div class="card-header bg-primary-subtle py-2">
        <strong class="small"><span class="fas fa-universal-access ms-1"></span> متن alt تصاویر (سئو و دسترسی‌پذیری)</strong>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            این متن در attribute <code>alt</code> تصاویر سایت نمایش داده می‌شود. اگر خالی بگذارید، از نام اقامتگاه استفاده می‌شود
            (پیش‌فرض: <em>{{ homeImageAltDefault($home) }}</em>).
        </p>
        <div class="mb-0">
            <label for="cover_alt" class="form-label fw-semibold">متن alt کاور و کارت‌های لیست</label>
            <input type="text"
                   class="form-control"
                   name="cover_alt"
                   id="cover_alt"
                   maxlength="255"
                   value="{{ old('cover_alt', $home->cover_alt) }}"
                   placeholder="{{ homeImageAltDefault($home) }}">
            <p class="text-muted small mb-0 mt-1">مثال: «نمای بیرونی ویلا دو خوابه در رامسر» — برای همهٔ تصاویر کاور در لیست و صفحهٔ اقامتگاه (مگر برای هر عکس گالری جداگانه alt بگذارید).</p>
        </div>
    </div>
</div>

@if(!$home->images->isEmpty())
    <div class="border rounded-3 px-3 pt-3 pb-2 bg-white mb-4">
        <div class="fw-semibold small text-secondary mb-2">حذف دسته‌ای تصاویر گالری (بدون ذخیرهٔ فرم اصلی)</div>
        <div class="d-flex flex-wrap align-items-center gap-3 p-3 rounded-3 bg-light border mb-3">
            <div class="form-check mb-0">
                <input type="checkbox" class="form-check-input" id="admin-gallery-select-all">
                <label class="form-check-label small fw-semibold" for="admin-gallery-select-all">انتخاب همه</label>
            </div>
            <button type="submit" form="form-bulk-delete-images" class="btn btn-danger btn-sm rounded-pill px-4"
                    id="admin-gallery-bulk-delete-btn" disabled
                    onclick="return confirm('تصاویر انتخاب‌شده برای همیشه حذف شوند؟');">
                <span class="fas fa-trash-alt ms-1"></span> حذف انتخاب‌شده‌ها
            </button>
            <span class="text-muted small" id="admin-gallery-selected-hint"></span>
        </div>
        <div class="row g-3 pb-3">
            @foreach($home->images as $image)
                <div class="col-6 col-md-4 col-lg-3">
                    <label class="admin-gallery-tile position-relative rounded-3 overflow-hidden border mb-0 d-block cursor-pointer bg-white shadow-sm">
                        <input type="checkbox" name="ids[]" value="{{ $image->id }}" form="form-bulk-delete-images"
                               class="form-check-input position-absolute top-0 start-0 m-2 admin-gallery-check"
                               style="width: 1.15rem; height: 1.15rem; z-index: 2;">
                        <img src="{{ $image->image_path }}" alt="{{ $image->original_name }}"
                             class="w-100 d-block" style="height: 140px; object-fit: cover;">
                        <div class="small text-truncate px-2 py-2 border-top bg-light text-secondary"
                             title="{{ $image->original_name }}">{{ $image->original_name }}</div>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="mb-4">
    <label class="form-label fw-semibold mb-2">@lang('title.cover')</label>
    @if($home->cover)
        <div class="mb-3 rounded-3 overflow-hidden border shadow-sm d-inline-block" id="cover-current-thumb">
            <img src="{{ $home->cover_path }}" alt="{{ $home->name }}"
                 style="max-height: 200px; max-width: 100%; display: block;">
        </div>
    @endif
    <div id="cover-preview-wrap" class="mb-3 rounded-3 overflow-hidden border shadow-sm d-none bg-light" style="max-width: 320px;">
        <img src="" alt="" id="cover-preview-img" class="w-100 d-block" style="max-height: 220px; object-fit: contain;">
        <div id="cover-preview-caption" class="small text-center py-1 bg-white border-top text-muted">پیش‌نمایش کاور جدید</div>
    </div>
    <div class="rounded-3 border border-2 border-dashed bg-light p-4 text-center admin-upload-dropzone">
        <label for="cover" class="mb-0 cursor-pointer d-block">
            <span class="fas fa-image fa-2x text-secondary mb-2 d-block"></span>
            <span class="d-block text-secondary small mb-2">کاور اصلی اقامتگاه — روی سرور به WebP با کیفیت بالا ذخیره می‌شود. این تصویر اولین عکس صفحهٔ اقامتگاه و کارت‌های لیست است؛ افقی، روشن و بدون واترمارک انتخاب کنید.</span>
            <span class="btn btn-sm btn-primary px-4 rounded-pill">انتخاب تصویر کاور</span>
        </label>
        <input type="file" name="cover" id="cover" class="d-none" accept="image/jpeg,image/png,image/webp,image/heic,image/heif,.heic,.heif">
    </div>
    <p class="text-muted small mb-0 mt-2" id="cover-file-name"></p>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold mb-2">گالری تصاویر</label>
    @if(!$home->images->isEmpty())
        <p class="text-muted small mb-2">برای حذف همراه با ذخیرهٔ فرم، گزینهٔ «حذف» را بزنید:</p>
        <div class="row g-3 mb-3">
            @foreach($home->images as $image)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="admin-gallery-tile rounded-3 overflow-hidden border bg-white shadow-sm position-relative h-100">
                        <img src="{{ $image->image_path }}" alt="{{ $image->alt_text ?: $image->original_name }}"
                             class="w-100 d-block" style="height: 120px; object-fit: cover;">
                        <div class="p-2 border-top bg-light">
                            <label class="form-label small fw-semibold mb-1" for="image_alt_{{ $image->id }}">متن alt این تصویر</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   name="image_alts[{{ $image->id }}]"
                                   id="image_alt_{{ $image->id }}"
                                   maxlength="255"
                                   value="{{ old('image_alts.'.$image->id, $image->alt_text) }}"
                                   placeholder="{{ homeImageAltDefault($home) }}">
                            <label class="d-flex align-items-center gap-2 small mb-0 mt-2 cursor-pointer text-danger">
                                <input type="checkbox" name="delete_existing_images[]" value="{{ $image->id }}" class="form-check-input m-0">
                                <span>حذف پس از ذخیره</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted small mb-3">هنوز تصویری در گالری نیست.</p>
    @endif

    <div class="rounded-3 border border-2 border-dashed bg-white p-4 text-center admin-upload-dropzone">
        <label for="gallery" class="mb-0 cursor-pointer d-block">
            <span class="fas fa-images fa-2x text-primary mb-2 d-block opacity-75"></span>
            <span class="d-block text-secondary small mb-2">افزودن تصاویر گالری — با ذخیرهٔ فرم آپلود می‌شود (حداکثر ۳۰ تصویر). بعد از آپلود، برای هر تصویر فیلد «متن alt» در بالا ظاهر می‌شود.</span>
            <span class="btn btn-sm btn-outline-primary px-4 rounded-pill">انتخاب تصاویر گالری</span>
        </label>
        <input type="file" name="gallery[]" id="gallery" class="d-none" accept="image/*" multiple
               data-max-batch="{{ max(1, (int) ini_get('max_file_uploads')) }}">
    </div>
    <div id="gallery-batch-warning" class="alert alert-warning py-2 small mt-2 mb-0 d-none" role="status"></div>
    <div id="gallery-files-preview" class="row g-2 mt-3"></div>
    @error('gallery')
        <div class="alert alert-danger py-2 small mt-2 mb-0">{{ $message }}</div>
    @enderror
</div>

<div class="col-12 mb-4">
    <label for="video">@lang('title.video')</label>
    <div class="input-group flex-column flex-md-row gap-2">
        <input class="form-control" type="file" name="video" id="video">
        @if($home->video)
            <video class="w-100 rounded border" style="max-height: 240px;" controls>
                <source src="{{ $home->video_path }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @endif
    </div>
    <p class="text-muted small mb-0 mt-1">ویدیوی معرفی اقامتگاه (اختیاری).</p>
</div>

<x-admin.home-documents-list :home="$home" />

<div class="mb-3">
    <label for="document">
        @if(($home->relationLoaded('documents') && $home->documents->isNotEmpty()) || $home->document)
            افزودن مدرک دیگر
        @else
            @lang('title.document')
        @endif
    </label>
    <input type="file" class="form-control" name="document" id="document" accept="image/*,.pdf,application/pdf,.heic,.heif">
    <p class="text-muted small mb-0 mt-1">هر بار ذخیره، مدرک جدید به لیست اضافه می‌شود؛ مدارک قبلی حذف نمی‌شوند.</p>
</div>
