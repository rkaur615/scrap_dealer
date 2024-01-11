<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new \GuzzleHttp\Client(['headers' => ['X-CSCAPI-KEY' => 'U2dHRzlKeE9Pc0VaZ1F6b3ptN1JlaEM3RGhiRlpFWDNmSzRYeFdnWg==']]);
        $result = $client->request('GET', 'https://api.countrystatecity.in/v1/countries');
        $resultData =  $result->getBody()->getContents();
        $countriesArray = json_decode($resultData, true);
        $countries = [];

        foreach ($countriesArray as $key => $item) {
            $countries[$key]['name'] = $item['name'];
            $countries[$key]['code'] = $item['iso2'];
            $countries[$key]['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $countries[$key]['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
        DB::table('countries')->insert($countries);
    }
}
