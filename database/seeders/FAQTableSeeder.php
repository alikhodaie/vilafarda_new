<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Seeder;

class FAQTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $categories = Category::query()->faq()->get();
            $faker = Factory::create('fa_IR');

            foreach ($categories as $category) {
                for ($i = 0; $i < rand(1, 5); $i++) {
                    $category->FAQ()->create(['question' => $faker->realText, 'answer' => $faker->realText, 'sort' => $i]);
                }
            }
        }
    }
}
