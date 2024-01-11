<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\AdminRole;
use Illuminate\Http\Request;
use App\Models\AdminUserPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreatePermissionRequest;

class AdminUserPermissionController extends Controller
{
    public function create(CreatePermissionRequest $request)
    {
        AdminUserPermission::create($request->validated());
        return response()->json(['message' => __('apiMessage.createPermission')]);
    }

    public function update(CreatePermissionRequest $request, AdminUserPermission $id)
    {

        AdminRole::where('id', $request->admin_role_id)->update([
            'name' => $request->role_name
        ]);
        $id->update($request->validated());
        return response()->json(['message' => __('apiMessage.updatePermission')]);
    }
}
