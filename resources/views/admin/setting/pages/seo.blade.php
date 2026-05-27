<form action="{{ route('admin.setting.seo') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('admin.partials.seo-performance-guide')
    @include('admin.partials.seo-measurement-guide')

    <div class="alert alert-info">
        <h5 class="alert-heading mb-2">راهنمای مدیر سایت — سئو گوگل</h5>
        <p class="mb-2 small">این بخش متا تگ‌های <strong>title</strong>، <strong>description</strong>، <strong>canonical</strong> و <strong>robots</strong> را برای صفحات عمومی سایت تنظیم می‌کند.</p>
        <ol class="mb-0 small ps-3">
            <li class="mb-1"><strong>عنوان صفحه (Title):</strong> متنی که در تب مرورگر و نتیجه جستجوی گوگل دیده می‌شود. فرمت خودکار: <code>عنوان صفحه | {{ config('app.name') }}</code> — بخش اصلی را کوتاه و با کلمه کلیدی بنویسید (حداکثر حدود ۴۵ کاراکتر قبل از نام برند).</li>
            <li class="mb-1"><strong>توضیح متا (Description):</strong> زیر عنوان در گوگل نمایش داده می‌شود؛ حداکثر <strong>۱۵۰ کاراکتر</strong>.</li>
            <li class="mb-1"><strong>صفحه اصلی:</strong> نام برند + خدمت اصلی، مثلاً «اجاره ویلا و اقامتگاه در شمال — رزرو آنلاین».</li>
            <li class="mb-1"><strong>لیست اقامتگاه‌ها:</strong> عبارت جستجو مانند «جستجو و رزرو ویلا و سوئیت».</li>
            <li class="mb-1"><strong>اقامتگاه‌ها:</strong> عنوان هر صفحه به‌صورت خودکار از نام، نوع و شهر ساخته می‌شود (مثلاً «اجاره ویلا فلان در رامسر»). در ویرایش اقامتگاه نام دقیق و شهر را درست وارد کنید.</li>
            <li class="mb-1"><strong>مقالات:</strong> عنوان مقاله همان تگ title است؛ عنوان جذاب و مرتبط با موضوع بنویسید.</li>
            <li class="mb-1"><strong>تأیید گوگل:</strong> کد <code>google-site-verification</code> را از <a href="https://search.google.com/search-console" target="_blank" rel="noopener">Google Search Console</a> کپی کنید.</li>
            <li class="mb-0"><strong>تصویر اشتراک:</strong> برای صفحاتی که عکس اختصاصی ندارند؛ در غیر این صورت از لوگو استفاده می‌شود.</li>
        </ol>
    </div>

    <div class="alert alert-warning small">
        صفحات ورود، ثبت‌نام، داشبورد کاربر و پنل مدیریت به‌صورت خودکار با <code>noindex</code> از ایندکس گوگل خارج شده‌اند.
    </div>

    <h4 class="text-center mt-4">تنظیمات عمومی سئو</h4>

    <div class="row mt-3">
        <div class="col-12 col-md-4">
            <label for="default_description">توضیح متا پیش‌فرض (حداکثر ۱۵۰ کاراکتر)</label>
        </div>
        <div class="col-12 col-md-8">
            <textarea class="form-control js-seo-meta-description" name="default_description" id="default_description" rows="3"
                      maxlength="150" data-seo-max="150">{{ setting('seo:default-description') }}</textarea>
            <small class="seo-char-counter text-muted d-block mt-1" data-for="default_description" aria-live="polite"></small>
            <small class="text-muted">در صورت خالی بودن توضیح صفحات، این متن نمایش داده می‌شود.</small>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-4">
            <label for="ga4_measurement_id">شناسه اندازه‌گیری Google Analytics 4</label>
        </div>
        <div class="col-12 col-md-8">
            <input class="form-control font-monospace" type="text" name="ga4_measurement_id" id="ga4_measurement_id"
                   value="{{ setting('seo:ga4-measurement-id') }}"
                   placeholder="مثال: G-XXXXXXXXXX"
                   pattern="^G-[A-Z0-9]+$"
                   title="فرمت صحیح: G- و سپس حروف و اعداد انگلیسی">
            <small class="text-muted d-block mt-1">
                از Google Analytics → Admin → Data Streams → Web → Measurement ID. خالی = بدون ردیابی در سایت.
            </small>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-4">
            <label for="google_site_verification">کد تأیید Google Search Console</label>
        </div>
        <div class="col-12 col-md-8">
            <input class="form-control" type="text" name="google_site_verification" id="google_site_verification"
                   value="{{ setting('seo:google-site-verification') }}"
                   placeholder="مثال: abc123XYZ">
            <small class="text-muted d-block mt-1">
                فقط متا تأیید مالکیت؛ گزارش ایندکس و کوئری را در Search Console ببینید.
            </small>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-md-4">
            <label for="default_og_image">تصویر پیش‌فرض Open Graph</label>
        </div>
        <div class="col-12 col-md-8">
            <input class="form-control" type="file" name="default_og_image" id="default_og_image" accept="image/*">
            @if(setting('seo:default-og-image'))
                <img src="{{ settingFilePath('seo:default-og-image') }}" alt="" class="img-thumbnail mt-2" style="max-height:120px">
            @endif
        </div>
    </div>

    <hr>
    <h4 class="text-center">عنوان صفحات (تگ Title)</h4>
    <p class="text-center text-muted small mb-0">
        پیش‌نمایش در گوگل: <strong>بخش اصلی</strong> | <strong>{{ config('app.name') }}</strong> — حداکثر ۶۰ کاراکتر برای کل عنوان.
    </p>

    @php
        $titleFields = [
            'index_title' => [
                'key' => 'seo:index-title',
                'label' => 'صفحه اصلی',
                'placeholder' => 'اجاره ویلا و اقامتگاه — رزرو آنلاین',
                'hint' => 'اگر خالی باشد از «عنوان صفحه» در تنظیمات صفحه اصلی استفاده می‌شود.',
                'fallback' => setting('index:page-title'),
            ],
            'homes_title' => [
                'key' => 'seo:homes-title',
                'label' => 'لیست اقامتگاه‌ها',
                'placeholder' => 'جستجو و رزرو ویلا و سوئیت',
                'hint' => 'اگر خالی باشد: «اقامتگاه‌ها».',
                'fallback' => __('title.homes'),
            ],
            'articles_title' => [
                'key' => 'seo:articles-title',
                'label' => 'لیست مقالات',
                'placeholder' => 'مجله و راهنمای سفر',
                'hint' => 'اگر خالی باشد: «وبلاگ».',
                'fallback' => __('title.blog'),
            ],
            'contact_title' => [
                'key' => 'seo:contact-title',
                'label' => 'تماس با ما',
                'placeholder' => 'تماس با پشتیبانی',
                'hint' => 'اختیاری — از عنوان صفحه تماس.',
                'fallback' => setting('contact-us:title'),
            ],
            'about_title' => [
                'key' => 'seo:about-title',
                'label' => 'درباره ما',
                'placeholder' => 'درباره ما',
                'hint' => 'اختیاری — از عنوان صفحه درباره ما.',
                'fallback' => setting('about-us:page-title'),
            ],
            'privacy_title' => [
                'key' => 'seo:privacy-title',
                'label' => 'قوانین و حریم خصوصی',
                'placeholder' => 'قوانین و مقررات',
                'hint' => 'اختیاری.',
                'fallback' => setting('privacy:title'),
            ],
            'faq_title' => [
                'key' => 'seo:faq-title',
                'label' => 'سوالات متداول',
                'placeholder' => 'سوالات متداول',
                'hint' => 'اختیاری.',
                'fallback' => setting('faq:title'),
            ],
            'submit_home_title' => [
                'key' => 'seo:submit-home-title',
                'label' => 'ثبت ملک',
                'placeholder' => 'ثبت اقامتگاه شما',
                'hint' => 'اختیاری.',
                'fallback' => setting('submit-home:page-title'),
            ],
        ];
        $brandName = config('app.name');
        $titleMaxSegment = 45;
    @endphp

    @foreach($titleFields as $name => $field)
        <div class="row mt-3">
            <div class="col-12 col-md-4">
                <label for="{{ $name }}">{{ $field['label'] }}</label>
            </div>
            <div class="col-12 col-md-8">
                <input class="form-control js-seo-page-title" name="{{ $name }}" id="{{ $name }}" type="text"
                       value="{{ setting($field['key']) }}"
                       maxlength="{{ $titleMaxSegment }}" data-seo-max="{{ $titleMaxSegment }}"
                       data-brand="{{ $brandName }}"
                       placeholder="{{ $field['placeholder'] }}">
                <small class="seo-char-counter text-muted d-block mt-1" data-for="{{ $name }}" aria-live="polite"></small>
                <small class="seo-title-preview text-muted d-block mt-1" data-for="{{ $name }}" aria-live="polite"></small>
                @if($field['hint'])
                    <small class="text-muted d-block">{{ $field['hint'] }}</small>
                @endif
                @if($field['fallback'])
                    <small class="text-muted d-block">مقدار فعلی (در صورت خالی بودن این فیلد): {{ $field['fallback'] }}</small>
                @endif
            </div>
        </div>
    @endforeach

    <hr>
    <h4 class="text-center">توضیح متا صفحات</h4>

    @php
        $pageFields = [
            'index_meta_description' => ['key' => 'seo:index-meta-description', 'label' => 'صفحه اصلی', 'hint' => 'اگر خالی باشد از توضیح بنر صفحه اصلی استفاده می‌شود.'],
            'homes_meta_description' => ['key' => 'seo:homes-meta-description', 'label' => 'لیست اقامتگاه‌ها', 'hint' => ''],
            'articles_meta_description' => ['key' => 'seo:articles-meta-description', 'label' => 'لیست مقالات', 'hint' => ''],
            'contact_meta_description' => ['key' => 'seo:contact-meta-description', 'label' => 'تماس با ما', 'hint' => 'اختیاری — از متن صفحه تماس.'],
            'about_meta_description' => ['key' => 'seo:about-meta-description', 'label' => 'درباره ما', 'hint' => 'اختیاری — از داستان درباره ما.'],
            'privacy_meta_description' => ['key' => 'seo:privacy-meta-description', 'label' => 'قوانین و حریم خصوصی', 'hint' => 'اختیاری.'],
            'faq_meta_description' => ['key' => 'seo:faq-meta-description', 'label' => 'سوالات متداول', 'hint' => 'اختیاری.'],
            'submit_home_meta_description' => ['key' => 'seo:submit-home-meta-description', 'label' => 'ثبت ملک', 'hint' => 'اختیاری.'],
        ];
    @endphp

    @foreach($pageFields as $name => $field)
        <div class="row mt-3">
            <div class="col-12 col-md-4">
                <label for="{{ $name }}">{{ $field['label'] }}</label>
            </div>
            <div class="col-12 col-md-8">
                <textarea class="form-control js-seo-meta-description" name="{{ $name }}" id="{{ $name }}" rows="2"
                          maxlength="150" data-seo-max="150">{{ setting($field['key']) }}</textarea>
                <small class="seo-char-counter text-muted d-block mt-1" data-for="{{ $name }}" aria-live="polite"></small>
                @if($field['hint'])
                    <small class="text-muted d-block">{{ $field['hint'] }}</small>
                @endif
            </div>
        </div>
    @endforeach

    <div class="row mt-4">
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary">@lang('title.submit')</button>
        </div>
    </div>
