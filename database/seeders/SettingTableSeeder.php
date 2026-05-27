<?php

namespace Database\Seeders;

use App\Models\Home;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['key' => 'app:logo', 'value' => 'logo.png'],
            ['key' => 'app:logo-light', 'value' => 'logo-light.png'],
            ['key' => 'app:auth-modal-active', 'value' => true],
            ['key' => 'app:auth-modal-img', 'value' => 'auth-banner.png'],
            ['key' => 'index:page-title', 'value' => 'اجاره باغ ویلا استخردار | لوکس ، اقامتی و مراسمی'],
            ['key' => 'index:banner-video', 'value' => 'banners.mp4'],
            ['key' => 'index:banner-title', 'value' => 'خانه جدید خود را پیدا کنید'],
            ['key' => 'index:banner-description', 'value' => 'ملک جدید و برجسته واقع در شهر محلی خود را پیدا کنید.'],
            ['key' => 'index:home-ready-order-title', 'value' => 'املاک آماده رزور امروز'],
            ['key' => 'index:home-ready-order-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد'],
            ['key' => 'index:home-cheap-title', 'value' => 'املاک ارزان'],
            ['key' => 'index:home-cheap-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد'],
            ['key' => 'index:home-popular-title', 'value' => 'املاک آماده رزور امروز'],
            ['key' => 'index:home-popular-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد'],
            ['key' => 'index:home-latest-title', 'value' => 'آخرین ملک ها'],
            ['key' => 'index:home-latest-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد'],
            ['key' => 'index:home-expensive-title', 'value' => 'املاک گران'],
            ['key' => 'index:home-expensive-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد'],
            ['key' => 'index:consultant-title', 'value' => 'مشاوران برجسته ما'],
            ['key' => 'index:consultant-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان.'],
            ['key' => 'index:position-title', 'value' => 'جستجو بر اساس موقعیت'],
            ['key' => 'index:position-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان.'],
            ['key' => 'index:comments-title', 'value' => 'نظرات خوب توسط مشتریان'],
            ['key' => 'index:comments-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان.'],
            ['key' => 'index:articles-title', 'value' => 'آخرین اخبار و مقالات'],
            ['key' => 'index:articles-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است.'],
            ['key' => 'app:contact-title', 'value' => 'آیا سوالی دارید؟'],
            ['key' => 'app:contact-description', 'value' => 'ما به شما کمک می کنیم تا شغل و پیشرفت خود را افزایش دهید.'],
            ['key' => 'app:contact-btn-text', 'value' => 'امروز با ما تماس بگیرید'],
            ['key' => 'app:newsletter-title', 'value' => 'آیا در مورد چیزی به کمک نیاز دارید؟'],
            ['key' => 'app:newsletter-description', 'value' => 'هر ماه به روزرسانی ها ، معاملات داغ ، آموزش ها ، تخفیف های مستقیم را در صندوق ورودی خود ارسال کنید'],
            ['key' => 'app:footer', 'value' => json_encode([
                'first_menu_title' => 'تست',
                'first_menu' => [
                    ['title' => 'صفحه اصلی', 'link' => route('main.index')],
                    ['title' => 'تماس با ما', 'link' => route('main.contact-us')],
                    ['title' => 'وبلاگ', 'link' => route('main.articles.index')],
                    ['title' => 'املاک', 'link' => route('main.homes.index')],
                    ['title' => 'سوالات متداول', 'link' => route('main.faq')]
                ],
                'second_menu_title' => 'تست',
                'second_menu' => [
                    ['title' => 'صفحه اصلی', 'link' => route('main.index')],
                    ['title' => 'تماس با ما', 'link' => route('main.contact-us')],
                    ['title' => 'وبلاگ', 'link' => route('main.articles.index')],
                    ['title' => 'املاک', 'link' => route('main.homes.index')],
                    ['title' => 'سوالات متداول', 'link' => route('main.faq')]
                ],
                'third_menu_title' => 'تست',
                'third_menu' => [
                    ['title' => 'صفحه اصلی', 'link' => route('main.index')],
                    ['title' => 'تماس با ما', 'link' => route('main.contact-us')],
                    ['title' => 'وبلاگ', 'link' => route('main.articles.index')],
                    ['title' => 'املاک', 'link' => route('main.homes.index')],
                    ['title' => 'سوالات متداول', 'link' => route('main.faq')]
                ],
                'enamad_url' => 'https://trustseal.enamad.ir/?id=341631&Code=Qk98lTGBRYsxA6HLexcG',
                'enamad_image_url' => 'https://trustseal.enamad.ir/logo.aspx?id=341631&Code=Qk98lTGBRYsxA6HLexcG',
                'phones' => [
                    ['label' => 'پشتیبانی', 'number' => '0211234567'],
                ],
                'socials' => [
                    ['title' => 'اینستاگرام', 'link' => 'https://instagram.com/rentnaab', 'icon_type' => 'font', 'icon_class' => 'bi-instagram'],
                    ['title' => 'بله', 'link' => '', 'icon_type' => 'font', 'icon_class' => 'bi-chat-dots-fill'],
                    ['title' => 'روبیکا', 'link' => '', 'icon_type' => 'font', 'icon_class' => 'bi-chat-square-fill'],
                ],
                'mobile_nav' => [
                    ['title' => 'صفحه اصلی', 'link' => route('main.index'), 'icon' => 'bi-house'],
                    ['title' => 'املاک', 'link' => route('main.homes.index'), 'icon' => 'bi-search'],
                    ['title' => 'تماس با ما', 'link' => route('main.contact-us'), 'icon' => 'bi-telephone'],
                    ['title' => 'سوالات متداول', 'link' => route('main.faq'), 'icon' => 'bi-question-circle'],
                ],
            ])],
            ['key' => 'faq:banner', 'value' => 'faq.png'],
            ['key' => 'faq:title', 'value' => 'سوالات متداول'],
            ['key' => 'submit-home:banner', 'value' => 'submit-home.png'],
            ['key' => 'submit-home:page-title', 'value' => 'ثبت ملک'],
            ['key' => 'submit-home:title', 'value' => 'رنت ناب - ثبت ملک'],
            ['key' => 'submit-home:first-title', 'value' => 'صحت اطلاعات'],
            ['key' => 'submit-home:first-description', 'value' => 'مطمئن شوید که نمایه اقامتگاه خود را منطبق با واقعیت تنظیم می کنید. به هیچ عنوان امکاناتی که در منزل فراهم نیست را ثبت نکنید و یا از عکسهای غیر واقعی استفاده نکنید. همچنین در صورتی که عیب, نقص یا مشکلی در اقامتگاه وجود دارد, در توضیحات اقامتگاه ذکر کنید و تصاویر آن را اضافه کنید. مطابق ضمانت تحویل اقامتگاه رنت ناب، درصورت اثبات عدم مطابقت اقامتگاه تحویل شده, رزرو لغو گردیده و کل ‏وجه دریافتی به میهمان بازگردانده خواهد شد.'],
            ['key' => 'submit-home:second-title', 'value' => 'لغو مراحل ثبت'],
            ['key' => 'submit-home:second-description', 'value' => 'در صورتی که قادر به اتمام مراحل ثبت اقامتگاه نباشید, می توانید با کلیک بر گزینه "لغو و خروج" در بالای صفحه, مراحل ثبت را نیمه کاره بگذارید. اطلاعات وارد شده تا همان مرحله ذخیره می شود و می توانید در فرصت بعدی مراحل ثبت اقامتگاه را به پایان برسانید. پس با خیال راحت, ثبت اقامتگاه خود را شروع کنید.'],
            ['key' => 'new-home:policy', 'value' => 'تست'],
            ['key' => 'new-home:help-page-1', 'value' => 'تست 1'],
            ['key' => 'new-home:help-page-2', 'value' => 'تست 2'],
            ['key' => 'new-home:help-page-3', 'value' => 'تست 3'],
            ['key' => 'new-home:help-page-4', 'value' => 'تست 4'],
            ['key' => 'new-home:help-page-5', 'value' => 'تست 5'],
            ['key' => 'new-home:help-page-6', 'value' => 'تست 6'],
            ['key' => 'new-home:help-page-7', 'value' => 'تست 7'],
            ['key' => 'new-home:help-page-8', 'value' => 'تست 8'],
            ['key' => 'new-home:help-page-9', 'value' => 'تست 9'],
            ['key' => 'new-home:help-page-10', 'value' => 'تست 10'],
            ['key' => 'new-home:help-page-11', 'value' => 'تست 11'],
            ['key' => 'new-home:help-page-12', 'value' => 'تست 12'],
            ['key' => 'new-home:help-page-13', 'value' => 'تست 13'],
            ['key' => 'new-home:help-page-14', 'value' => 'تست 14'],
            ['key' => 'new-home:help-page-15', 'value' => 'تست 15'],
            ['key' => 'about-us:banner', 'value' => 'about-us.png'],
            ['key' => 'about-us:page-title', 'value' => 'درباره ما'],
            ['key' => 'about-us:title', 'value' => 'درباره ما - ما کی هستیم؟'],
            ['key' => 'about-us:story-title', 'value' => 'داستان املاک ما'],
            ['key' => 'about-us:story-title1', 'value' => 'داستان شرکت و روند کار ما را بررسی کنید'],
            ['key' => 'about-us:story-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای متنوع با هدف بهبود ابزارهای.

لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است و برای شرایط فعلی تکنولوژی مورد نیاز و کاربردهای.'],
            ['key' => 'about-us:story-btn-title', 'value' => 'بیشتر درباره ما'],
            ['key' => 'about-us:story-btn-link', 'value' => ''],
            ['key' => 'about-us:story-image', 'value' => 'about-us-story.png'],
            ['key' => 'about-us:reward-title', 'value' => 'جوایز ما'],
            ['key' => 'about-us:reward-description', 'value' => 'بیش از 1،24،000+ رضایت کاربر که هنوز خدمات ما را دوست دارند'],
            ['key' => 'about-us:reward-box1-count', 'value' => '32'],
            ['key' => 'about-us:reward-box1-title', 'value' => 'جایزه املاک آبی'],
            ['key' => 'about-us:reward-box2-count', 'value' => '43'],
            ['key' => 'about-us:reward-box2-title', 'value' => 'جایزه رضایت مشتری'],
            ['key' => 'about-us:reward-box3-count', 'value' => '51'],
            ['key' => 'about-us:reward-box3-title', 'value' => 'جایزه UGC استرالیا'],
            ['key' => 'about-us:reward-box4-count', 'value' => '42'],
            ['key' => 'about-us:reward-box4-title', 'value' => 'جایزه سبز IITCA'],
            ['key' => 'about-us:comments-title', 'value' => 'نظرات خوب توسط مشتریان'],
            ['key' => 'about-us:comments-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است.'],
            ['key' => 'about-us:comments', 'value' => json_encode([
                ['name' => 'لیلا یوسفی', 'job' => 'لینکدین', 'score' => '4.5', 'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله.'],
                ['name' => 'لیلا یوسفی', 'job' => 'لینکدین', 'score' => '4.5', 'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله.'],
                ['name' => 'لیلا یوسفی', 'job' => 'لینکدین', 'score' => '4.5', 'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله.'],
                ['name' => 'لیلا یوسفی', 'job' => 'لینکدین', 'score' => '4.5', 'description' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله.'],
            ])],
            ['key' => 'about-us:articles-title', 'value' => 'آخرین اخبار و مقالات'],
            ['key' => 'about-us:articles-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است.'],
            ['key' => 'contact-us:banner', 'value' => 'contact-us.png'],
            ['key' => 'contact-us:title', 'value' => 'تماس با ما'],
            ['key' => 'contact-us:description1', 'value' => 'دریافت راهنما و پشتیبانی'],
            ['key' => 'contact-us:description2', 'value' => 'به دنبال کمک یا پشتیبانی هستید؟ ما 24 ساعته در دسترس هستیم.'],
            ['key' => 'contact-us:box1-title', 'value' => 'تماس با قسمت فروش'],
            ['key' => 'contact-us:box1-email', 'value' => 'sales@rikadahelp.co.uk'],
            ['key' => 'contact-us:box1-phone', 'value' => '0211234567'],
            ['key' => 'contact-us:box2-title', 'value' => 'تماس با قسمت فروش'],
            ['key' => 'contact-us:box2-email', 'value' => 'sales@rikadahelp.co.uk'],
            ['key' => 'contact-us:box2-phone', 'value' => '0211234567'],
            ['key' => 'contact-us:box3-title', 'value' => 'تماس با قسمت فروش'],
            ['key' => 'contact-us:box3-email', 'value' => 'sales@rikadahelp.co.uk'],
            ['key' => 'contact-us:box3-phone', 'value' => '0211234567'],
            ['key' => 'contact-us:map-iframe', 'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d821223.3678368849!2d50.81655200000001!3d35.660472!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e00491ff3dcd9%3A0xf0b3697c567024bc!2sTehran%2C%20Tehran%20Province%2C%20Iran!5e1!3m2!1sen!2sus!4v1641499396680!5m2!1sen!2sus" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'],
            ['key' => 'contact-us:article-title', 'value' => 'آخرین اخبار و مقالات'],
            ['key' => 'contact-us:article-description', 'value' => 'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است. چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است.'],
            ['key' => 'reservation:health-advice', 'value' => 'باوجود رعایت نکات بهداشتی توسط میزبانان، هیچگاه احتمال انتقال عوامل بیماریزا به صفر نمی رسد، لذا توصیه می شود در سفر لوازم شخصی همچون ملحفه و روبالشی، حوله، ظروف انفرادی و لوازم بهداشت شخصی را همراه ببرید. رعایت همین نکات به صورت محسوسی در افزایش ایمنی و سلامت شما عزیزان در طول سفر تاثیرگذار خواهد بود. با آرزوی سلامتی برای شما'],
            ['key' => 'reject-policy:'.Home::EASY, 'value' => 'لغو رزرو تا ۲۴ ساعت مانده به روز شروع اقامت: خسارتی پرداخت نمی‌شود. لغو رزرو در کمتر از ۲۴ ساعت مانده به روز شروع اقامت: ۴۰ درصد مبلغ شب اول. لغو رزرو حین اقامت: ۹۰ درصد مبلغ شب‌های سپری شده'],
            ['key' => 'reject-policy:'.Home::BALANCED, 'value' => 'لغو رزرو تا ۴۸ ساعت مانده به روز شروع اقامت: خسارتی پرداخت نمی‌شود. لغو رزرو در کمتر از ۴۸ ساعت مانده به روز شروع اقامت: ۹۰ درصد مبلغ شب اول. لغو رزرو حین اقامت: ۹۰ درصد مبلغ شب‌های سپری شده+ ۹۰ درصد مبلغ شب بعدی'],
            ['key' => 'reject-policy:'.Home::STRICT, 'value' => 'لغو رزرو تا ۷۲ ساعت مانده به روز شروع اقامت: ۱۰ درصد مبلغ رزرو. لغو رزرو در کمتر از ۷۲ ساعت مانده به روز شروع اقامت: ۹۰ درصد مبلغ شب اول + ۱۰ درصد شب‌های بعدی. لغو رزرو حین اقامت: ۹۰ درصد مبلغ تمامی شب‌ها'],
            ['key' => 'seo:default-description', 'value' => 'رزرو آنلاین ویلا، سوئیت و اقامتگاه در سراسر ایران. مقایسه قیمت، تصاویر واقعی و رزرو امن.'],
            ['key' => 'seo:index-meta-description', 'value' => ''],
            ['key' => 'seo:homes-meta-description', 'value' => 'جستجو و رزرو ویلا، سوئیت و اقامتگاه با فیلتر شهر، تاریخ و قیمت.'],
            ['key' => 'seo:articles-meta-description', 'value' => 'مقالات و راهنمای سفر، اجاره اقامتگاه و نکات رزرو.'],
            ['key' => 'seo:about-meta-description', 'value' => ''],
            ['key' => 'seo:contact-meta-description', 'value' => ''],
            ['key' => 'seo:privacy-meta-description', 'value' => ''],
            ['key' => 'seo:faq-meta-description', 'value' => ''],
            ['key' => 'seo:submit-home-meta-description', 'value' => ''],
            ['key' => 'seo:google-site-verification', 'value' => ''],
            ['key' => 'seo:default-og-image', 'value' => ''],
        ];

        foreach ($settings as $setting){
            Setting::query()->create($setting);
        }
    }
}
