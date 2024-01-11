<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserType;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserVerifyRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Http\Requests\UpdateDetailsRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserDetailCollection;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserTypeCollection;
use App\Http\Resources\UserRoleCollection;
use App\Models\Otp;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
class AuthController extends Controller
{

    public function sendOtp($phone, $otp)
    {

        //$sid = "AC443ff6a880eab6293dd77dc72438b9c1"; // Your Account SID from www.twilio.com/console
        //$sid = "AC443ff6a880eab6293dd77dc72438b9c1"; // Your Account SID from www.twilio.com/console
        $sid = "ACc968f3427f0f9109d6a534f17d2953bb"; // Your Account SID from www.twilio.com/console
        $token = "a8ef47308d16065f38d70c59693fe151"; // Your Auth Token from www.twilio.com/console

        $client = new Client($sid, $token);
        $message = $client->messages->create(
        '+91'.$phone, // Text this number
        [
            "from" => "+12286880514",
            //'from' => '+1 228 688 0514', // From a valid Twilio number
            'body' => 'your verification code is '. $otp
        ]
        );
        return $message;
    }
    public function getOtp(UserSignUpRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if (empty($user)) {
            $user = User::create([
                'phone_number' => $request->phone_number,
            ]);
        }
        $otpData = Otp::where([
            'sender_id' => $user->id,
            'reciever_id' => $user->id,
            'reciever_type'=> get_class($user),
            'sender_type' => get_class($user),
        ])->first();

        if (empty($otpData)) {
            $otpData = Otp::create([
                'sender_id' => $user->id,
                'reciever_id' => $user->id,
                'reciever_type' => get_class($user),
                'sender_type' => get_class($user),
                'otp' => random_int(1111, 9999),
                'status' => config('constants.otpStatus.open'),
                'section' => 'signup',
                'section_id' => config('constants.otpSection.signup')
            ]);
            $verification_code = $otpData['otp'];
        } else {
            $verification_code = $otpData->otp;
        }

        // $this->sendOtp($user->phone_number, $verification_code);
        return response()->json([
            'verification_code' => $verification_code,
            'is_verified' => false,
            'message' => __('apiMessage.otpSend'),
        ]);
    }

    public function verify(UserVerifyRequest $request)
    {
        return $this->verifyUserOpt(false, $request);
    }

    public function verifyUserOpt($withOtp, $request)
    {
        if ($withOtp) {
            $user = User::where('phone_number', $request['phone_number'])->first();
            $otpData = Otp::where([
                'sender_id' => $user->id,
                'reciever_id' => $user->id,
                'reciever_type'=> get_class($user),
                'sender_type' => get_class($user),
                'section_id' => config('constants.otpSection.signup')
            ])->first();
            if (empty($user)) {
                return response()->json([
                    'message' => __('apiMessage.unauthorized'),
                    'is_verified' => false,
                    'token' => null
                ]);
            } else {
                if ($request->verification_code == $otpData->otp) {
                    if ($user->is_block == 1) {
                        return response()->json([
                            'message' => __('apiMessage.accountBlocked'),
                            'is_verified' => null,
                            'token' => null
                        ]);
                    }
                    $token = $user->createToken('auth_token')->plainTextToken;
                    User::where([
                        ['phone_number', '=', $request->phone_number],
                    ])
                    ->update([
                        'is_verified' => true,
                        'device_token' => $request->device_token,
                        'device_type' => $request->device_type,
                    ]);
                    $otpData->delete();
                    if (isset($user->email)) {
                        return response()->json([
                            'message' => __('apiMessage.UserVerified'),
                            'is_verified' => true,
                            'token' => $token
                        ]);
                    } else {
                        return response()->json([
                            'message' => __('apiMessage.UserVerified'),
                            'is_verified' => false,
                            'token' => $token
                        ]);
                    }
                }
                return response()->json([
                    'message' => __('apiMessage.unauthorized'),
                    'is_verified' => false,
                    'token' => null
                ]);
            }
        } else {
            $user = User::where('phone_number', $request['phone_number'])->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            if (isset($user->email)) {
                return response()->json([
                    'message' => __('apiMessage.UserVerified'),
                    'is_verified' => true,
                    'token' => $token
                ]);
            } else {
                return response()->json([
                    'message' => __('apiMessage.UserVerified'),
                    'is_verified' => false,
                    'token' => $token
                ]);
            }
        }
    }

    public function getUserTypes(Request $request)
    {
        $userTypes = UserType::where('title', '!=', 'Customer')->get();
        return new UserTypeCollection($userTypes);
    }

