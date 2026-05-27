<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create('fa_IR');

        User::query()->create([
            'first_name' => 'علی',
            'last_name' => 'خدائی',
            'mobile' => '09306986655',
            'mobile_verified_at' => now(),
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => true
        ])->assignRole('super-admin');

//        for ($i = 0; $i < rand(10, 20); $i++){
//            $role = Role::query()->where('name', '!=', 'super-admin')
//                ->inRandomOrder()->first();
//
//            User::query()->create([
//                'first_name' => $faker->firstName,
//                'last_name' => $faker->lastName,
//                'mobile' => $faker->phoneNumber,
//                'mobile_verified_at' => now(),
//                'email' => $faker->email,
//                'email_verified_at' => now(),
//                'password' => Hash::make('password'),
//                'is_admin' => true
//            ])->assignRole($role->name);
//        }
    }
}
