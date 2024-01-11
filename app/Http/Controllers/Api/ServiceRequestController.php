<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use App\Models\Upload;
use App\Models\Product;
use App\Models\CategoryFormData;
use App\Models\Response;
use App\Models\UserAddress;
use App\Models\UserCompany;
use App\Models\UserCategory;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SaveServiceRequest;
use App\Http\Resources\MyRequestsResource;
use App\Http\Resources\ResponseCollection;
use App\Http\Resources\ServiceRequestResource;
use App\Http\Resources\ServiceRequestMobileResource;
use App\Models\UserSubscription;

class ServiceRequestController extends Controller
{
    public function getMyRequests(Request $request)
    {
        $user = auth()->user();
        $filterBy = $request->filterBy ?? '';
        $myRequests = [];
        if (!empty($user)) {
            if (!empty($filterBy)) {
                if ($filterBy == 'product') {
                    $products = $this->getMyProductRequests();
                    $myRequests['products'] = $products;
                } else {
                    $services = $this->getMyServiceRequests();
                    $myRequests['services'] = $services;
                }
            } else {
                $services = $this->getMyServiceRequests();
                $products = $this->getMyProductRequests();
                $myRequests['services'] = $services;
                $myRequests['products'] = $products;
            }
            return MyRequestsResource::collection($myRequests);
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }
    }

