<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $permissions = Permission::all();

        Role::create([
            'name' => 'super-admin',
            'fa_name' => 'مدیر سیستم',
        ])->syncPermissions($permissions);

        for ($i = 0; $i < rand(2, 10); $i++){
            Role::create(['name' => Str::random(), 'fa_name' => $faker->name])->syncPermissions($permissions->random(rand(1, $permissions->count())));
        }
    }
}
