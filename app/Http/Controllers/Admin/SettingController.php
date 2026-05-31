<?php

namespace App\Http\Controllers\Admin;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\Home;
use App\Models\Province;
use App\Models\Setting;
use App\Rules\HomeRasterImageUpload;
use App\Services\IndexBannerVideoEncoder;
use App\Support\UploadValidation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $this->authorize('index', Setting::class);

        return view('admin.setting.index');
    }

    public function general(Request $request)
    {
        $data = [];
        # Logo
        if (auth()->user()->can('logo', Setting::class)){
            if ($request->hasFile('logo')){
                $data['app:logo'] = Setting::saveLogoFile($request->file('logo'), 'app:logo', 'logo');
            }

            if ($request->hasFile('logo_light')){
                $data['app:logo-light'] = Setting::saveLogoFile($request->file('logo_light'), 'app:logo-light', 'logo_light');
            }
        }

        # Auth Modal
        if (auth()->user()->can('appModalAuth', Setting::class)){
            if ($request->hasFile('auth_banner')){
                Setting::deleteFile(setting('app:auth-modal-img'));

                $data['app:auth-modal-img'] = Setting::saveFile($request->file('auth_banner'), 'auth-banner.png');
            }

            $data['app:auth-modal-active'] = $request->filled('auth_modal_status');
        }

        # Contact
        if (auth()->user()->can('appContact', Setting::class)){
            $data['app:contact-title'] = $request->get('contact_title');
            $data['app:contact-description'] = $request->get('contact_description');
            $data['app:contact-btn-text'] = $request->get('contact_btn_text');
        }

        # Newsletter
        if (auth()->user()->can('appNewsletter', Setting::class)){
            $data['app:newsletter-title'] = $request->get('newsletter_title');
            $data['app:newsletter-description'] = $request->get('newsletter_description');
        }

        foreach ($data as $key => $value){
            Setting::query()->where('key', $key)->first()->update(['value' => $value]);
        }

        if ($data !== []) {
            forgetSettingsCache();
        }

        return redirect()->route('admin.setting.index', ['active' => 'general'])->with('success', __('text.success.setting_general'));
    }

    public function payment(Request $request)
    {
        $this->authorize('payment', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'zarinpal:merchant-id' => $request->get('merchant_id'),
                'zarinpal:gate' => $request->filled('gate'),
                'zarinpal:sandbox' => $request->filled('sandbox'),
            ];

            foreach ($data as $key => $value){
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            }

            cache()->clear();
            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'payment'])->with('success', __('text.success.setting_payment'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function indexPage(Request $request)
    {
        $this->authorize('indexPage', Setting::class);

        $imageRule = new HomeRasterImageUpload();
        foreach ($request->file('cities', []) as $index => $row) {
            $file = $row['image'] ?? null;
            if (! $this->isUploadedImage($file)) {
                continue;
            }
            if (! $imageRule->passes('image', $file)) {
                return redirect()->back()
                    ->with('danger', 'تصویر شهر (ردیف '.($index + 1).'): '.$imageRule->message())
                    ->withInput();
            }
        }
        foreach ($request->file('slider', []) as $index => $row) {
            $file = $row['image'] ?? null;
            if (! $this->isUploadedImage($file)) {
                continue;
            }
            if (! $imageRule->passes('image', $file)) {
                return redirect()->back()
                    ->with('danger', 'تصویر اسلایدر (ردیف '.($index + 1).'): '.$imageRule->message())
                    ->withInput();
            }
        }

        $cities = json_decode(setting('index:cities') ?? '[]', true) ?: [];
        $cities = array_values(is_array($cities) ? $cities : (array) $cities);
        $new_cities = [];
        $provinces = Province::getFromCache();
        foreach (array_values($request->get('cities') ?? []) as $index => $item){
            $province = $provinces->where('id', $item['province'] ?? 0)->firstOrFail();
            $city = $province->cities->where('id', $item['city'] ?? 0)->firstOrFail();
            $uploadedImage = $request->file("cities.{$index}.image");
            $have_new_image = $this->isUploadedImage($uploadedImage);
            $oldImageFile = Setting::rasterImageFilename($cities[$index]['image'] ?? null)
                ?: Setting::rasterImageFilename($item['keep_image'] ?? null);
            $has_old_image = $oldImageFile !== null && $oldImageFile !== '';

            # delete old image
            $image_path = 'cities/';
            if ($has_old_image && $have_new_image){
                Storage::delete(Setting::FILE_PATH.$image_path.$oldImageFile);
            }

            $new_cities[$index]['province'] = ['id' => $province->id, 'name' => $province->name];
            $new_cities[$index]['city'] = ['id' => $city->id, 'name' => $city->name];
            $new_cities[$index]['count_homes'] = (int) ($province->homes_count ?? 0);

            if ($have_new_image){
                $new_cities[$index]['image'] = Setting::saveRasterImage($uploadedImage, $image_path);
            }
            elseif ($has_old_image) {
                $new_cities[$index]['image'] = $oldImageFile;
            }
        }

        $slider = json_decode(setting('index:slider') ?? '[]', true) ?: [];
        $slider = array_values(is_array($slider) ? $slider : (array) $slider);
        $new_slider = [];
        foreach (array_values($request->get('slider') ?? []) as $index => $item){
            $uploadedImage = $request->file("slider.{$index}.image");
            $have_new_image = $this->isUploadedImage($uploadedImage);
            $oldImageFile = Setting::rasterImageFilename($slider[$index]['image'] ?? null)
                ?: Setting::rasterImageFilename($item['keep_image'] ?? null);
            $has_old_image = $oldImageFile !== null && $oldImageFile !== '';

            # delete old image
            $image_path = 'slider/';
            if ($has_old_image && $have_new_image){
                Storage::delete(Setting::FILE_PATH.$image_path.$oldImageFile);
            }

            $new_slider[$index]['link'] = $request->get('slider')[$index]['link'];
            $new_slider[$index]['alt'] = trim((string) ($item['alt'] ?? ''));

            if ($have_new_image){
                $new_slider[$index]['image'] = Setting::saveRasterImage($uploadedImage, $image_path);
            }
            elseif ($has_old_image) {
                $new_slider[$index]['image'] = $oldImageFile;
            }
        }

        $data = [
            'index:banner-video' => setting('index:banner-video'),
            'index:banner-type' => $request->get('banner_type'),
            'index:page-title' => $request->get('page_title'),
            'index:banner-title' => $request->get('banner_title'),
            'index:banner-description' => $request->get('banner_description'),
            'index:consultant-title' => $request->get('consultant_title'),
            'index:consultant-description' => $request->get('consultant_description'),
            'index:position-title' => $request->get('position_title'),
            'index:position-description' => $request->get('position_description'),
            'index:comments-title' => $request->get('comments_title'),
            'index:comments-description' => $request->get('comments_description'),
            'index:articles-title' => $request->get('articles_title'),
            'index:articles-description' => $request->get('articles_description'),
            'index:cities' => json_encode(array_values($new_cities)),
            'index:slider' => json_encode(array_values($new_slider)),
            'index:home-ready-order-title' => $request->get('home_ready_order_title'),
            'index:home-ready-order-description' => $request->get('home_ready_order_description'),
            'index:home-cheap-title' => $request->get('home_cheap_title'),
            'index:home-cheap-description' => $request->get('home_cheap_description'),
            'index:home-popular-title' => $request->get('home_popular_title'),
            'index:home-popular-description' => $request->get('home_popular_description'),
            'index:home-latest-title' => $request->get('home_latest_title'),
            'index:home-latest-description' => $request->get('home_latest_description'),
            'index:home-expensive-title' => $request->get('home_expensive_title'),
            'index:home-expensive-description' => $request->get('home_expensive_description'),
            'index:home-off-title' => $request->get('home_off_title'),
            'index:home-off-description' => $request->get('home_off_description'),
            'index:home-tomorrow-order-title' => $request->get('home_tomorrow_order_title'),
            'index:home-tomorrow-order-description' => $request->get('home_tomorrow_order_description'),

        ];

        # Banner Video (hasFile فقط برای آپلود معتبر true است؛ ویدئوهای بزرگ‌تر از upload_max_filesize بدون خطا رد می‌شدند)
        $bannerVideo = $request->file('banner_video');
        if ($bannerVideo instanceof UploadedFile) {
            try {
                UploadValidation::validateUploadedOrFail($bannerVideo, 'banner_video', 'ویدئو بنر صفحه اصلی');
            } catch (ValidationException $e) {
                return redirect()->route('admin.setting.index', ['active' => 'index'])
                    ->with('danger', $e->validator->errors()->first('banner_video'))
                    ->withInput();
            }

            $mime = strtolower((string) $bannerVideo->getMimeType());
            if ($mime !== '' && strpos($mime, 'video/') !== 0) {
                return redirect()->route('admin.setting.index', ['active' => 'index'])
                    ->with('danger', 'فایل انتخاب‌شده باید ویدئو باشد (MP4، MOV، WebM و …).')
                    ->withInput();
            }

            Setting::deleteFile(setting('index:banner-video'));

            try {
                $data['index:banner-video'] = app(IndexBannerVideoEncoder::class)->storeOptimizedMp4($bannerVideo);
            } catch (\RuntimeException $e) {
                return redirect()->route('admin.setting.index', ['active' => 'index'])
                    ->with('danger', $e->getMessage())
                    ->withInput();
            }
        }

        foreach ($data as $key => $value){
            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        forgetSettingsCache();

        return redirect()->route('admin.setting.index', ['active' => 'index'])->with('success', __('text.success.setting_index'));
    }

    /**
     * آپلود ویدئو بنر جدا از فرم (بدنهٔ خام) — محدودیت upload_max_filesize اعمال نمی‌شود.
     */
    public function indexPageBannerVideo(Request $request)
    {
        $this->authorize('indexPage', Setting::class);

        $maxBytes = index_banner_video_max_upload_bytes();
        $raw = file_get_contents('php://input');

        if ($raw === false || $raw === '' || strlen($raw) < 1024) {
            return response()->json([
                'message' => 'ویدئو دریافت نشد. صفحه را رفرش کنید و دوباره انتخاب کنید.',
            ], 422);
        }

        if (strlen($raw) > $maxBytes) {
            return response()->json([
                'message' => sprintf(
                    'حجم ویدئو (%s مگ) از سقف این سرور (%s مگ، post_max_size=%s) بیشتر است.',
                    number_format(strlen($raw) / 1048576, 1),
                    number_format($maxBytes / 1048576, 1),
                    ini_get('post_max_size') ?: '?'
                ),
            ], 413);
        }

        $tmp = tempnam(sys_get_temp_dir(), 'rn_banner_');
        if ($tmp === false) {
            return response()->json(['message' => 'خطای موقت سرور.'], 500);
        }

        try {
            if (file_put_contents($tmp, $raw) === false) {
                throw new \RuntimeException('نوشتن فایل موقت انجام نشد.');
            }

            $contentType = strtolower((string) $request->header('Content-Type', 'video/mp4'));
            $extension = 'mp4';
            if (str_contains($contentType, 'quicktime') || str_contains($contentType, 'x-m4v')) {
                $extension = 'mov';
            } elseif (str_contains($contentType, 'webm')) {
                $extension = 'webm';
            }

            $uploaded = new UploadedFile(
                $tmp,
                'banner-input.'.$extension,
                $contentType !== '' ? $contentType : 'video/mp4',
                UPLOAD_ERR_OK,
                true
            );

            Setting::deleteFile(setting('index:banner-video'));

            $filename = app(IndexBannerVideoEncoder::class)->storeOptimizedMp4($uploaded);

            Setting::query()->updateOrCreate(
                ['key' => 'index:banner-video'],
                ['value' => $filename]
            );

            forgetSettingsCache();

            $absolute = Storage::disk('public-folder')->path(Setting::FILE_PATH.$filename);

            return response()->json([
                'ok' => true,
                'url' => settingFilePath('index:banner-video'),
                'size' => is_file($absolute) ? filesize($absolute) : strlen($raw),
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } finally {
            @unlink($tmp);
        }
    }

    public function commission(Request $request)
    {
        $this->authorize('commission', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'commission:easy' => $request->get('easy_commission'),
                'commission:balanced' => $request->get('balanced_commission'),
                'commission:strict' => $request->get('strict_commission'),
            ];

            foreach ($data as $key => $value){
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'commission'])->with('success', __('text.success.setting_commission'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function rejectPolicy(Request $request)
    {
        $this->authorize('rejectPolicy', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'reject-policy:'.Home::EASY => $request->get('easy_description'),
                'reject-policy:'.Home::BALANCED => $request->get('balanced_description'),
                'reject-policy:'.Home::STRICT => $request->get('strict_description'),
            ];

            foreach ($data as $key => $value){
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'rejectPolicy'])->with('success', __('text.success.setting_reject_policy'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function submitHome(Request $request)
    {
        $this->authorize('submitHome', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'submit-home:banner' => setting('submit-home:banner'),
                'submit-home:page-title' => $request->get('page_title'),
                'submit-home:title' => $request->get('title'),
                'submit-home:first-title' => $request->get('first_box_title'),
                'submit-home:first-description' => $request->get('first_box_description'),
                'submit-home:second-title' => $request->get('second_box_title'),
                'submit-home:second-description' => $request->get('second_box_description'),
                'new-home:policy' => $request->get('policy'),
                'new-home:help-page-1' => $request->get('help1'),
                'new-home:help-page-2' => $request->get('help2'),
                'new-home:help-page-3' => $request->get('help3'),
                'new-home:help-page-4' => $request->get('help4'),
                'new-home:help-page-5' => $request->get('help5'),
                'new-home:help-page-6' => $request->get('help6'),
                'new-home:help-page-7' => $request->get('help7'),
                'new-home:help-page-8' => $request->get('help8'),
                'new-home:help-page-9' => $request->get('help9'),
                'new-home:help-page-10' => $request->get('help10'),
                'new-home:help-page-11' => $request->get('help11'),
                'new-home:help-page-12' => $request->get('help12'),
                'new-home:help-page-13' => $request->get('help13'),
                'new-home:help-page-14' => $request->get('help14'),
                'new-home:help-page-15' => $request->get('help15'),
            ];

            if ($request->hasFile('banner')){
                Setting::deleteFile(setting('submit-home:banner'));

                $data['submit-home:banner'] = Setting::saveFile($request->file('banner'), 'submit-home.png');
            }

            foreach ($data as $key => $value){
                Setting::query()->where('key', $key)->first()->update(['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'submitHome'])->with('success', __('text.success.setting_contact_us'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function contactUs(Request $request)
    {
        $this->authorize('contactUs', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'contact-us:banner' => setting('contact-us:banner'),
                'contact-us:title' => $request->get('title'),
                'contact-us:description1' => $request->get('first-description'),
                'contact-us:description2' => $request->get('second-description'),
                'contact-us:box1-title' => $request->get('first-box-title'),
                'contact-us:box1-email' => $request->get('first-box-email'),
                'contact-us:box1-phone' => $request->get('first-box-phone'),
                'contact-us:box2-title' => $request->get('second-box-title'),
                'contact-us:box2-email' => $request->get('second-box-email'),
                'contact-us:box2-phone' => $request->get('second-box-phone'),
                'contact-us:box3-title' => $request->get('third-box-title'),
                'contact-us:box3-email' => $request->get('third-box-email'),
                'contact-us:box3-phone' => $request->get('third-box-phone'),
                'contact-us:map-iframe' => $request->get('map-iframe'),
                'contact-us:article-title' => $request->get('article-title'),
                'contact-us:article-description' => $request->get('article-description')
            ];

            if ($request->hasFile('banner')){
                Setting::deleteFile(setting('contact-us:banner'));

                $data['contact-us:banner'] = Setting::saveFile($request->file('banner'), 'contact-us.png');
            }

            foreach ($data as $key => $value){
                Setting::query()->where('key', $key)->first()->update(['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'contactUs'])->with('success', __('text.success.setting_contact_us'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function privacy(Request $request)
    {
        $this->authorize('privacy', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'privacy:banner' => setting('privacy:banner'),
                'privacy:title' => $request->get('title'),
                'privacy:description1' => $request->get('first-description'),
                'privacy:description2' => $request->get('second-description'),
                'privacy:content' => $request->get('content'),
                'privacy:article-title' => $request->get('article-title'),
                'privacy:article-description' => $request->get('article-description')
            ];

            if ($request->hasFile('banner')){
                if (setting('privacy:banner')){
                    Setting::deleteFile(setting('privacy:banner'));
                }

                $data['privacy:banner'] = Setting::saveFile($request->file('banner'), 'privacy.png');
            }

            foreach ($data as $key => $value){
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
//                $setting = Setting::query()->where('key', $key)->first();
//                ($setting)
//                    ? $setting->update(['value' => $value])
//                    : Setting::query()->create(['key' => $key, 'value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'privacy'])->with('success', __('text.success.setting_privacy'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function aboutUs(Request $request)
    {
        $this->authorize('aboutUs', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'about-us:banner' => setting('contact-us:banner'),
                'about-us:page-title' => $request->get('page_title'),
                'about-us:title' => $request->get('title'),
                'about-us:story-title' => $request->get('story_title'),
                'about-us:story-title1' => $request->get('story_little_title'),
                'about-us:story-description' => $request->get('story_description'),
                'about-us:story-btn-title' => $request->get('story_btn_title'),
                'about-us:story-btn-link' => $request->get('story_btn_link'),
                'about-us:story-image' => setting('about-us:story-image'),
                'about-us:reward-title' => $request->get('reward_title'),
                'about-us:reward-description' => $request->get('reward_description'),
                'about-us:reward-box1-count' => $request->get('reward_box1_count'),
                'about-us:reward-box1-title' => $request->get('reward_box1_title'),
                'about-us:reward-box2-count' => $request->get('reward_box2_count'),
                'about-us:reward-box2-title' => $request->get('reward_box2_title'),
                'about-us:reward-box3-count' => $request->get('reward_box3_count'),
                'about-us:reward-box3-title' => $request->get('reward_box3_title'),
                'about-us:reward-box4-count' => $request->get('reward_box4_count'),
                'about-us:reward-box4-title' => $request->get('reward_box4_title'),
                'about-us:comments-title' => $request->get('comments_title'),
                'about-us:comments-description' => $request->get('comments_description'),
                'about-us:comments' => json_encode($request->get('comments')),
                'about-us:articles-title' => $request->get('articles_title'),
                'about-us:articles-description' => $request->get('articles_description'),
            ];

            if ($request->hasFile('banner')){
                Setting::deleteFile(setting('about-us:banner'));

                $data['about-us:banner'] = Setting::saveFile($request->file('banner'), 'about-us.png');
            }
            if ($request->hasFile('story-banner')){
                Setting::deleteFile(setting('about-us:story-image'));

                $data['about-us:story-image'] = Setting::saveFile($request->file('story-banner'), 'about-us-story.png');
            }

            foreach ($data as $key => $value){
                Setting::query()->where('key', $key)->first()->update(['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'aboutUs'])->with('success', __('text.success.setting_about_us'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function faq(Request $request)
    {
        $this->authorize('faq', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'faq:banner' => setting('faq:banner'),
                'faq:title' => $request->get('title'),
            ];

            if ($request->hasFile('banner')){
                Setting::deleteFile(setting('faq:banner'));

                $data['faq:banner'] = Setting::saveFile($request->file('banner'), 'faq.png');
            }

            foreach ($data as $key => $value){
                Setting::query()->where('key', $key)->first()->update(['value' => $value]);
            }

            DB::commit();
            return redirect()->route('admin.setting.index', ['active' => 'faq'])->with('success', __('text.success.setting_faq'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function seo(Request $request)
    {
        $this->authorize('seo', Setting::class);

        try {
            DB::beginTransaction();

            $data = [
                'seo:default-description' => $request->get('default_description'),
                'seo:index-title' => $request->get('index_title'),
                'seo:homes-title' => $request->get('homes_title'),
                'seo:articles-title' => $request->get('articles_title'),
                'seo:contact-title' => $request->get('contact_title'),
                'seo:about-title' => $request->get('about_title'),
                'seo:privacy-title' => $request->get('privacy_title'),
                'seo:faq-title' => $request->get('faq_title'),
                'seo:submit-home-title' => $request->get('submit_home_title'),
                'seo:index-meta-description' => $request->get('index_meta_description'),
                'seo:homes-meta-description' => $request->get('homes_meta_description'),
                'seo:articles-meta-description' => $request->get('articles_meta_description'),
                'seo:contact-meta-description' => $request->get('contact_meta_description'),
                'seo:about-meta-description' => $request->get('about_meta_description'),
                'seo:privacy-meta-description' => $request->get('privacy_meta_description'),
                'seo:faq-meta-description' => $request->get('faq_meta_description'),
                'seo:submit-home-meta-description' => $request->get('submit_home_meta_description'),
                'seo:google-site-verification' => trim((string) $request->get('google_site_verification')),
                'seo:ga4-measurement-id' => AnalyticsService::sanitizeMeasurementIdInput($request->get('ga4_measurement_id')),
                'seo:default-og-image' => setting('seo:default-og-image'),
            ];

            if ($request->hasFile('default_og_image')) {
                Setting::deleteFile(setting('seo:default-og-image'));
                $data['seo:default-og-image'] = Setting::saveFile($request->file('default_og_image'), 'seo-og.png');
            }

            foreach ($data as $key => $value) {
                Setting::query()->updateOrCreate(['key' => $key], ['value' => $value ?? '']);
            }

            forgetSettingsCache();
            DB::commit();

            return redirect()->route('admin.setting.index', ['active' => 'seo'])->with('success', __('text.success.setting_seo'));
        } catch (Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    public function footer(Request $request)
    {
        $this->authorize('footer', Setting::class);

        try {
            DB::beginTransaction();

            $existing = footerSettings();
            $socials = $this->normalizeFooterSocials($request, $existing['socials'] ?? []);

            $data = json_encode([
                'first_menu_title' => $request->get('first_menu_title'),
                'first_menu' => array_values($request->get('first_menu', []) ?: []),
                'second_menu_title' => $request->get('second_menu_title'),
                'second_menu' => array_values($request->get('second_menu', []) ?: []),
                'third_menu_title' => $request->get('third_menu_title'),
                'third_menu' => array_values($request->get('third_menu', []) ?: []),
                'enamad_html' => trim((string) $request->get('enamad_html', '')),
                'trust_section_title' => trim((string) $request->get('trust_section_title', '')),
                'phones' => array_values(array_filter($request->get('phones', []) ?: [], function ($row) {
                    return ! empty($row['number']);
                })),
                'socials' => $socials,
                'mobile_nav' => array_values(array_filter($request->get('mobile_nav', []) ?: [], function ($row) {
                    return ! empty($row['title']) && ! empty($row['link']);
                })),
            ]);

            Setting::query()->where('key', 'app:footer')->first()->update(['value' => $data]);

            forgetSettingsCache();
            DB::commit();

            return redirect()->route('admin.setting.index', ['active' => 'footer'])->with('success', __('text.success.setting_footer'));
        }
        catch (Exception $e){
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);

            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }

    private function normalizeFooterSocials(Request $request, array $existingSocials): array
    {
        $socials = $request->get('socials', []) ?: [];
        $normalized = [];

        foreach ($socials as $index => $row) {
            if (empty($row['link']) && empty($row['title'])) {
                continue;
            }

            $iconClass = trim((string) ($row['icon_class'] ?? ''));
            if ($iconClass === '' && ! empty($existingSocials[$index]['icon_class'])) {
                $iconClass = trim((string) $existingSocials[$index]['icon_class']);
            }

            $entry = [
                'title' => $row['title'] ?? '',
                'link' => $row['link'] ?? '',
                'follower_count' => trim((string) ($row['follower_count'] ?? '')),
            ];

            $file = $request->file("socials.{$index}.icon_image");
            $existingIcon = $row['icon'] ?? ($existingSocials[$index]['icon'] ?? '');

            if ($this->isUploadedImage($file)) {
                $oldIcon = $existingSocials[$index]['icon'] ?? null;
                if (($existingSocials[$index]['icon_type'] ?? '') === 'image' && $oldIcon) {
                    Setting::deleteFile('footer/'.$oldIcon);
                }

                $entry['icon'] = Setting::saveRasterImage($file, 'footer/');
                $entry['icon_type'] = 'image';
            } elseif (($row['icon_type'] ?? 'font') === 'image' && $existingIcon !== '') {
                $entry['icon'] = $existingIcon;
                $entry['icon_type'] = 'image';
            } else {
                $entry['icon_type'] = 'font';
                $entry['icon_class'] = $iconClass;
            }

            if ($iconClass !== '' && ($entry['icon_type'] ?? 'font') === 'font') {
                $entry['icon_class'] = $iconClass;
            }

            $normalized[] = $entry;
        }

        return $normalized;
    }

    private function isUploadedImage($file): bool
    {
        return $file instanceof UploadedFile
            && $file->isValid()
            && $file->getSize() > 0;
    }
}
