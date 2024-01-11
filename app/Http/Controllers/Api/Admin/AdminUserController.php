<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\ChangeUserPasswordRequest;
use App\Http\Requests\Admin\CreateAdminRequest;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Http\Requests\Admin\UpdateUserDeatilsRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\Admin\CreateAdminResource;
use App\Http\Resources\Admin\LoginResource;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Requirement;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{

    public function getUser() :Admin
    {
        return auth()->guard('admin')->user();
    }

    public function adminLogin(AdminLoginRequest $request)
    {
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $data = auth()->guard('admin')->user();
            if ($data->is_block == 1) {
                return response()->json(['message'=>__('apiMessage.accountDeActive')], 401);
            }
            $data['token'] = $this->getUser()->createToken('SPA')->plainTextToken;
            return new LoginResource($data);
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 401);
        }
    }

    public function adminLogout(Request $request) {

        /** @var \Laravel\Sanctum\PersonalAccessToken $token */

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => __('apiMessage.logOut')
        ]);
    }

    public function deleteUser($id)
    {
        User::where('id', $id)->delete();
        return response()->json(['message'=>__('apiMessage.deleteUser')], 200);
    }

    public function blockUser($id)
    {
        User::where('id', $id)->update(['is_block' => 1]);
        return response()->json(['message'=>__('apiMessage.blockUser')], 200);
    }

    public function unBlockUser($id)
    {
        User::where('id', $id)->update(['is_block' => 0]);
        return response()->json(['message'=>__('apiMessage.unBlockUser')], 200);
    }

    public function markPro($id)
    {
        User::where('id', $id)->update(['is_pro' => 1]);
        return response()->json(['message'=>__('apiMessage.markedPro')], 200);
    }

    public function markNormal($id)
    {
        User::where('id', $id)->update(['is_pro' => 0]);
        return response()->json(['message'=>__('apiMessage.markedNormal')], 200);
    }

    public function getUserList() {
        $data = User::all();
        return response()->json($data, 200);
    }

    public function changePassword(UpdatePasswordRequest $request) {
        $admin = auth()->user();
        $adminPassword = $admin->password;
        if (!Hash::check($request->current_password, $adminPassword)) {
            return response()->json(['error' => __('apiMessage.passwordNotMatch')], 401);
        }
        $admin->password = Hash::make($request->password);
        $admin->save();
        return response()->json(['message'=>__('apiMessage.updatePassword')], 200);
    }

    public function adminDetail(Request $request) {
        return new AdminResource(auth()->user());
    }

    public function updateDetails(UpdateUserDeatilsRequest $request, $userId) {
        $data = Admin::where('id', $userId)->update($request->validated());
        if ($data) {
            return response()->json(['message'=>__('apiMessage.updateDetails')], 200);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    public function getAllUsers(Request $request){
        return User::paginate();
    }

    public function getUserData(Request $request, $user){
        $user = User::where('id', $user)->with(['types'=>function($q) use($user) {
            $q->where('user_id', $user);
        }, 'types.company'=>function($q) use($user) {
            $q->where('user_id', $user);
        }, 'types.company.uploads' => function($q) {
            $q->where('filetype', '!=', config('constants.fileTypes.serviceRequestPhoto'))
            ->where('filetype', '!=', config('constants.fileTypes.productPhoto'));
        }, 'types.company.country', 'types.company.state', 'types.company.city', 'types.company.references', 'types.company.categories','types.company.categories.category','types.company.categories.subcategory','addresses' ])->first();
        return response()->json($user);
    }

    public function getAllUsersByType(Request $request, $type){
        return UserResource::collection(User::query()->{$type}()->orderBy('id', 'desc')->paginate());
    }

    public function createAdmin(CreateAdminRequest $request)
    {
        $admin = $request->validated();
        $admin['password'] = bcrypt($request->password);
        $data = Admin::create($admin);
        if ($data) {
            return new CreateAdminResource($data);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }

    public function getAllAdminList($role='ALL')
    {
        $admins = null;
        switch ($role) {
            case 'ALL':
                $admins = Admin::paginate(10);
                break;
            case 'IA':
                $admins = Admin::where('role_id',AdminRole::where('name', 'Inspection Agent')->first()->id)->paginate(100);
                break;
            case 'DA':
                $admins = Admin::where('role_id',AdminRole::where('name', 'Delivery Agent')->first()->id)->paginate(100);
                break;

            default:
                # code...
                break;
        }
        return AdminResource::collection($admins);
    }

    public function getUserDetail(Admin $admin)
    {
        return new AdminResource($admin->load('role:id,name'));
    }

    public function blockUnblockAdminUser(Admin $admin)
    {
        $admin->update([
            "is_block" => !$admin->is_block
        ]);
        if ($admin->is_block) {
            return response()->json(['message'=>__('apiMessage.accountDeActive')], 200);
        } else {
            return response()->json(['message' => __('apiMessage.accountActive')], 200);
        }
    }

    public function deleteAdminUser(Admin $admin)
    {
        $admin->delete();
        return response()->json(['message'=>__('apiMessage.deleteUser')], 200);
    }

    public function changeUserPassword(ChangeUserPasswordRequest $request, Admin $admin)
    {
        $admin->update(['password' => bcrypt($request->password)]);
        return response()->json(['message'=>__('apiMessage.updatePassword')], 200);
    }

    public function getAllUsersByTypes(Request $request)
    {
        $userTypes = $request->types ?? [];
        $data = UserRole::with('user','role:id,title')->whereIn('user_type_id', $userTypes)->paginate();
        return UserResource::collection($data);
    }

}


