<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalSetting = [
            ['title' => 'site_title', 'field_type'=> 1],
            //['title' => 'tag_line', 'field_type'=> 6],
            ['title' => 'email_address', 'field_type'=> 2],
            ['title' => 'contact_us', 'field_type'=> 4],
            //['title' => 'site_logo', 'field_type'=> 3],
            //['title' => 'user_registration_allowed', 'field_type'=> 5, 'value' => 1],
            //['title' => 'google_secret_key', 'field_type'=> 1],
            //['title' => 'firebase_secret_key', 'field_type'=> 1],
            ['title' => 'client_id', 'field_type'=> 1],
        ];

        foreach ($generalSetting as $setting) {
            $admin = GeneralSetting::where('title', $setting['title'])->first();
            if ($admin === null) {
                GeneralSetting::create($setting);
            }
        }
    }
}
