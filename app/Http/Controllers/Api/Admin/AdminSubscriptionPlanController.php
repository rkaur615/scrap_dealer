<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateSubscriptionRequest;
use App\Http\Requests\Admin\CreateUpdateSubscriptionRequest;
use App\Http\Resources\Admin\GetSubscriptionDetailResource;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Http\Request;

class AdminSubscriptionPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = SubscriptionPlan::orderBy('id', 'desc')->paginate(10);
        return GetSubscriptionDetailResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubscriptionRequest $request)
    {
        $plan = SubscriptionPlan::where([
            'no_of_leads'=> $request->no_of_leads,
            'days'=> $request->days,
            ])->first();

        if (!empty($plan)) {
            return response()->json([
                'message' => $plan->name . ' have same number of leads and days',
                'error' => true
            ], 402);
        }
        $data = SubscriptionPlan::create($request->validated() + ['added_by' => auth()->user()->id]);
        if ($data) {
            return response()->json([
                'message'=>__('apiMessage.createSubscription'),
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'error' => __('apiMessage.errorMsg'),
                'success' => false
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SubscriptionPlan $subscription)
    {
        $users = UserSubscription::where('subscription_plan_id', $subscription->id)->pluck('user_id');
        $subscription->users = User::whereIn('id', $users)->get();
        return new GetSubscriptionDetailResource($subscription);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateUpdateSubscriptionRequest $request, SubscriptionPlan $subscription)
    {
        $subscription->update($request->validated());
        return response()->json([
            'message' => __('apiMessage.updateSubscription'),
            'success' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPlan $subscription)
    {
        $subscription->delete();
        return response()->json([
            'message'=>__('apiMessage.deleteSubscription'),
            'success' => true
        ], 200);
    }
}
