@php use App\Models\Province; @endphp
<form id="index-page-settings-form" action="{{ route('admin.setting.index-page') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <h4 class="text-center">@lang('title.main_title')</h4>
    <div class="alert alert-secondary small">
        توضیح متا (description) صفحه اصلی برای گوگل در بخش <strong>سئو (متا تگ‌ها)</strong> در همین صفحه تنظیمات قابل ویرایش است. عنوان زیر در تب مرورگر و عنوان سئو استفاده می‌شود.
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="page_title">@lang('title.page_title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="page_title" id="page_title"
                   value="{{ setting('index:page-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="banner_type">@lang('title.banner_type')</label>
        </div>
        <div class="col-12 col-md-6">
            <select class="form-select" type="text" name="banner_type" id="banner_type">
                <option value="slider"
                        @if(setting('index:banner-type') === 'slider') selected @endif>@lang('title.slider')</option>
                <option value="video"
                        @if(setting('index:banner-type') === 'video' || !setting('index:banner-type')) selected @endif>@lang('title.video')</option>
            </select>
        </div>
    </div>
    <div class="alert alert-info small mt-3 mb-0">
        <strong>بنر صفحه اصلی:</strong>
        <strong>موبایل:</strong> فقط اسلایدهای بخش پایین (اگر خالی باشد بنری نیست).
        <strong>دسکتاپ:</strong> «اسلایدر» = همین اسلایدها؛ «ویدئو» = ویدئو و عنوان/توضیحات.
    </div>

    <hr>
    <h4 class="text-center">@lang('title.video') <small class="text-muted fw-normal">(نوع بنر = ویدئو)</small></h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="banner_video">@lang('title.video')</label>
        </div>
        <div class="col-12 col-md-6">
            <index-banner-video-upload
                upload-url="{{ route('admin.setting.index-page.banner-video') }}"
                :max-upload-bytes="{{ index_banner_video_max_upload_bytes() }}"
                current-src="{{ settingFilePath('index:banner-video') }}"
                hint="پس از انتخاب، ویدئو در مرورگر فشرده و مستقیم ذخیره می‌شود (محدودیت ۲ مگابایت PHP دور زده می‌شود)."
            ></index-banner-video-upload>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="banner_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="banner_title" id="banner_title"
                   value="{{ setting('index:banner-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="banner_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="banner_description" id="banner_description"
                   value="{{ setting('index:banner-description') }}">
        </div>
    </div>

    @php
        $slider = json_decode(setting('index:slider') ?? '[]', true) ?: [];
        $slider = array_values(is_array($slider) ? $slider : (array) $slider);
        foreach ($slider as $index => $item) {
            if (! empty($item['image'])) {
                $slider[$index]['image_file'] = \App\Models\Setting::rasterImageFilename($item['image']);
                $slider[$index]['image'] = \App\Models\Setting::rasterImageUrl($item['image'], 'slider');
            }
        }
    @endphp
    <hr>
    <h4 class="text-center">@lang('title.slider') <small class="text-muted fw-normal">(نوع بنر = اسلایدر)</small></h4>
    <p class="text-muted small text-center mb-3">
        هر ردیف: تصویر، لینک (URL) و <strong>متن alt</strong> (توضیح کوتاه تصویر برای گوگل و نابینایان).
        اسلایدها در بالای صفحه اصلی (موبایل و دسکتاپ) نمایش داده می‌شوند؛ اسلاید اول با اولویت بارگذاری بالا لود می‌شود — تصویر سبک (ترجیحاً زیر ۳۰۰ کیلوبایت پس از WebP) انتخاب کنید.
    </p>
    <sliders
        :old='@json($slider)'
        url_text="@lang('title.url')"
        empty_message="@lang('text.slider_empty')"
        name="slider"
    ></sliders>

    <hr>
    <h4 class="text-center">@lang('title.homes')</h4>

    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_off_title">@lang('title.title') @lang('title.off')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_off_title" id="home_off_title"
                   value="{{ setting('index:home-off-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_off_description">@lang('title.description') @lang('title.off')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_off_description"
                      id="home_off_description">{!! setting('index:home-off-description') !!}</textarea>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_tomorrow_order_title">@lang('title.title') @lang('title.open_tomorrow')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_tomorrow_order_title" id="home_tomorrow_order_title"
                   value="{{ setting('index:home-tomorrow-order-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_tomorrow_order_description">@lang('title.description') @lang('title.open_tomorrow')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_tomorrow_order_description"
                      id="home_tomorrow_order_description">{!! setting('index:home-tomorrow-order-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_cheap_title">@lang('title.title') @lang('title.cheap_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_cheap_title" id="home_cheap_title"
                   value="{{ setting('index:home-cheap-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_cheap_description">@lang('title.description') @lang('title.cheap_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_cheap_description"
                      id="home_cheap_description">{!! setting('index:home-cheap-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_popular_title">@lang('title.title') @lang('title.popular_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_popular_title" id="home_popular_title"
                   value="{{ setting('index:home-popular-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_popular_description">@lang('title.description') @lang('title.popular_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_popular_description"
                      id="home_popular_description">{!! setting('index:home-popular-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_latest_title">@lang('title.title') @lang('title.last_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_latest_title" id="home_latest_title"
                   value="{{ setting('index:home-latest-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_latest_description">@lang('title.description') @lang('title.last_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_latest_description"
                      id="home_latest_description">{!! setting('index:home-latest-description') !!}</textarea>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_expensive_title">@lang('title.title') @lang('title.expensive_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="home_expensive_title" id="home_expensive_title"
                   value="{{ setting('index:home-expensive-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="home_expensive_description">@lang('title.description') @lang('title.expensive_homes')</label>
        </div>
        <div class="col-12 col-md-6">
            <textarea class="form-control" type="text" name="home_expensive_description"
                      id="home_expensive_description">{!! setting('index:home-expensive-description') !!}</textarea>
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.consultants')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="consultant_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="consultant_title" id="consultant_title"
                   value="{{ setting('index:consultant-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="consultant_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="consultant_description" id="consultant_description"
                   value="{{ setting('index:consultant-description') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.comments')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="comments_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="comments_title" id="comments_title"
                   value="{{ setting('index:comments-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="comments_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="comments_description" id="comments_description"
                   value="{{ setting('index:comments-description') }}">
        </div>
    </div>

    <hr>
    <h4 class="text-center">@lang('title.articles')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="articles_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="articles_title" id="articles_title"
                   value="{{ setting('index:articles-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="articles_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="articles_description" id="articles_description"
                   value="{{ setting('index:articles-description') }}">
        </div>
    </div>

    <hr>
    @php
        $indexCities = json_decode(setting('index:cities') ?? '[]', true) ?: [];
        $indexCities = array_values(is_array($indexCities) ? $indexCities : (array) $indexCities);
        foreach ($indexCities as $index => $item) {
            if (! empty($item['image'])) {
                $indexCities[$index]['image_file'] = \App\Models\Setting::rasterImageFilename($item['image']);
                $indexCities[$index]['image'] = \App\Models\Setting::rasterImageUrl($item['image'], 'cities');
            }
        }

        $indexProvinces = Province::getFromCache()->map(function ($province) {
            return [
                'id' => $province->id,
                'name' => $province->name,
                'homes_count' => $province->homes_count,
                'cities' => $province->cities->map(fn ($city) => [
                    'id' => $city->id,
                    'name' => $city->name,
                    'homes_count' => $city->homes_count,
                ])->values()->all(),
            ];
        })->values()->all();
    @endphp
    <h4 class="text-center">@lang('title.position')</h4>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="position_title">@lang('title.title')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="position_title" id="position_title"
                   value="{{ setting('index:position-title') }}">
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12 col-md-6">
            <label for="position_description">@lang('title.description')</label>
        </div>
        <div class="col-12 col-md-6">
            <input class="form-control" type="text" name="position_description" id="position_description"
                   value="{{ setting('index:position-description') }}">
        </div>
    </div>


    <hr>
    <h4 class="text-center">@lang('title.cities')</h4>
    <div class="mt-4">
        <index-cities
            :old='@json($indexCities)'
            :provinces_prop='@json($indexProvinces)'
            empty_message="@lang('text.city_empty')"
            province_placeholder="@lang('text.insert_province')"
            city_placeholder="@lang('text.insert_city')"
            select_text="@lang('title.select')"
            selected_text="@lang('title.selected')"
            remove_text="@lang('title.remove')"
            empty_result_text="@lang('text.empty_result')"
            empty_list_text="@lang('text.empty_list')"
        ></index-cities>
    </div>
    <div class="mt-5 d-flex justify-content-center">
        <button class="btn btn-falcon-success">@lang('title.submit')</button>
        <button type="reset" class="btn btn-falcon-warning mx-3">@lang('title.reset')</button>
    </div>
</form>
