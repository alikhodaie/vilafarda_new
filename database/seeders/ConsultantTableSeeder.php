<?php

namespace Database\Seeders;

use App\Models\Consultant;
use App\Models\Province;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class ConsultantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('fa_IR');
        $provinces = Province::getFromCache();

        for ($i = 0; $i < rand(4, 6); $i++){
            $province = $provinces->random()->first();

            $image = $faker->image(null, 2200, 950);
            $image = new UploadedFile($image, basename($image));
            $image = $image->store(Consultant::IMAGE_PATH);

            Consultant::query()->create([
                'name' => "{$faker->firstName} {$faker->lastName}",
                'image' => basename($image),
                'province_id' => $province->id,
                'city_id' => $province->cities->random()->first()->id,
                'phone_number' => $faker->phoneNumber,
                'whatsapp_number' => $faker->phoneNumber,
            ]);
        }
    }
}
