<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\UserTypeSeeder;
use Database\Seeders\CategoryTableSeeder;
use Database\Seeders\CountryTableSeeder;
use Database\Seeders\StateTableSeeder;
use Database\Seeders\CityTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(UserTypeSeeder::class);
        // //$this->call(CategoryTableSeeder::class);
        //$this->call(CountryTableSeeder::class);
        //$this->call(StateTableSeeder::class);
        //$this->call(CityTableSeeder::class);
            // $this->call(GeneralSettingSeeder::class);
            // $this->call(AdminRolesSeeder::class);
            // $this->call(AdminSectionSeeder::class);
            // // $this->call(AdminUserSeeder::class);
            // $this->call(AdminUserPermissionSeeder::class);
    }
}
