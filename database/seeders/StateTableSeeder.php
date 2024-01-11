<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * It will save the states data for India only
     *
     * @return void
     */
    public function run()
    {
        $client = new \GuzzleHttp\Client(['headers' => ['X-CSCAPI-KEY' => 'U2dHRzlKeE9Pc0VaZ1F6b3ptN1JlaEM3RGhiRlpFWDNmSzRYeFdnWg==']]);
        $result = $client->request('GET', 'https://api.countrystatecity.in/v1/countries/GR/states/');
        $resultData =  $result->getBody()->getContents();
        $statesArray = json_decode($resultData, true);
        $states = [];
        foreach ($statesArray as $key => $item) {
            $states[$key]['name'] = $item['name'];
            $states[$key]['country_id'] = '85';
            $states[$key]['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $states[$key]['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
            
        }
        DB::table('states')->insert($states);
    }
}
