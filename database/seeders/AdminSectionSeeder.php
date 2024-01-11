<?php

namespace Database\Seeders;

use App\Models\AdminSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            [
                'name' => 'user management',
                'key'=> 'user_management',
                'permission'=> json_encode([
                    "view",
                    "add",
                    "edit",
                    "delete",
                ])
            ],
            [
                'name' => 'general settings',
                'key'=> 'general_settings',
                'permission'=> json_encode([
                    "view",
                    "update",
                    "delete",
                ])
            ]
        ];

        foreach ($sections as $section) {
            $sectionExist = AdminSection::where('name', $section['name'])->first();
            if ($sectionExist === null) {
                AdminSection::create($section);
            }
        }
    }
}
