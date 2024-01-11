<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB::table('admins')->where('email', '=', 'admin@admin.com')->first();
        if ($admin === null) {
            Admin::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('navi2213'),
            ]);
        }
    }
}
