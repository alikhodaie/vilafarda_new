@include('admin.partials.home-edit.help', ['text' => '<strong>مدیریت اقامتگاه:</strong> مالک، وضعیت انتشار، کد اختصاصی، امتیاز نمایشی و شبا را از این بخش کنترل کنید. تغییر وضعیت بلافاصله روی نمایش اقامتگاه در سایت اثر می‌گذارد.'])

@include('admin.partials.home-edit.help', ['text' => '
<strong>آدرس سئو (اسلاگ):</strong>
<ul class="mb-0 ps-3">
    <li>آدرس عمومی اقامتگاه به‌صورت <code>/homes/r/اسلاگ-شناسه</code> است؛ مثال: <code>/homes/r/اجاره-ویلایی-در-رامسر-123</code></li>
    <li>اسلاگ می‌تواند فارسی یا لاتین باشد؛ کلمات را با <strong>خط تیره (-)</strong> جدا کنید، نه فاصله (مثل: اجاره-ویلایی-در-رامسر).</li>
    <li>اگر فیلد اسلاگ را خالی بگذارید، هنگام ذخیره به‌صورت خودکار از نوع و شهر ساخته می‌شود.</li>
    <li>لینک‌های قدیمی <code>/homes/123</code> همچنان کار می‌کنند؛ نیازی به تغییر لینک‌های منتشرشده در شبکه‌های اجتماعی نیست.</li>
    <li>پس از تغییر اسلاگ، آدرس جدید در سایت و sitemap استفاده می‌شود؛ canonical در متا تگ به آدرس جدید اشاره می‌کند.</li>
</ul>
'])

<div class="row">
    <div class="col-12 mb-3">
        <label for="home_seo_slug">آدرس سئو (اسلاگ)</label>
        <input type="text" class="form-control" name="slug" id="home_seo_slug" maxlength="200"
               dir="auto"
               value="{{ old('slug', $home->slug) }}"
               placeholder="{{ $home->suggestSlug() }}">
        @error('slug')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
        <p class="text-muted small mb-1 mt-1">
            پیشنهاد خودکار: <span class="text-dark">{{ $home->suggestSlug() }}</span>
        </p>
        <p class="text-muted small mb-0">
            آدرس نمایشی:
            <a href="{{ $home->public_show_url }}" target="_blank" rel="noopener" class="text-break">
                {{ $home->public_show_url }}
            </a>
        </p>
    </div>
    <div class="col-12 mb-3">
        <label for="user">@lang('title.user')</label>
        <select name="user" id="user" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach($usersForSelect as $selectUser)
                @php($label = trim($selectUser->full_name))
                <option value="{{ $selectUser->id }}"
                        {{ old('user', $home->user_id) == $selectUser->id ? 'selected' : '' }}>{{ $label !== '' ? $label : 'کاربر #'.$selectUser->id }}</option>
            @endforeach
        </select>
        <p class="text-muted small mb-0 mt-1">میزبان اقامتگاه؛ رزروها و تسویه‌ها به این حساب متصل است.</p>
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="code">@lang('title.code')</label>
        <input type="text" class="form-control" name="code" id="code" maxlength="50"
               value="{{ old('code', $home->code) }}">
        <p class="text-muted small mb-0 mt-1">کد یکتا برای جستجو و پشتیبانی داخلی.</p>
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="status">@lang('title.status')</label>
        <select name="status" id="status" class="form-control" required>
            <option value="">@lang('title.select')</option>
            @foreach(\App\Models\Home::STATUSES as $status)
                <option value="{{ $status['value'] }}"
                        {{ $status['value'] == old('status', $home->status) ? 'selected' : '' }}>{{ $status['fa_text'] }}</option>
            @endforeach
        </select>
        <p class="text-muted small mb-0 mt-1">مثلاً فعال، در انتظار تأیید، رد شده — وضعیت نمایش در سایت.</p>
    </div>

    <div class="col-12 col-md-4 mb-3">
        <label for="shaba">@lang('title.shaba')</label>
        <input type="text" class="form-control" name="shaba" value="{{ old('shaba', $home->shaba) }}">
        <p class="text-muted small mb-0 mt-1">شماره شبا ۲۴ رقمی برای واریز درآمد میزبان (اختیاری).</p>
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label>@lang('title.main_score')</label>
        <input type="number" class="form-control" disabled value="{{ $home->score }}">
        <p class="text-muted small mb-0 mt-1">امتیاز واقعی از نظرات مهمانان — فقط خواندنی.</p>
    </div>

    <div class="col-12 col-md-6 mb-3">
        <label for="score">@lang('title.fake_score')</label>
        <input type="number" class="form-control" min="1" max="5" id="score" name="score"
               value="{{ old('score', $home->fake_score) }}">
        <p class="text-muted small mb-0 mt-1">امتیاز نمایشی در کارت اقامتگاه (۱ تا ۵).</p>
    </div>

    <div class="col-12">
        <x-dashboard.home.variables class="mb-3" :values="$home->variables"></x-dashboard.home.variables>
        <p class="text-muted small mb-0">فیلدهای سفارشی تعریف‌شده در پنل (متغیرها).</p>
    </div>
</div>
