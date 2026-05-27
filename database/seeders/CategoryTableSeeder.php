<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $faker = Factory::create('fa_IR');

            foreach (Category::SECTIONS as $section) {
                for ($i = 0; $i < rand(3, 6); $i++) {
                    Category::query()->create(['title' => $faker->realText(20), 'section' => $section]);
                }
            }
        }
    }
}
