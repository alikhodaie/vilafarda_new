<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
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

            for ($i = 0; $i < rand(10, 20); $i++){

                User::query()->create([
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'mobile' => $faker->phoneNumber,
                    'mobile_verified_at' => now(),
                    'email' => $faker->email,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                ]);
            }
        }
        else {
            $path = storage_path('app/members.sql');
            $users = explode('),', file_get_contents($path));
            $users = str_replace('(', '', $users);
            $users = str_replace('\'', '', $users);

            foreach($users as $user) {
                $user = explode(',', $user);

                $name = explode(' ', trim($user[5]));
                $first_name = array_shift($name);
                $last_name = '';

                foreach ($name as $index => $ls_name){
                    if (array_key_last($name) !== $index){
                        $ls_name .= ' ';
                    }
                    $last_name .= $ls_name;
                }

                $email = trim($user[2]);
                if (! $email || ! filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $email = null;
                }

                User::query()->create([
                    'first_name' => $first_name,
                    'last_name'   => $last_name,
                    'mobile'     => trim($user[4]),
                    'email'      => $email,
                    'password'   => trim($user[3])
                ]);
            }
        }
    }
}
