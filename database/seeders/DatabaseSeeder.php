<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProvincesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(AdminTableSeeder::class);
//        $this->call(ConsultantTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(ContactTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(NavbarTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ArticleTableSeeder::class);
        $this->call(CommentTableSeeder::class);
        $this->call(OptionTableSeeder::class);
        $this->call(HealthTableSeeder::class);
        $this->call(SafetyTableSeeder::class);
        $this->call(TicketTableSeeder::class);
        $this->call(VariableTableSeeder::class);
        $this->call(HomeTableSeeder::class);
        $this->call(FAQTableSeeder::class);
        $this->call(OrderTableSeeder::class);
    }
}
