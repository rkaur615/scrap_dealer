<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Http\Resources\OrderSummaryResource;
use App\Http\Requests\SaveOrderRequest;
use DB;

class OrderController extends Controller
{
    public function saveOrder(SaveOrderRequest $request)
    {
        $user = auth()->user();
        if (!empty($user)) {            
            $orderCreated = Order::create($request->validated() + ['user_id' => $user->id]);
            if ($orderCreated) {
                return response()->json([
                    'order_id' => $orderCreated['id'],
                    'message' => __('apiMessage.orderSubmitted'),
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

    public function getOrderSummary(Request $request)
    {
        $order_id = $request->order_id;
        $data = Order::with(['address', 'product'])
        ->where('id', $order_id)
        ->get();
        /*$data = DB::table('orders')
        ->join('products','products.id','=','orders.product_id')
        ->join('user_addresses','user_addresses.id','=','orders.address_id')
        ->leftJoin('cities', 'cities.id', '=', 'user_addresses.city_id')
        ->leftJoin('states', 'states.id', '=', 'user_addresses.state_id')
        ->leftJoin('countries', 'countries.id', '=', 'user_addresses.country_id')
        ->where([
            ['orders.id', '=', $order_id],
        ])
        ->get(['orders.id','products.title','products.price','products.description', 
         'user_addresses.address','cities.name AS city_name','states.name As state_name',
         'countries.name As country_name'
        ]);*/
        if (!empty($data)){
          return new OrderSummaryResource($data);
        }
        else{
            return response()->json(['message' => __('apiMessage.notExist')]);
        }
    } 
}

       



