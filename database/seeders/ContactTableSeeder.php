<?php

namespace Database\Seeders;

use App\Models\Contact;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ContactTableSeeder extends Seeder
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

            for ($i = 0; $i < rand(20, 50); $i++){
                Contact::query()->create([
                    'name' => "{$faker->firstName} {$faker->lastName}",
                    'mobile' => $faker->phoneNumber,
                    'subject' => $faker->realText(100),
                    'message' => $faker->realText(500),
                    'is_seen' => rand(1, 0)
                ]);
            }
        }
    }
}
