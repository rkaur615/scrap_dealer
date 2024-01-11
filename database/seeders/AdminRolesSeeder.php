<?php

namespace Database\Seeders;

use App\Models\AdminRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['name' => 'admin'],
        ];

        foreach ($roles as $role) {
            $roleExist = AdminRole::where('name', $role['name'])->first();
            if ($roleExist === null) {
                AdminRole::create($role);
            }
        }
    }
}