    public function submitUserDetail(UserRegisterRequest $request)
    {
        $user = request()->user();

        if (Auth::check()) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)]
            );
            return response()->json([
                'message' => __('apiMessage.submitUserDetail'),
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => __('apiMessage.unauthorized'),
                'success' => false
            ]);
        }
    }

    public function submitUserRoles(Request $request)
    {
        $userRoles = $request->user_roles ?? '';
        $user = request()->user();
        if (Auth::check()) {
            if (!empty($userRoles)) {
                $userRoles = json_decode($userRoles, true);
                foreach ($userRoles as $userRole) {
                    $userRoleExist = UserRole::where([
                        ['user_type_id', '=', $userRole['id']],
                        ['user_id', '=', $user['id']],
                    ])->first();
                    if (empty($userRoleExist)) {
                        $data = UserRole::create([
                            'user_id' => $user['id'],
                            'user_type_id' => $userRole['id'],
                        ]);
                    }
                }
            } else {
                $userRoleExist = UserRole::where([
                    ['user_type_id', '=', config('constants.userRoles.Customer')],
                    ['user_id', '=', $user['id']],
                ])->first();
                if (empty($userRoleExist)) {
                    $data = UserRole::create([
                        'user_id' => $user['id'],
                        'user_type_id' => config('constants.userRoles.Customer'),
                    ]);
                }
            }
            $userRolesArray = $this->getUserRoles($user['id']);
            return new UserRoleCollection($userRolesArray);
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')]);
        }
    }

    public function loginWithPassword(UserLoginRequest $request)
    {
        if (Auth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password])) {
            $user = auth()->user();
            if ($user->is_block == 1) {
                return response()->json(['message'=>__('apiMessage.accountBlocked')], 401);
            }
            User::where([
                ['phone_number', $request->phone_number],
                ['password', '=', $request->password],
            ])
            ->update([
                'device_token' => $request->device_token,
                'device_type' => $request->device_type,
            ]);
            return response()->json([
                'isSuccess' => true,
                'message' => __('apiMessage.login'),
                'token' => auth()->user()->createToken('login_token')->plainTextToken
            ]);
        } else {
            return response()->json([
                'isSuccess' => false,
                'message' => __('apiMessage.invalidCredentials'),
                'token' => null
            ]);
        }
    }

    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        // Revoke current user token
        $user =  auth()->user();
        $user->currentAccessToken()->delete();
        User::where('id', $user->id)->update([
            'device_token' => null,
            'device_type' => null
            ]);
        return response()->json([
            'message' => __('apiMessage.userLogout'),
            'success' => true
        ]);
    }

    public function getUserDetails()
    {
        $user = auth()->user();
        if (!empty($user)) {
            $userDetails = User::with('types.role:id,title','usersubscription.subscriptionPlan:id,name')
            ->with(['addresses' => function ($query) {
                return $query->first();
            }])
            ->where('id', $user->id)->get();
            return UserDetailResource::collection($userDetails);
        } else {
            return response()->json(['error' => __('apiMessage.unauthorized')], 400);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = auth()->user();
        $userPassword = $user->password;
        if (!Hash::check($request->current_password, $userPassword)) {
            return response()->json(['error' => __('apiMessage.passwordNotMatch')], 401);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['message' => __('apiMessage.updatePassword')], 200);
    }

    public function getUserRoles($user_id)
    {
        $userRolesArray = [];
        $userCurrentRoles = UserRole::join('user_types', 'user_roles.user_type_id', '=', 'user_types.id')
        ->where('user_roles.user_id', $user_id)
        ->where('user_roles.user_type_id', '!=' , config('constants.userRoles.Customer'))
        ->get(['user_roles.*', 'user_types.title']);

        foreach ($userCurrentRoles as $key => $role) {
            $userRolesArray[$key]['role_name'] = $role['title'];
            $userRolesArray[$key]['value'] = $role['user_type_id'];
        }
        return $userRolesArray;
    }

    public function updateGeneralProfileData(Request $request)
    {
        $user = auth()->user();
        $dataToBeUpdate = [];
        $name = $request->name ?? '';
        if (!empty($name)) {
            $dataToBeUpdate['name'] = $name;
        }
        $email = $request->email ?? '';
        if (!empty($email)) {
            $dataToBeUpdate['email'] = $email;
        }
        if ($request->hasFile('image')) {
            $profile_photo = $request->file('image');
                $uploadFolder = 'images';
                $filename = $profile_photo->getClientOriginalName();
                $profile_photo->storeAs($uploadFolder, $filename, 'public');
                $dataToBeUpdate['image'] = $filename;
        }
        $data = User::where('id', $user->id)->update($dataToBeUpdate);
        if ($data) {
            return response()->json(['message' => __('apiMessage.updateDetails')], 200);
        } else {
            return response()->json(['error' => __('apiMessage.errorMsg')], 400);
        }
    }
}
