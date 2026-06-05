<form action="{{ route('admin.setting.general') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @can('logo', \App\Models\Setting::class)
        <h4 class="text-center">@lang('title.logo')</h4>
        <p class="text-muted text-center small mb-3">فقط فایل PNG با پس‌زمینه شفاف (از InShot: Save as PNG — نه JPG)</p>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="logo">@lang('title.logo')</label>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <input class="form-control" type="file" name="logo" id="logo">
                    <a href="{{ settingFilePath('app:logo') }}" target="_blank" class="input-group-text">
                        <img width="200" src="{{ settingFilePath('app:logo') }}" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="logo_light">@lang('title.logo_light')</label>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <input class="form-control" type="file" name="logo_light" id="logo_light">
                    <a href="{{ settingFilePath('app:logo-light') }}" target="_blank" class="input-group-text">
                        <img width="200" src="{{ settingFilePath('app:logo-light') }}" alt="">
                    </a>
                </div>
            </div>
        </div>

        <hr>
        <h4 class="text-center">@lang('title.site_identity')</h4>
        <div class="alert alert-secondary small">
            <strong>تیتر تب مرورگر:</strong> در همه صفحات به‌صورت <code>عنوان صفحه | نام سایت</code> نمایش داده می‌شود.
            «نام سایت» را اینجا تنظیم کنید؛ «عنوان صفحه» هر بخش در <em>سئو</em> یا تنظیمات همان صفحه (مثلاً صفحه اصلی) است.
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="site_name">@lang('title.site_name')</label>
            </div>
            <div class="col-12 col-md-6">
                <input class="form-control" type="text" name="site_name" id="site_name"
                       value="{{ old('site_name', setting('app:site-name', config('app.name'))) }}"
                       placeholder="مثال: vilafarda">
                <small class="text-muted d-block mt-1">بخش ثابت سمت راست تیتر تب (بعد از |). خالی = مقدار APP_NAME در سرور.</small>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="favicon">@lang('title.favicon')</label>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <input class="form-control" type="file" name="favicon" id="favicon" accept="image/png,image/x-icon,.ico">
                    @if(settingFilePath('app:favicon'))
                        <span class="input-group-text p-2">
                            <img width="32" height="32" src="{{ settingFilePath('app:favicon') }}" alt="" style="object-fit: contain;">
                        </span>
                    @endif
                </div>
                <small class="text-muted d-block mt-2">
                    <strong>برای نمایش دایره‌ای در تب مرورگر و گوگل:</strong>
                    فایل PNG با <strong>پس‌زمینه شفاف</strong> (نه مربع تیره/سفید) آپلود کنید.
                    لوگو باید بزرگ باشد و بیشتر فضای تصویر را پر کند.
                    پس از آپلود، نسخه‌های گرد ۴۸، ۱۹۲ و ۵۱۲ پیکسل ساخته می‌شود.
                    اگر آیکون قدیمی است: <code>php artisan favicon:reprocess</code> — سپس کش مرورگر را پاک کنید (Ctrl+Shift+R).
                </small>
            </div>
        </div>
    @endcan

    @can('appModalAuth', \App\Models\Setting::class)
        <hr>

        <h4 class="text-center">@lang('title.auth_modal')</h4>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="auth_modal_status">@lang('title.status')</label>
            </div>
            <div class="col-12 col-md-6">
                <label for="auth_modal_status">@lang('title.active')</label>
                <input type="checkbox" @if(setting('app:auth-modal-active')) checked @endif class="form-control-check" id="auth_modal_status" name="auth_modal_status">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="auth_banner">@lang('title.banner')</label>
            </div>
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <input class="form-control" type="file" name="auth_banner" id="auth_banner">
                    <a href="{{ settingFilePath('app:auth-modal-img') }}" target="_blank" class="input-group-text">
                        <img width="200" src="{{ settingFilePath('app:auth-modal-img') }}" alt="auth_banner">
                    </a>
                </div>
            </div>
        </div>
    @endcan

    @can('appContact', \App\Models\Setting::class)
        <hr>

        <h4 class="text-center">@lang('title.contact')</h4>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="contact_title">@lang('title.title')</label>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" value="{{ old('contact_title', setting('app:contact-title')) }}" class="form-control" id="contact_title" name="contact_title">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="contact_description">@lang('title.description')</label>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" value="{{ old('contact_description', setting('app:contact-description')) }}" class="form-control" id="contact_description" name="contact_description">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="contact_btn_text">@lang('title.btn_title')</label>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" value="{{ old('contact_btn_text', setting('app:contact-btn-text')) }}" class="form-control" id="contact_btn_text" name="contact_btn_text">
            </div>
        </div>
    @endcan

    @can('appNewsletter', \App\Models\Setting::class)
        <hr>

        <h4 class="text-center">@lang('title.newsletter')</h4>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="newsletter_title">@lang('title.title')</label>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" value="{{ old('newsletter_title', setting('app:newsletter-title')) }}" class="form-control" id="newsletter_title" name="newsletter_title">
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <label for="newsletter_description">@lang('title.description')</label>
            </div>
            <div class="col-12 col-md-6">
                <input type="text" value="{{ old('newsletter_description', setting('app:newsletter-description')) }}" class="form-control" id="newsletter_description" name="newsletter_description">
            </div>
        </div>
    @endcan

    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
