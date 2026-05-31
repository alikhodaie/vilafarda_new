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
