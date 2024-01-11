<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBankDetailRequest;
use App\Http\Requests\StoreUserBankDetailRequest;
use App\Http\Resources\GetBankDetailResource;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;

class BankDetailController extends Controller
{
    public function store(StoreBankDetailRequest $request)
    {
        if (!empty(auth()->user()->bankDetail)) {
            return response()->json([
                'message' => __('apiMessage.bankDetailExist'),
                'errors' => 'true'
            ], 422);
        } else {
            try{
                auth()->user()->bankDetail()->create($request->validated());
            }
            catch(Exception $e){
                return response()->json([
                    'message' => __($e->getMessage()),
                    'errors' => 'true'
                ], 422);
            }

            return response()->json([
                'message' => __('apiMessage.saveBankDetail'),
                'status' => 'success'
            ]);
        }
    }

    public function storesUserDetails(StoreUserBankDetailRequest $request)
    {
        $user = Admin::find($request->user_id);
        if (!empty($user->bankDetail)) {
            return response()->json([
                'message' => __('apiMessage.bankDetailExist'),
                'errors' => 'true'
            ], 422);
        } else {
            try{
                $user->bankDetail()->create($request->validated());
                return response()->json([
                    'message' => __('apiMessage.saveBankDetail'),
                    'status' => 'success'
                ]);
            }
            catch(Exception $e){
                return response()->json([
                    'message' => __($e->getMessage()),
                    'errors' => 'true'
                ], 422);
            }

        }
    }

    public function update(StoreBankDetailRequest $request)
    {
        if (!empty(auth()->user()->bankDetail)) {
            auth()->user()->bankDetail()->update($request->validated());
            return response()->json([
                'message' => __('apiMessage.updateBankDetail'),
                'status' => 'success'
            ]);
        }
        return response()->json([
            'message' => __('apiMessage.notExist'),
            'errors' => 'true'
        ], 404);
    }

    public function updateUserDetails(StoreUserBankDetailRequest $request)
    {
        $user = Admin::find($request->user_id);
        if (!empty($user->bankDetail)) {
            $user->bankDetail->update($request->validated());
            return response()->json([
                'message' => __('apiMessage.updateBankDetail'),
                'status' => 'success'
            ]);
        }
        return response()->json([
            'message' => __('apiMessage.notExist'),
            'errors' => 'true'
        ], 404);
    }

    public function show(Request $request)
    {
        if (!empty($request->user_id)) {
            $user = Admin::find($request->user_id);
            if (!empty($user->bankDetail)) {
                return new GetBankDetailResource($user->bankDetail);
            } else {
                return response()->json([
                    'message' => __('apiMessage.notExist'),
                    'errors' => 'true'
                ], 404);
            }
        } else {
            return new GetBankDetailResource(auth()->user()->bankDetail);
        }
    }
}
