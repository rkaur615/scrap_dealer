<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use App\Http\Resources\SubscriptionResource;
use App\Http\Requests\UserSubscriptionRequest;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionPlanController extends Controller
{
    public function getAllSubscriptions(Request $request)
    {   $user = auth()->user();
        if (!empty($user)) {
            return SubscriptionResource::collection(SubscriptionPlan::all());
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }
        
    }

    public function submitUserSubscription(UserSubscriptionRequest $request)
   {
        $user = auth()->user();
        if (!empty($user)) {
            $result = UserSubscription::create($request->validated() + ['user_id' => $user->id]);
            if ($result) {
                return response()->json([
                    'message' => __('apiMessage.createUserSubscription'),
                    'status' => 'success'
                ]);
            } else {
                return response()->json(['message' => __('apiMessage.errorMsg')]);
            }
            
        } else {
            return response()->json([
                'message'=>__('apiMessage.unauthorized'),
                'status'=> 'error'
            ]);
        }
    }   
}