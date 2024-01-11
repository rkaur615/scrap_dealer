<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\GetMultipleCityRequest;
use App\Models\City;
use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use Illuminate\Support\Arr;


class CityController extends Controller
{
    public function getCities(Request $request)
    {
        $country_id = $request->country_id;
        $state_id = $request->state_id;
        $cities = City::where([
            ['country_id', '=', $country_id],
            ['state_id', '=', $state_id],
        ])->orderBy('name')->get();
        return new CityCollection($cities);
    }

    public function getMultipleCities(GetMultipleCityRequest $request)
    {
        $states = json_decode($request->state_ids);
        $stateIds = array_column($states, 'state_id');
        $allCity = City::whereIn('state_id', $stateIds)->orderBy('name')->get();
        return CityResource::collection($allCity);
    }
}
