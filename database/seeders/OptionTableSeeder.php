<?php

namespace Database\Seeders;

use App\Models\Option;
use Faker\Factory;
use Illuminate\Database\Seeder;

class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $icons = ['1.png', '2.png', '3.png', '4.png'];
            $faker = Factory::create('fa_IR');

            foreach ($icons as $icon) {
                Option::query()->create(['title' => $faker->realText(50), 'icon' => $icon]);
            }
        }
    }
}