</form>

@once
    @push('bottom-assets')
        <script>
            (function () {
                var DESC_MAX = 150;
                var TITLE_MAX = 45;
                var DOC_TITLE_MAX = 60;

                function countChars(text) {
                    return Array.from(text || '').length;
                }

                function updateCounter(textareaOrInput) {
                    var max = parseInt(textareaOrInput.getAttribute('data-seo-max') || textareaOrInput.getAttribute('maxlength') || DESC_MAX, 10);
                    var used = countChars(textareaOrInput.value);
                    var counter = document.querySelector('.seo-char-counter[data-for="' + textareaOrInput.id + '"]');
                    if (!counter) {
                        return;
                    }

                    counter.textContent = used + ' از ' + max + ' کاراکتر استفاده شده';
                    counter.classList.remove('text-muted', 'text-warning', 'text-danger', 'fw-semibold');
                    if (used >= max) {
                        counter.classList.add('text-danger', 'fw-semibold');
                    } else if (used >= max - 10) {
                        counter.classList.add('text-warning', 'fw-semibold');
                    } else {
                        counter.classList.add('text-muted');
                    }
                }

                function updateTitlePreview(input) {
                    var preview = document.querySelector('.seo-title-preview[data-for="' + input.id + '"]');
                    if (!preview) {
                        return;
                    }

                    var brand = input.getAttribute('data-brand') || '';
                    var segment = (input.value || '').trim();
                    var full = segment ? (segment + ' | ' + brand) : brand;
                    var total = countChars(full);

                    preview.textContent = 'پیش‌نمایش: ' + (full || '—');
                    preview.classList.remove('text-muted', 'text-warning', 'text-danger');
                    if (total > DOC_TITLE_MAX) {
                        preview.classList.add('text-danger');
                        preview.textContent += ' — کل عنوان ' + total + ' کاراکتر است (بیش از ' + DOC_TITLE_MAX + '؛ در سایت کوتاه می‌شود)';
                    } else if (total > DOC_TITLE_MAX - 8) {
                        preview.classList.add('text-warning');
                    } else {
                        preview.classList.add('text-muted');
                    }
                }

                function bindField(field) {
                    updateCounter(field);
                    if (field.classList.contains('js-seo-page-title')) {
                        updateTitlePreview(field);
                    }
                    field.addEventListener('input', function () {
                        updateCounter(field);
                        if (field.classList.contains('js-seo-page-title')) {
                            updateTitlePreview(field);
                        }
                    });
                }

                document.querySelectorAll('.js-seo-meta-description, .js-seo-page-title').forEach(bindField);
            })();
        </script>
    @endpush
@endonce
