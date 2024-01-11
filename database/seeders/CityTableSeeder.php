<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * It will save the cities data for Indian states only
     *
     * @return void
     */
    public function run()
    {
        // $client = new \GuzzleHttp\Client(['headers' => ['X-CSCAPI-KEY' => 'U2dHRzlKeE9Pc0VaZ1F6b3ptN1JlaEM3RGhiRlpFWDNmSzRYeFdnWg==']]);
        // $result = $client->request('GET', 'https://api.countrystatecity.in/v1/countries/GR/states/');
        // $resultData =  $result->getBody()->getContents();
        // $statesArray = json_decode($resultData, true);
        $statesArray = State::where(['country_id'=>85])->get()->toArray();
        $states = [];
        $client = new \GuzzleHttp\Client(['headers' => ['X-CSCAPI-KEY' => 'U2dHRzlKeE9Pc0VaZ1F6b3ptN1JlaEM3RGhiRlpFWDNmSzRYeFdnWg==']]);
        $cities = [];
        $sresult = $client->request('GET', 'https://api.countrystatecity.in/v1/countries/GR/states');
        $sresultData =  $sresult->getBody()->getContents();
        $statesApiArray = json_decode($sresultData, true);
        foreach ($statesArray as $statekey => $state) {
            $cityObj = collect($statesApiArray)->filter(function($item)use($state){return $item['name']==$state['name'];});
            if(!$cityObj) continue;
            // dump($cityObj->first());
            $iso = $cityObj->first()['iso2'];
            
            //->first()->iso2;
            $result = $client->request('GET', 'https://api.countrystatecity.in/v1/countries/GR/states/'.$iso.'/cities');
            $resultData =  $result->getBody()->getContents();
            // dd($resultData);
            $citiesArray = json_decode($resultData, true);
            // $stateid = $statekey+1;
            $stateid = $state['id'];
            
            if(count($citiesArray)){
                foreach ($citiesArray as $key => $city) {
                    $cities[$city['id']]['name'] = $city['name'];
                    $cities[$city['id']]['country_id'] = '85';
                    $cities[$city['id']]['state_id'] = $stateid;
                    $cities[$city['id']]['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                    $cities[$city['id']]['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
                }
            }
            
                   
        }
        $citycollection = collect($cities)->unique()->toArray();
            // map(function ($array) {
            //     return collect($array)->unique('name')->all();
            // })->toArray();
            //dd($citycollection);
        DB::table('cities')->insert($citycollection);     
    }
}
