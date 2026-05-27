<?php

namespace Database\Seeders;

use App\Models\Tag;
use Faker\Factory;
use Illuminate\Database\Seeder;

class TagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()){
            $faker = Factory::create('fa_IR');

            for ($i = 0; $i < rand(30, 50); $i++){
                Tag::query()->create(['name' => $faker->realText(20)]);
            }
        }
    }
}
