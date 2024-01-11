<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Http\Requests\SaveResponseRequest;
use App\Http\Resources\ResponseCollection;
use App\Models\ProductBidding;
use DB;

class ResponseController extends Controller
{
    /*public function getMyResponses(Request $request)
    {
        $filterBy = $request->filterBy ?? '';
        $user = auth()->user();
        if (!empty($user)) {
            if (!empty($filterBy)) {
                $responses = Response::where([
                    ['user_id', '=', $user->id],
                ])
                ->with(['requests' => function($q) use ($filterBy){
                    $q->where('type', '=', $filterBy);
                }])->get();
            } else {
                $responses = Response::where([
                    ['user_id', '=', $user->id],
                ])
                ->with('services')->get();
            }
            foreach ($responses as $datakey => $response) {
                foreach (config('constants.responseStatus') as $status => $val) {
                    if ($responses[$datakey]['status'] == $val) {
                        $responses[$datakey]['status'] = $status;
                    }
                }
                if (!empty($response['requests']['uploads'])) {
                    foreach ($response['requests']['uploads'] as $fileKey => $uploaddata) {
                        $responses[$datakey]['requests']['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                        foreach (config('constants.fileTypes') as $filetype => $value) {
                            if ($uploaddata['filetype'] == $value) {
                                $responses[$datakey]['requests']['uploads'][$fileKey]['filetype'] = $filetype;
                            }
                        }
                    }
                }
            }
            return new ResponseCollection($responses);
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }

    }*/

    public function getMyResponses(Request $request)
    {
        $filterBy = $request->filterBy ?? '';
        $user = auth()->user();
        $myResponses = [];
        if (!empty($user)) {
            if (!empty($filterBy)) {
                if ($filterBy == 'product') {
                    $productRequestResponses = $this->getProductRequestResponses();
                    $myResponses['productResponses'] = $productRequestResponses;
                } else {
                    $serviceRequestResponses = $this->getServiceRequestResponses();
                    $myResponses['serviceResponses'] = $serviceRequestResponses;
                }
            } else {
                $serviceRequestResponses = $this->getServiceRequestResponses();
                $productRequestResponses = $this->getProductRequestResponses();
                $myResponses['serviceResponses'] = $serviceRequestResponses;
                $myResponses['productResponses'] = $productRequestResponses;
                return new ResponseCollection($myResponses);
            }
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }
    }

    public function getServiceRequestResponses()
    {
        $user = auth()->user();
        $responses = Response::where([
            ['user_id', '=', $user->id],
            ['type', '=', 'service'],
        ])
        ->with('services')->latest()->get();
        foreach ($responses as $datakey => $response) {
            foreach (config('constants.responseStatus') as $status => $val) {
                if ($responses[$datakey]['status'] == $val) {
                    $responses[$datakey]['status'] = $val; //As per Anil Request
                    //$responses[$datakey]['status'] = $status;
                }
            }
            if (!empty($response['services']['uploads'])) {
                foreach ($response['services']['uploads'] as $fileKey => $uploaddata) {
                    $responses[$datakey]['services']['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                    foreach (config('constants.fileTypes') as $filetype => $value) {
                        if ($uploaddata['filetype'] == $value) {
                            $responses[$datakey]['services']['uploads'][$fileKey]['filetype'] = $filetype;
                        }
                    }
                }
            }
        }
        return $responses;
    }

    public function getProductRequestResponses()
    {
        $user = auth()->user();
        $responses = Response::where([
            ['user_id', '=', $user->id],
            ['type', '=', 'product'],
        ])
        ->with('products')->latest()->get()->toArray();
        $bids = ProductBidding::where('added_by', $user->id)->with('products')->latest()->get()->toArray();
        $responses = array_merge($responses, $bids);
        foreach ($responses as $datakey => $response) {
            foreach (config('constants.responseStatus') as $status => $val) {
                if ($responses[$datakey]['status'] == $val) {
                    $responses[$datakey]['status'] = $val; //As
                    //$responses[$datakey]['status'] = $status;
                }
            }
            if (!empty($response['products']['uploads'])) {
                foreach ($response['products']['uploads'] as $fileKey => $uploaddata) {
                    $responses[$datakey]['products']['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                    foreach (config('constants.fileTypes') as $filetype => $value) {
                        if ($uploaddata['filetype'] == $value) {
                            $responses[$datakey]['products']['uploads'][$fileKey]['filetype'] = $filetype;
                        }
                    }
                }
            }
        }
        return $responses;
    }
    public function saveResponse(SaveResponseRequest $request)
    {
        $user = auth()->user();
        if (!empty($user)) {
            $responseSaved = Response::create([
                'request_id' => $request->request_id,
                'description' => $request->description,
                'status' => $request->status,
                'type' => $request->type,
                'user_id' => $user->id
            ]);
            if ($responseSaved) {
                return response()->json([
                    'message'=>__('apiMessage.responseSubmitted'),
                    'success' => true
                ], 200);
            } else {
                return response()->json([
                    'error' => __('apiMessage.errorMsg'),
                    'success' => false
                ], 400);
            }
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }

    }
}
