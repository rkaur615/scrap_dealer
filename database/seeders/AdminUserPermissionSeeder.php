<?php

namespace Database\Seeders;

use App\Models\AdminUserPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'admin_role_id'=> '1',
                'permissions'=> json_encode(["general_settings"=>["view", "update", "delete"],"user_management"=>["view", "add", "edit", "delete"]])
            ]
        ];

        foreach ($permissions as $permission) {
            $sectionExist = AdminUserPermission::where('admin_role_id', $permission['admin_role_id'])->first();
            if ($sectionExist === null) {
                AdminUserPermission::create($permission);
            }
        }
    }
}
