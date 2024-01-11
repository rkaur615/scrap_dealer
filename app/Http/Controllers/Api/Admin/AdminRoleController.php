<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddAdminRoleRequest;
use App\Http\Resources\Admin\GetAdminroleDetailResource;
use App\Http\Resources\Admin\GetAdminrolesResource;
use App\Models\AdminRole;
use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = AdminRole::with('permission')->paginate(10);
        return GetAdminrolesResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddAdminRoleRequest $request)
    {
        $role = AdminRole::create($request->validated());
        if ($role) {
            return new GetAdminrolesResource($role);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AdminRole $role)
    {
        $role->permission;
        if ($role) {
            return new GetAdminrolesResource($role);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddAdminRoleRequest $request, AdminRole $role)
    {
        $role->update($request->validated());
        return response()->json(['message' => __('apiMessage.updateAdminRole')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminRole $role)
    {
        $role->delete();
        return response()->json(['message' => __('apiMessage.deleteAdminRole')]);
    }
}
