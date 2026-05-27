<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [

            # region Admin
            ['name' => 'admins:index', 'fa_name' => 'نمایش', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:create', 'fa_name' => 'ایجاد', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:update', 'fa_name' => 'ویرایش', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:destroy', 'fa_name' => 'حذف', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:assignRole', 'fa_name' => 'اعمال نقش', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:updateRole', 'fa_name' => 'ویرایش نقش', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            ['name' => 'admins:block', 'fa_name' => 'بلاک', 'group' => 'admins', 'fa_group' => 'مدیران', 'guard_name' => 'web'],
            # endregion

            # region Users
            ['name' => 'users:index', 'fa_name' => 'نمایش', 'group' => 'users', 'fa_group' => 'کاربران', 'guard_name' => 'web'],
            ['name' => 'users:create', 'fa_name' => 'ایجاد', 'group' => 'users', 'fa_group' => 'کاربران', 'guard_name' => 'web'],
            ['name' => 'users:update', 'fa_name' => 'ویرایش', 'group' => 'users', 'fa_group' => 'کاربران', 'guard_name' => 'web'],
            ['name' => 'users:destroy', 'fa_name' => 'حذف', 'group' => 'users', 'fa_group' => 'کاربران', 'guard_name' => 'web'],
            ['name' => 'users:block', 'fa_name' => 'بلاک', 'group' => 'users', 'fa_group' => 'کاربران', 'guard_name' => 'web'],
            # endregion

            # region Article
            ['name' => 'articles:index', 'fa_name' => 'نمایش', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'articles:create', 'fa_name' => 'ایجاد', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'articles:update', 'fa_name' => 'ویرایش', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'articles:destroy', 'fa_name' => 'حذف', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            # endregion

            # region Article Category
            ['name' => 'article-categories:index', 'fa_name' => 'نمایش دسته بندی', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'article-categories:create', 'fa_name' => 'ایجاد دسته بندی', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'article-categories:update', 'fa_name' => 'ویرایش دسته بندی', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            ['name' => 'article-categories:destroy', 'fa_name' => 'حذف دسته بندی', 'group' => 'blog', 'fa_group' => 'وبلاگ', 'guard_name' => 'web'],
            # endregion

            # region Comments
            ['name' => 'comments:index', 'fa_name' => 'نمایش', 'group' => 'comments', 'fa_group' => 'نظرات', 'guard_name' => 'web'],
            ['name' => 'comments:create', 'fa_name' => 'ساخت', 'group' => 'comments', 'fa_group' => 'نظرات', 'guard_name' => 'web'],
            ['name' => 'comments:update', 'fa_name' => 'ویرایش', 'group' => 'comments', 'fa_group' => 'نظرات', 'guard_name' => 'web'],
            ['name' => 'comments:destroy', 'fa_name' => 'حذف', 'group' => 'comments', 'fa_group' => 'نظرات', 'guard_name' => 'web'],
            # endregion

            # region Roles
            ['name' => 'roles:index', 'fa_name' => 'نمایش', 'group' => 'roles', 'fa_group' => 'نقش ها', 'guard_name' => 'web'],
            ['name' => 'roles:create', 'fa_name' => 'ایجاد', 'group' => 'roles', 'fa_group' => 'نقش ها', 'guard_name' => 'web'],
            ['name' => 'roles:update', 'fa_name' => 'ویرایش', 'group' => 'roles', 'fa_group' => 'نقش ها', 'guard_name' => 'web'],
            ['name' => 'roles:destroy', 'fa_name' => 'حذف', 'group' => 'roles', 'fa_group' => 'نقش ها', 'guard_name' => 'web'],
            # endregion

            # region Homes
            ['name' => 'homes:index', 'fa_name' => 'نمایش', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'homes:create', 'fa_name' => 'ایجاد', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'homes:update', 'fa_name' => 'ویرایش', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'homes:destroy', 'fa_name' => 'حذف', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home Dates
            ['name' => 'home-dates:show', 'fa_name' => 'نمایش تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-dates:update', 'fa_name' => 'ویرایش تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-dates:update-fast-reserve', 'fa_name' => 'ویرایش دوره رزور سریع', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-dates:destroy', 'fa_name' => 'حذف تقویم', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home variables
            ['name' => 'home-variables:index', 'fa_name' => 'نمایش قانون', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-variables:create', 'fa_name' => 'ایجاد قانون', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-variables:update', 'fa_name' => 'ویرایش قانون', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-variables:destroy', 'fa_name' => 'حذف قانون', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home Options
            ['name' => 'home-options:index', 'fa_name' => 'نمایش ویژگی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-options:create', 'fa_name' => 'ایجاد ویژگی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-options:update', 'fa_name' => 'ویرایش ویژگی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-options:destroy', 'fa_name' => 'حذف ویژگی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home Healths
            ['name' => 'home-healths:index', 'fa_name' => 'نمایش اقلام بهداشتی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-healths:create', 'fa_name' => 'ایجاد اقلام بهداشتی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-healths:update', 'fa_name' => 'ویرایش اقلام بهداشتی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-healths:destroy', 'fa_name' => 'حذف اقلام بهداشتی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home Safeties
            ['name' => 'home-safeties:index', 'fa_name' => 'نمایش ایمنی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-safeties:create', 'fa_name' => 'ایجاد ایمنی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-safeties:update', 'fa_name' => 'ویرایش ایمنی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-safeties:destroy', 'fa_name' => 'حذف ایمنی', 'group' => 'homes', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Home Category
            ['name' => 'home-categories:index', 'fa_name' => 'نمایش دسته بندی', 'group' => 'blog', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-categories:create', 'fa_name' => 'ایجاد دسته بندی', 'group' => 'blog', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-categories:update', 'fa_name' => 'ویرایش دسته بندی', 'group' => 'blog', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            ['name' => 'home-categories:destroy', 'fa_name' => 'حذف دسته بندی', 'group' => 'blog', 'fa_group' => 'املاک', 'guard_name' => 'web'],
            # endregion

            # region Orders
            ['name' => 'orders:index', 'fa_name' => 'مشاهده', 'group' => 'orders', 'fa_group' => 'سفارشات', 'guard_name' => 'web'],
            ['name' => 'orders:status', 'fa_name' => 'تغییر وضعیت', 'group' => 'orders', 'fa_group' => 'سفارشات', 'guard_name' => 'web'],
            ['name' => 'orders:sms', 'fa_name' => 'دریافت پیامک', 'group' => 'orders', 'fa_group' => 'سفارشات', 'guard_name' => 'web'],
            # endregion

            # region Tickets
            ['name' => 'tickets:index', 'fa_name' => 'مشاهده', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            ['name' => 'tickets:create', 'fa_name' => 'ایجاد', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            ['name' => 'tickets:reply', 'fa_name' => 'پاسخ', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            ['name' => 'tickets:update', 'fa_name' => 'ویرایش', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            ['name' => 'tickets:destroy', 'fa_name' => 'حذف', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],

            ['name' => 'ticket-messages:update', 'fa_name' => 'ویرایش پیام', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            ['name' => 'ticket-messages:destroy', 'fa_name' => 'حذف پیام', 'group' => 'tickets', 'fa_group' => 'تیکت ها', 'guard_name' => 'web'],
            # endregion

            # region Navbar
            ['name' => 'navbar:index', 'fa_name' => 'نمایش', 'group' => 'roles', 'fa_group' => 'نوبار', 'guard_name' => 'web'],
            ['name' => 'navbar:create', 'fa_name' => 'ایجاد', 'group' => 'roles', 'fa_group' => 'نوبار', 'guard_name' => 'web'],
            ['name' => 'navbar:update', 'fa_name' => 'ویرایش', 'group' => 'roles', 'fa_group' => 'نوبار', 'guard_name' => 'web'],
            ['name' => 'navbar:destroy', 'fa_name' => 'حذف', 'group' => 'roles', 'fa_group' => 'نوبار', 'guard_name' => 'web'],
            # endregion

            # region Article
            ['name' => 'faq:index', 'fa_name' => 'نمایش', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq:create', 'fa_name' => 'ایجاد', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq:update', 'fa_name' => 'ویرایش', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq:destroy', 'fa_name' => 'حذف', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            # endregion

            # region Article Category
            ['name' => 'faq-categories:index', 'fa_name' => 'نمایش دسته بندی', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq-categories:create', 'fa_name' => 'ایجاد دسته بندی', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq-categories:update', 'fa_name' => 'ویرایش دسته بندی', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            ['name' => 'faq-categories:destroy', 'fa_name' => 'حذف دسته بندی', 'group' => 'faq', 'fa_group' => 'سوالات متداول', 'guard_name' => 'web'],
            # endregion

            # region Contact
            ['name' => 'contacts:index', 'fa_name' => 'نمایش', 'group' => 'contacts', 'fa_group' => 'تماس ها', 'guard_name' => 'web'],
            ['name' => 'contacts:destroy', 'fa_name' => 'حذف', 'group' => 'contacts', 'fa_group' => 'تماس ها', 'guard_name' => 'web'],
            # endregion

            # region Setting
            ['name' => 'setting:app-logo', 'fa_name' => 'لوگو', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:app-modal-auth', 'fa_name' => 'مودال اعتبارسنجی', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:app-contact', 'fa_name' => 'فوتر تماس', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:app-newsletter', 'fa_name' => 'فوتر خبرنامه', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:footer', 'fa_name' => 'لینک های فوتر', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:contact-us', 'fa_name' => 'صفحه تماس با ما', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:privacy', 'fa_name' => 'صفحه قوانین', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:index', 'fa_name' => 'صفحه اصلی', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:faq', 'fa_name' => 'صفحه سوالات متداول', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:about-us', 'fa_name' => 'صفحه درباره ما', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:submit-home', 'fa_name' => 'صفحه ثبت ملک', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:payment', 'fa_name' => 'درگاه پرداخت', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:commission', 'fa_name' => 'کمیسیون', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:reject-policy', 'fa_name' => 'لغو رزرو', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            ['name' => 'setting:seo', 'fa_name' => 'سئو و متا تگ‌ها', 'group' => 'settings', 'fa_group' => 'تنظیمات', 'guard_name' => 'web'],
            # endregion

            # region Newsletter
            ['name' => 'newsletters:index', 'fa_name' => 'مشاهده', 'group' => 'newsletters', 'fa_group' => 'خبرنامه'],
            ['name' => 'newsletters:create', 'fa_name' => 'ایجاد', 'group' => 'newsletters', 'fa_group' => 'خبرنامه'],
            ['name' => 'newsletters:destroy', 'fa_name' => 'حذف', 'group' => 'newsletters', 'fa_group' => 'خبرنامه'],

            ['name' => 'newsletter_subscribers:index', 'fa_name' => 'مشاهده', 'group' => 'newsletters', 'fa_group' => 'مشترکین خبرنامه', 'guard_name' => 'web'],
            ['name' => 'newsletter_subscribers:destroy', 'fa_name' => 'حذف', 'group' => 'newsletters', 'fa_group' => 'مشترکین خبرنامه', 'guard_name' => 'web'],
            # endregion

            # region Consultants
            ['name' => 'consultants:index', 'fa_name' => 'مشاهده', 'group' => 'consultants', 'fa_group' => 'مشاوران'],
            ['name' => 'consultants:create', 'fa_name' => 'ایجاد', 'group' => 'consultants', 'fa_group' => 'مشاوران'],
            ['name' => 'consultants:update', 'fa_name' => 'ویرایش', 'group' => 'consultants', 'fa_group' => 'مشاوران'],
            ['name' => 'consultants:destroy', 'fa_name' => 'حذف', 'group' => 'consultants', 'fa_group' => 'مشاوران'],
            # endregion

            # region Withdraws
            ['name' => 'withdraws:index', 'fa_name' => 'مشاهده', 'group' => 'withdraws', 'fa_group' => 'درخواست تسویه'],
            ['name' => 'withdraws:create', 'fa_name' => 'ایجاد', 'group' => 'withdraws', 'fa_group' => 'درخواست تسویه'],
            ['name' => 'withdraws:update', 'fa_name' => 'ویرایش', 'group' => 'withdraws', 'fa_group' => 'درخواست تسویه'],
            # endregion

            # region SMS Templates
            ['name' => 'sms-templates:index', 'fa_name' => 'مشاهده قالب‌های پیامک', 'group' => 'sms', 'fa_group' => 'پیامک', 'guard_name' => 'web'],
            # endregion

            # region Discounts
            ['name' => 'discounts:index', 'fa_name' => 'مشاهده', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
            ['name' => 'discounts:create', 'fa_name' => 'ایجاد', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
            ['name' => 'discounts:update', 'fa_name' => 'ویرایش', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
            ['name' => 'discounts:destroy', 'fa_name' => 'حذف', 'group' => 'discounts', 'fa_group' => 'تخفیف‌ها', 'guard_name' => 'web'],
            # endregion
        ];

        foreach ($permissions as $permission) {
            $permission['guard_name'] = $permission['guard_name'] ?? 'web';

            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }

        Role::where('name', 'super-admin')->first()?->givePermissionTo('sms-templates:index');
    }
}
