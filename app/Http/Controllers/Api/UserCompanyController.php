<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserCompany;
use App\Models\UserCategory;
use App\Models\Reference;
use App\Models\Upload;
use App\Models\OperationalArea;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserCompanyProfileRequest;
use App\Http\Resources\UserCompanyCollection;

class UserCompanyController extends Controller
{
    public function submitUserCompanyProfile(UserCompanyProfileRequest $request)
    {
        $user = auth()->user();
        $profileExist = UserCompany::where([
            ['user_type_id', '=', $request->user_type_id],
            ['user_id', '=', $user->id],
        ])->first();
        if (empty($profileExist)) {
            $userCompanyCreated = UserCompany::create([
                'company_name' => $request->company_name,
                'user_type_id' => $request->user_type_id,
                'user_id' => Auth::user()->id,
                'is_agency' => $request->is_agency
            ]);

            $operation_areas = $request->operation_area ?? '';
            echo "abc";
            if (!empty($operation_areas)) {
                $operation_areas = json_decode($operation_areas, true);
                foreach ($operation_areas as $operation_area) {
                    OperationalArea::create([
                        'user_company_id' => $userCompanyCreated['id'],
                        'city_id' => $operation_area['city_id'],
                        'state_id' => $operation_area['state_id'],
                    ]);
                }
            }

            $category = $request->category ?? '';
            if (!empty($category)) {
                $userCategories = json_decode($category, true);
                foreach ($userCategories as $usercategory) {
                    UserCategory::create([
                        'user_company_id' => $userCompanyCreated['id'],
                        'category_id' => $usercategory['category_id'],
                        'subcategory_id'=> $usercategory['child_id'],
                    ]);
                }
            }
            $userReferences = $request->references ?? '';
            if (!empty($userReferences)) {
                $userReferences = json_decode($userReferences, true);
                foreach ($userReferences as $userReference) {
                    Reference::create([
                        'person_name' => $userReference['person_name'],
                        'phone_number'=> $userReference['phone_number'],
                        'user_company_id' => $userCompanyCreated['id'],
                    ]);
                }
            }
            if ($request->hasFile('profile_photo')) {
                $profile_photo = $request->file('profile_photo');
                $uploadFolder = 'profilePhotos';
                $profileUploadedPath = $profile_photo->store($uploadFolder, 'public');
                $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                $input['filename'] = $profile_photo->getClientOriginalName();
                Upload::create([
                    'filename' => $input['filename'],
                    'filepath' => $input['path'],
                    'filetype'=> config('constants.fileTypes.profilePhoto'),
                    'ref_id' => $userCompanyCreated['id'],
                ]);                
            }
            if ($request->hasFile('agency_photo')) {
                $agency_photos = $request->file('agency_photo');
                foreach ($agency_photos as $key => $agency_photo) {
                    $uploadFolder = 'agencyPhotos';
                    $profileUploadedPath = $agency_photo->store($uploadFolder, 'public');
                    $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                    $input['filename'] = $agency_photo->getClientOriginalName();
                    Upload::create([
                        'filename' => $input['filename'],
                        'filepath' => $input['path'],
                        'filetype'=> config('constants.fileTypes.agencyPhoto'),
                        'ref_id' => $userCompanyCreated['id'],
                    ]);
                }
            }
            if ($request->hasFile('certification')) {
                $certificates = $request->file('certification');
                foreach ($certificates as $key => $certificate) {
                    $uploadFolder = 'certificates';
                    $profileUploadedPath = $certificate->store($uploadFolder, 'public');
                    $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                    $input['filename'] = $certificate->getClientOriginalName();
                    Upload::create([
                        'filename' => $input['filename'],
                        'filepath' => $input['path'],
                        'filetype'=> config('constants.fileTypes.certification'),
                        'ref_id' => $userCompanyCreated['id'],
                    ]);
                }
            }
            $dataToBeUpdate = [];
            $description = $request->description ?? '';
            if (!empty($description)) {
                $dataToBeUpdate['description'] = $description;
            }
            $country_id = $request->country_id ?? 0;
            if (!empty($country_id)) {
                $dataToBeUpdate['country_id'] = $country_id;
            }
            $state_id = $request->state_id ?? 0;
            if (!empty($state_id)) {
                $dataToBeUpdate['state_id'] = $state_id;
            }
            $city_id = $request->city_id ?? 0;
            if (!empty($city_id)) {
                $dataToBeUpdate['city_id'] = $city_id;
            }
            $pin = $request->pin ?? '';
            if (!empty($pin)) {
                $dataToBeUpdate['pin'] = $pin;
            }
            $address = $request->address ?? '';
            if (!empty($address)) {
                $dataToBeUpdate['address'] = $address;
            }
            $average_rate = $request->average_rate ?? '';
            if (!empty($average_rate)) {
                $dataToBeUpdate['average_rate'] = $average_rate;
            }
            $registration_number = $request->registration_number ?? '';
            if (!empty($registration_number)) {
                $dataToBeUpdate['registration_number'] = $registration_number;
            }
            $gst_number = $request->gst_number ?? '';
            if (!empty($gst_number)) {
                $dataToBeUpdate['gst_number'] = $gst_number;
            }
            $experience = $request->experience ?? 0;
            if (!empty($experience)) {
                $dataToBeUpdate['experience'] = $experience;
            }
            
            UserCompany::where('id', $userCompanyCreated['id'])->update($dataToBeUpdate);
            if ($userCompanyCreated) {
                return response()->json([
                    'message'=>__('apiMessage.userProfileSubmitted'),
                    'status'=> 'success'
                ]);
            } else {
                return response()->json([
                    'message'=>__('apiMessage.errorMsg'),
                    'status'=> 'error'
                ]);
            }
        } else {
            UserCompany::where([
                ['user_type_id', '=', $request->user_type_id],
                ['user_id', '=', $user->id],
            ])->update([
                'company_name' => $request->company_name,
                'is_agency' => $request->is_agency
            ]);

            $operation_areas = $request->operation_area ?? '';
            if (!empty($operation_areas)) {
                OperationalArea::where('user_company_id', $profileExist->id)->delete();//deleting old opeartion areas
                $operation_areas = json_decode($operation_areas, true);
                foreach ($operation_areas as $operation_area) {
                    OperationalArea::create([
                        'user_company_id' => $profileExist->id,
                        'city_id' => $operation_area['city_id'],
                        'state_id' => $operation_area['state_id'],
                    ]);
                }
            }

            $category = $request->category ?? '';
            if (!empty($category)) {
                UserCategory::where('user_company_id', $profileExist->id)->delete();//deleting old categories
                $userCategories = json_decode($category, true);
                foreach ($userCategories as $usercategory) {
                    UserCategory::create([
                        'user_company_id' => $profileExist->id,
                        'category_id' => $usercategory['category_id'],
                        'subcategory_id'=> $usercategory['child_id'],
                    ]);
                }
            }
            $userReferences = $request->references ?? '';
            if (!empty($userReferences)) {
                Reference::where('user_company_id', $profileExist->id)->delete();//deleting old refrences
                $userReferences = json_decode($userReferences, true);
                foreach ($userReferences as $userReference) {
                    Reference::create([
                        'person_name' => $userReference['person_name'],
                        'phone_number'=> $userReference['phone_number'],
                        'user_company_id' => $profileExist->id,
                    ]);
                }
            }
            if ($request->hasFile('profile_photo')) {
                Upload::where([
                    ['ref_id', '=', $profileExist->id],
                    ['filetype', '=', config('constants.fileTypes.profilePhoto')],
                ])->delete();//deleting old uploads
                $profile_photo = $request->file('profile_photo');
                $uploadFolder = 'profilePhotos';
                $profileUploadedPath = $profile_photo->store($uploadFolder, 'public');
                $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                $input['filename'] = $profile_photo->getClientOriginalName();
                Upload::create([
                    'filename' => $input['filename'],
                    'filepath' => $input['path'],
                    'filetype'=> config('constants.fileTypes.profilePhoto'),
                    'ref_id' => $profileExist->id,
                ]);                
            }
            if ($request->hasFile('agency_photo')) {
                Upload::where([
                    ['ref_id', '=', $profileExist->id],
                    ['filetype', '=', config('constants.fileTypes.agencyPhoto')],
                ])->delete();//deleting old uploads
                $agency_photos = $request->file('agency_photo');
                foreach ($agency_photos as $key => $agency_photo) {
                    $uploadFolder = 'agencyPhotos';
                    $profileUploadedPath = $agency_photo->store($uploadFolder, 'public');
                    $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                    $input['filename'] = $agency_photo->getClientOriginalName();
                    Upload::create([
                        'filename' => $input['filename'],
                        'filepath' => $input['path'],
                        'filetype'=> config('constants.fileTypes.agencyPhoto'),
                        'ref_id' => $profileExist->id,
                    ]);
                }
            }
            if ($request->hasFile('certification')) {
                Upload::where([
                    ['ref_id', '=', $profileExist->id],
                    ['filetype', '=', config('constants.fileTypes.certification')],
                ])->delete();//deleting old certificates
                $certificates = $request->file('certification');
                foreach ($certificates as $key => $certificate) {
                    $uploadFolder = 'certificates';
                    $profileUploadedPath = $certificate->store($uploadFolder, 'public');
                    $input['path'] = $uploadFolder."/". basename($profileUploadedPath);
                    $input['filename'] = $certificate->getClientOriginalName();
                    Upload::create([
                        'filename' => $input['filename'],
                        'filepath' => $input['path'],
                        'filetype'=> config('constants.fileTypes.certification'),
                        'ref_id' => $profileExist->id,
                    ]);
                }
            }
            $dataToBeUpdate = [];
            $description = $request->description ?? '';
            if (!empty($description)) {
                $dataToBeUpdate['description'] = $description;
            }
            $country_id = $request->country_id ?? 0;
            if (!empty($country_id)) {
                $dataToBeUpdate['country_id'] = $country_id;
            }
            $state_id = $request->state_id ?? 0;
            if (!empty($state_id)) {
                $dataToBeUpdate['state_id'] = $state_id;
            }
            $city_id = $request->city_id ?? 0;
            if (!empty($city_id)) {
                $dataToBeUpdate['city_id'] = $city_id;
            }
            $pin = $request->pin ?? '';
            if (!empty($pin)) {
                $dataToBeUpdate['pin'] = $pin;
            }
            $address = $request->address ?? '';
            if (!empty($address)) {
                $dataToBeUpdate['address'] = $address;
            }
            $average_rate = $request->average_rate ?? '';
            if (!empty($average_rate)) {
                $dataToBeUpdate['average_rate'] = $average_rate;
            }
            $registration_number = $request->registration_number ?? '';
            if (!empty($registration_number)) {
                $dataToBeUpdate['registration_number'] = $registration_number;
            }
            $gst_number = $request->gst_number ?? '';
            if (!empty($gst_number)) {
                $dataToBeUpdate['gst_number'] = $gst_number;
            }
            $experience = $request->experience ?? 0;
            if (!empty($experience)) {
                $dataToBeUpdate['experience'] = $experience;
            }
            $operation_area = $request->operation_area ?? '';
            if (!empty($operation_area)) {
                $dataToBeUpdate['operation_area'] = $operation_area;
            }
            UserCompany::where('id', $profileExist->id)->update($dataToBeUpdate);
            return response()->json([
                'message'=>__('apiMessage.userProfileUpdated'),
                'status'=> 'success'
            ]);
        }
    }

