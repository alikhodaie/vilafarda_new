<?php

namespace Database\Seeders;

use App\Models\Safety;
use Illuminate\Database\Seeder;

class SafetyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Safety::query()->create(['title' => 'جعبه کمک‌های اولیه', 'placeholder' => 'مکان جعبه کمک های اولیه در منزل را وارد کنید']);
        Safety::query()->create(['title' => 'کپسول آتشنشانی', 'placeholder' => 'مکان کپسول آتشنشانی در منزل را وارد کنید']);
        Safety::query()->create(['title' => 'برگه راهنمای ایمنی', 'placeholder' => 'مکان برگه راهنمای ایمنی در منزل را وارد کنید']);
    }
}
