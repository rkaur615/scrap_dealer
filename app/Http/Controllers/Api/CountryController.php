<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Http\Resources\CountryCollection;

class CountryController extends Controller
{
    public function getCountries(Request $request)
    {
        return new CountryCollection(Country::all());
    }

    public function searchCountries(Request $request)
    {
        $q = $request->get('q');
        // return response()->json($q);
        return new CountryCollection(Country::where('name','LIKE','%'.$q.'%')->get());
    }
}