    public function getMyProductRequests()
    {
        $user = auth()->user();
        $products = Product::select(['id', 'title', 'description', 'price', 'user_id', 'sale_option_id', 'status'])
        ->with(['uploads' => function($q) {
            $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
        }])->where([
            ['user_id', '=', $user->id],
        ])->latest()->get();
        foreach ($products as $datakey => $product) {
            if (!empty($product['uploads'])) {
                foreach ($product['uploads'] as $fileKey => $uploaddata) {
                    $products[$datakey]['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                }
            }
        }
        return $products;
    }

    public function getMyServiceRequests()
    {
        $user = auth()->user();
        $serviceRequests = ServiceRequest::select(['id', 'title', 'description', 'user_id', 'status'])
        ->with(['uploads' => function($q) {
            $q->where('filetype', '=', config('constants.fileTypes.serviceRequestPhoto'));
        }])
        ->where([
            ['user_id', '=', $user->id],
        ])->latest()->get();
        foreach ($serviceRequests as $datakey => $request) {
            if (!empty($request['uploads'])) {
                foreach ($request['uploads'] as $fileKey => $uploaddata) {
                    $serviceRequests[$datakey]['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                }
            }
        }
        return $serviceRequests;
    }


    public function requestService(SaveServiceRequest $request)
    {
        $user = auth()->user();
        $serviceRequest = new ServiceRequest();
        $serviceRequest->user_id = $user->id;
        $serviceRequest->title = $request->title;
        $serviceRequest->description = $request->description;
        $serviceRequest->address_id = $request->address_id;
        $serviceRequest->check_proximity = isset($request->check_proximity) ? $request->check_proximity : 0;
        $serviceRequest->distance = isset($request->distance) ? $request->distance : 0;
        $serviceRequest->old_service_provider_id = isset($request->old_service_provider_id) ? $request->old_service_provider_id : null;
        $serviceRequest->time_slots = $request->time_slots;
        $serviceRequest->category_id = $request->category_id;
        $serviceRequest->subcategory_id = $request->subcategory_id;
        $serviceRequest->type = isset($request->type) ? $request->type : 'service';
        $result = $serviceRequest->save();
        if ($request->hasFile('photo')) {
            $uploadedphotos = $request->file('photo');
            foreach($uploadedphotos as $key => $photo){
                $uploadFolder = 'serviceRequestPhotos';
                $profileUploadedPath = $photo->store($uploadFolder, 'public');
                $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                $input['filename'] = $photo->getClientOriginalName();
                Upload::create([
                    'filename' => $input['filename'],
                    'filepath' => $input['path'],
                    'filetype'=> config('constants.fileTypes.serviceRequestPhoto'),
                    'ref_id' => $serviceRequest->id,
                ]);
            }
        }
        $custom_data = $request->custom_data ?? '';
        $customArray = json_decode($custom_data, true);
        if ($request->hasFile('custom_file')) {
            $custom_files = $request->file('custom_file');
            foreach ($custom_files as $fieldname => $custom_file) {
                $uploadFolder = 'customFormFiles';
                $profileUploadedPath = $custom_file->store($uploadFolder, 'public');
                $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                $input['filename'] = $custom_file->getClientOriginalName();
                $input['fieldname'] = $fieldname;
                foreach ($customArray as $key => $customData) {
                    if ($customData['type'] == 'file' && $customData['isAttachment'] == true) {
                            if ($input['fieldname'] == $customData['name']) {
                            $customArray[$key]['filepath'] = $input['path'];
                        }
                    }
                }
            }
        }

        if (!empty($customArray)) {
            CategoryFormData::create([
                'user_id' => $user->id,
                'category_dynamic_form_id' => $request->category_dynamic_form_id,
                'data' => json_encode($customArray),
                'type_id' => $serviceRequest->id,
                'type' => 'service'
            ]);
        }
        if ($result) {
            return response()->json(['message' => __('apiMessage.ServiceRequestAdded')]);
        } else {
            return response()->json(['message' => __('apiMessage.errorMsg')]);

        }
    }

    public function list(Request $request){
        $services = ServiceRequest::with(['user'])->paginate();
        return ServiceRequestResource::collection($services);
    }

    public function show(ServiceRequest $servicerequest){
        $services = $servicerequest->load(['user', 'category', 'subcategory', 'subcategory.form', 'address', 'address.city', 'address.state', 'address.country', 'formdata', 'uploads' => function($q) {
            $q->where('filetype', '=', config('constants.fileTypes.serviceRequestPhoto'));
        }, 'responses']);
        return new ServiceRequestResource($services);
    }


    public function updateStatus(Request $request, ServiceRequest $servicerequest){
        $method = $request->method();
        $idUpdated = match($method){
            'GET'   =>  $servicerequest->update(['status'=>config('constants.responseStatus.accepted')]),
            'POST'   =>  $servicerequest->update(['status'=>config('constants.responseStatus.rejected'), 'status_reason' => $request->reason])
        };
        if ($idUpdated) {
            $msg = match($method){
                'GET'   =>  __('apiMessage.serviceApproved'),
                'POST'   =>  __('apiMessage.serviceRejected')
            };
            return response()->json(['message' => $msg, 'id'=>$idUpdated, 'status'=>config('constants.responseStatus.accepted') ]);
        } else {
            return response()->json(['message' => __('apiMessage.errorMsg')]);

        }
    }

    public function autoAssignServiceProvider()
    {
        $allServiceRequest = ServiceRequest::where('status', 1)->get();
        foreach ($allServiceRequest as $serviceRequest) {
            if (empty($serviceRequest['address_id'])) {
                continue;
            }

            $requesterLocation = UserAddress::where('id', $serviceRequest['address_id'])
                ->select('latitude', 'longitude', 'city_id')->first();

            $response = Response::where('request_id', $serviceRequest['id'])
                ->orderBy('created_at', 'desc')->first();

            if (empty($response) || $response->created_at < Carbon::now()->subHour(1)) {
                $assignedUsers = Response::where('request_id', $serviceRequest['id'])->pluck('user_id');
                $serviceProviders = DB::table('user_companies')
                    ->join('user_categories', 'user_categories.user_company_id', '=', 'user_companies.id')
                    ->join('user_subscriptions', 'user_subscriptions.user_id', '=', 'user_companies.user_id')
                    ->join('operational_areas', 'operational_areas.user_company_id', '=', 'user_companies.id')
                    ->where('user_categories.category_id',  $serviceRequest['category_id'])
                    ->where('user_categories.subcategory_id', $serviceRequest['subcategory_id'])
                    ->where('operational_areas.city_id', $requesterLocation->city_id)
                    ->where('user_subscriptions.no_of_leads', '>=', 1)
                    ->whereNotIn('user_companies.user_id', $assignedUsers)
                    ->select('user_companies.id', 'user_subscriptions.no_of_leads')
                    ->get()->toArray();

                if (empty($serviceProviders)) {
                    continue;
                }

                $availableServiceProviders = DB::table("user_companies")
                    ->select("user_companies.user_id"
                        ,DB::raw("6371 * acos(cos(radians(" . $requesterLocation->latitude . "))
                        * cos(radians(user_companies.latitude))
                        * cos(radians(user_companies.longitude) - radians(" . $requesterLocation->longitude . "))
                        + sin(radians(" .$requesterLocation->latitude. "))
                        * sin(radians(user_companies.latitude))) AS distance")
                    )->whereIn('id', array_column($serviceProviders, 'id'))
                    ->where('city_id', $requesterLocation->city_id)
                    ->groupBy("user_companies.id")
                    ->orderBy('distance')
                    ->take(2)
                    ->get();

                $allProviders = [];
                if ($serviceRequest['check_proximity'] == 1) {
                    $mile = 0.621371;
                    $totalmiles = $serviceRequest['distance'] * $mile;
                    foreach ($availableServiceProviders as $serviceProvider) {
                        if ($totalmiles >= $serviceProvider->distance) {
                            array_push($allProviders, $serviceProvider->user_id);
                        }
                    }
                } else {
                    foreach ($availableServiceProviders as $serviceProvider) {
                        array_push($allProviders, $serviceProvider->user_id);
                    }
                }

                if (empty($allProviders)) {
                    continue;
                }

                foreach ($allProviders as $userId) {
                    $currentPlan = UserSubscription::where('user_id', $userId)->first();
                    $currentPlan->update([
                        'no_of_leads' => $currentPlan['no_of_leads'] - 1
                    ]);

                    Response::create([
                        'request_id' => $serviceRequest['id'],
                        'user_id' => $userId,
                        'status' => config('constants.responseStatus.pending'),
                        'type' => 'Service',
                    ]);
                }
            }
        }
    }

    public function getAssignedServiceProvider(Request $request)
    {
        if (!empty($request->service_id)) {
            $Provider = Response::with('provider_name:id,name,email,phone_number')
            ->where([
                ['request_id', '=', $request->service_id],
                ['type', '=', 'service'],
            ])
            ->get();
            if (!empty($Provider)) {
               return new ResponseCollection($Provider);
            }
            else {
                return response()->json(['message' => __('apiMessage.providerAssign')]);
            }
        }
    }

    public function getServiceRequestOverview(Request $request)
    {
        $service_request_id = $request->service_request_id;
        if (!empty($service_request_id)) {
            $service_request = ServiceRequest::where('id', $service_request_id)->get();
            if (!$service_request->isEmpty()) {
                return ServiceRequestMobileResource::collection($service_request);
            }
            else {
                return response()->json([
                    'message'=>__('apiMessage.serviceRequestNotExist'),
                ]);
            }
        }
        else {
            return response()->json([
               'message'=>__('apiMessage.errorMsg'),
            ]);
        }
    }
    public function updateRequestStatus(Request $request)
    {
        $service_request_id = $request->service_id;
        $status = $request->status;
        $status_reason = $request->status_reason;
        $service_request = ServiceRequest::where('id', $service_request_id)->get();
        if (empty($service_request)) {
            return response()->json(['message' => __('apiMessage.serviceRequestNotExist')]);
        } else {
            $service_request = ServiceRequest::where('id', $service_request_id)->first();
            $service_request->status = $status;
            $service_request->status_reason = $status_reason;
            $service_request->save();

            return response()->json(['message' => __('apiMessage.serviceRequestStatusUpdated')]);
        }
    }

}