    public function getUserCompanyProfile(Request $request)
    {
        $user = request()->user();
        $user_type_id = $request->user_type_id;
        $userCompanyData = UserCompany::with(['country:id,name', 'state:id,name', 'city:id,name', 'user:id,name', 'userType:id,title','references', 'categories', 'operationAreas.city:id,name', 'uploads' => function ($q) {
            $q->where('filetype', '!=', config('constants.fileTypes.serviceRequestPhoto'))
            ->where('filetype', '!=', config('constants.fileTypes.productPhoto'));
        }])
        ->where([
            ['user_type_id', '=', $user_type_id],
            ['user_id', '=', $user['id']],
        ])->get();
        foreach ($userCompanyData as $datakey => $profiledata) {
            foreach ($profiledata['uploads'] as $fileKey => $uploaddata) {
                $userCompanyData[$datakey]['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
            }
        }
        if (!$userCompanyData->isEmpty()) {
            return new UserCompanyCollection($userCompanyData);
        } else {
            return response()->json([
                'message'=>__('apiMessage.dataNotExist'),
            ]);
        }
    }

    public function getOldServiceProvider(Request $request)
    {
        $category_id = $request->category_id;
        $subcategory_id = $request->subcategory_id;
        $serviceProvidersData = UserCompany::with(['categories' => function ($q) use ($category_id, $subcategory_id){
            $q->where('category_id', '=', $category_id)
            ->where('subcategory_id', '=', $subcategory_id);
        }])        
        ->where([            
            ['user_companies.user_type_id', '=', config('constants.userRoles.Service Provider')],
        ])->get(['user_companies.id', 'user_companies.company_name']);
       
        if (!$serviceProvidersData->isEmpty()) {
            return new UserCompanyCollection($serviceProvidersData);
        } else {
            return response()->json([
                'message'=>__('apiMessage.dataNotExist'),
            ]);
        }
    }
}
