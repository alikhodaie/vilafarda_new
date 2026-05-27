<?php

namespace Database\Seeders;

use App\Models\Health;
use Illuminate\Database\Seeder;

class HealthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Health::query()->create(['title' => 'تعویض روبالشتی و روتختی (برای هر میهمان جدید)']);
        Health::query()->create(['title' => 'تعویض ملحفه (برای هر میهمان جدید)']);
        Health::query()->create(['title' => 'شارژ کاغذ توالت']);
        Health::query()->create(['title' => 'مایع ظرفشویی']);
        Health::query()->create(['title' => 'شارژ مایع دستشویی یا صابون']);
        Health::query()->create(['title' => 'مواد ضدعفونی کننده']);
    }
}
