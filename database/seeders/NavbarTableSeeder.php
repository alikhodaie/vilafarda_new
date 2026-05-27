<?php

namespace Database\Seeders;

use App\Models\Navbar;
use Illuminate\Database\Seeder;

class NavbarTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Navbar::query()->create(['title' => 'صفحه اصلی', 'link' => route('main.index')]);
        Navbar::query()->create(['title' => 'تماس با ما', 'link' => route('main.contact-us')]);
        Navbar::query()->create(['title' => 'درباره ما', 'link' => route('main.about-us')]);
        Navbar::query()->create(['title' => 'وبلاگ', 'link' => route('main.articles.index')]);
        Navbar::query()->create(['title' => 'املاک', 'link' => route('main.homes.index')]);
        Navbar::query()->create(['title' => 'سوالات متداول', 'link' => route('main.faq')]);
    }
}
