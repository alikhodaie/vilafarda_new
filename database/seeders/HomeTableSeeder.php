<?php

namespace Database\Seeders;

use App\Models\Home;
use App\Models\Option;
use App\Models\Province;
use App\Models\User;
use App\Models\Variable;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class HomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            $users = User::all()->random(rand(1, 5));
            $faker = Factory::create('fa_IR');
            $provinces = Province::getFromCache();
//        $variables = Variable::getFromCache();
            $options = Option::getFromCache();

            foreach ($users as $user) {
                for ($i = 0; $i < rand(1, 3); $i++) {
                    $province = $provinces->random()->first();

                    $home = $user->homes()->create([
                        'status' => Arr::random(array_keys(Home::STATUSES)),
                        'name' => $faker->realText(50),
                        'province_id' => $province->id,
                        'city_id' => $province->cities->random()->first()->id,
                        'address' => $faker->address,
                        'latitude' => $faker->latitude,
                        'longitude' => $faker->longitude,
                        'description' => $faker->realText(500),
                        'rules' => $faker->realText(500),
                        'yard_meter' => rand(100, 1000),
                        'infrastructure_meter' => rand(100, 600),
                        'main_guest' => rand(3, 10),
                        'extra_guest' => rand(0, 4),
                        'atmosphere' => Arr::random(array_keys(Home::ATMOSPHERES)),
                        'type' => Arr::random(array_keys(Home::TYPES)),
                        'area' => Arr::random(array_keys(Home::AREAS)),
                        'week_price' => rand(1000000, 2000000),
                        'wed_price' => rand(2000000, 2500000),
                        'thu_price' => rand(3000000, 5000000),
                        'fri_price' => rand(2000000, 3000000),
                        'price_per_surplus' => rand(100000, 500000),
                        'is_draft' => false
                    ]);

//                $file = $faker->image(null, 2200, 950);
//                $file = new UploadedFile($file, basename($file));
//                $home->updateCover($file);

//                for ($x = 0; $x < rand(1, 6); $x++){
//                    $file = $faker->image(null, 2200, 950);
//                    $file = new UploadedFile($file, basename($file));
//                    $home->addImage($file);
//                }

//                foreach ($variables as $variable){
//                    $home->variables()->create(['variable_id' => $variable->id, 'option_id' => $variable->options->random()->id]);
//                }

                    $home->options()->attach($options->random(1, $options->count()));
                }
            }
        }
    }
}
