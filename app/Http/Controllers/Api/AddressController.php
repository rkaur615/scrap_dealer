<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserAddressRequest;
use App\Http\Resources\UserAddressCollection;
use App\Models\UserCompany;

class AddressController extends Controller
{
    public function addUpdateAddress(UserAddressRequest $request)
    {
        $user = auth()->user();
        $id = isset($request->id) ? $request->id : '';
        $addressExist = UserAddress::where('id', $id)->first();
        if (empty($addressExist)) {
            $result = UserAddress::create($request->validated() + ['user_id' => $user->id]);
            if ($result) {
                return response()->json([
                    'message' => __('apiMessage.addressSubmitted'),
                    'addressId' => $result->id,
                    'status' => 'success'
                ]);
            } else {
                return response()->json([
                    'message' => __('apiMessage.unauthorized'),
                    'status' => 'error'
                ]);
            }
        } else {
            UserAddress::where('id', $id)->update($request->validated());
            return response()->json(['message' => __('apiMessage.addressUpdated')]);
        }
    }

    public function getSavedAddresses()
    {
        $user = auth()->user();
        $userAddresses = [];
        if (!empty($user)) {
            $userAddresses['userAddress'] = UserAddress::where('user_id', $user->id)->get();
            $userAddresses['companyAddress'] = UserCompany::where('user_id', $user->id)->get();
            if (!empty($userAddresses)) {
                return new UserAddressCollection($userAddresses);
            } else {
                return response()->json([
                    'message' => __('apiMessage.dataNotExist'),
                ]);
            }
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }
    }
}
