<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use App\Http\Resources\StateCollection;
use App\Http\Requests\StateRequest;

class StateController extends Controller
{
    public function getStates(StateRequest $request)
    {
        $country_id = $request->country_id;
        $states = State::where('country_id', $country_id)->orderBy('name')->get();
        return new StateCollection($states);
    }
}
