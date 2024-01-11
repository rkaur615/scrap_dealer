<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Models\User;
use App\Models\UserRole;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\RequirementsResurce;
use App\Mail\ResetPasswordMail;
use App\Models\City;
use App\Models\Country;
use App\Models\GeneralSetting;
use App\Models\State;
use App\Models\SupplierRequirement;
use App\Models\Upload;
use App\Models\UserAddress;
use App\Models\UserCategory;
use App\Models\UserType;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function signUp()
    {
        return view('site.signUp');
    }


    public function profile()
    {
        return view('site.profile.profile');
    }

    public function details(HTTPRequest $request,$id = null, $rid=null)
    {
        //dd($id);
        $settings = GeneralSetting::all();
        if($id && !$rid){
            return response()->json(['settings'=>$settings,'data'=>User::with(['addresses', 'feedback', 'feedback.retailer', 'catalog','catalog.title', 'userRole', 'categories.category'])->where('id',$id)->first()]);
        }
        return !$id ? response()->json(['settings'=>$settings,'data'=>auth()->user()]):response()->json(['data'=>User::with(['addresses', 'feedback'=>function($q)use($rid){ return $q->where('requirement_id', $rid)->get();}])->where('id',$id)->first()]);
    }

    public function completeProfile(HTTPRequest $request){

        $role = User::find(auth()->user()->id)->role();
        //$profile = auth()->user()->addresses;
        $profile = auth()->user()->addresses;
        $cats = auth()->user()->categories;
        $files = Upload::where(['filetype'=>1, 'ref_id'=>auth()->user()->id])->get();
        // dd(auth()->user());
        if($request->ajax()){
            return response()->json(['profile'=>$profile,'cats'=>$cats, 'files'=>$files]);
        }
        // dd($role);
        $template = match($role['user_type_id']){
            config('constants.userRoles.retailer')=> view('site.profile.retailer', compact('profile')),
            config('constants.userRoles.supplier')=> view('site.profile.supplier', compact('profile'))
        };
        return $template;

    }

    public function getLatLong($addr1, $zip, $city, $state){
        // https://maps.google.com/maps/api/geocode/json?address=&sensor=false
        // $address = urlencode("143 Blackstone Street, Mendon, MA - 01756");
        // $key = urlencode("AIzaSyArx9JBkqyCl5DoVTCTAH6bQGfA2lOzR0g");
        // $ch = curl_init();
        // $options = array(
        //     CURLOPT_URL => "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=".$key,
        //     CURLOPT_RETURNTRANSFER => 1,
        //     CURLOPT_TIMEOUT => 100,
        //     CURLOPT_SSL_VERIFYHOST => 0,
        //     CURLOPT_SSL_VERIFYPEER => false
        // );
        // curl_setopt_array($ch, $options);
        // $response = curl_exec($ch);
        // if(curl_error($ch))
        // {
        //     dd( 'error:' . curl_error($ch));
        // }
        // curl_close ($ch);
        // dd($response);

        $address = $addr1.' '.$city.' '.$state ;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&components=postal_code:".$zip."&key=AIzaSyArx9JBkqyCl5DoVTCTAH6bQGfA2lOzR0g";
        try {
            $client = new \GuzzleHttp\Client();
            $apiRequest = $client->request('GET', $url);
            $resp = json_decode($apiRequest->getBody());
        } catch (\Exception $re) {
            return['latitude'=>0,'longitude'=>0];
        }
        if(isset($resp->results[0])){
            $latitude = $resp->results[0]->geometry->location->lat;
            $longitude = $resp->results[0]->geometry->location->lng;
            return['latitude'=>$latitude,'longitude'=>$longitude];
        }
        return['latitude'=>0,'longitude'=>0];

    }

    public function saveTimeSlots(HttpRequest $request){
        $data = $request->all();
        $user = User::find($data["userid"]);
        $data['city_id'] = $data['city_id']??0;
        $latlong = $this->getLatLong($data['address'],$data['zip'], City::find($data['city_id'])?->name, State::find($data['state_id'])->name );
        // dd($latlong);
        if($user->addresses){
            $dataToSave = ['name'=>$data['name'],'address'=>$data['address'],'address2'=>$data['address2'], 'state_id'=>$data['state_id'],'city_id'=>$data['city_id'],'country_id'=>$data['country_id'], 'pin'=>$data['zip'],'time_slots'=>$data['timeSlots'], 'about_us'=>$data['aboutme'], 'radius'=>$data['radius']];
            if($data['lat']){
                $dataToSave['latitude']=$data['lat'];
                $dataToSave['longitude']=$data['lng'] ;
            }
            // dd($dataToSave);
            $isUpdated = $user->addresses($dataToSave)->update($dataToSave);
            if($isUpdated){
                User::where('id', auth()->user()->id)->update(['is_profile_completed'=>1]);
                $cats = $data['cats'];
                UserCategory::where('user_id',auth()->user()->id)->delete();
                $catData = collect($cats)->map(function($item){ return ['category_id'=>$item, 'user_id'=>auth()->user()->id];})->toArray();
                UserCategory::insert($catData);
            }
        }
        else{
            $dataToSave = ['name'=>$data['name'],'address'=>$data['address'],'address2'=>$data['address2'], 'state_id'=>$data['state_id'],'city_id'=>$data['city_id'],'country_id'=>$data['country_id'], 'pin'=>$data['zip'],'time_slots'=>$data['timeSlots'], 'about_us'=>$data['aboutme']];
            if($data['lat']){
                $dataToSave['latitude']=$data['lat'];
                $dataToSave['longitude']=$data['lng'] ;
            }
            $isUpdated = $user->addresses()->create($dataToSave);
            if($isUpdated){
                UserCategory::where('user_id',auth()->user()->id)->delete();
                User::where('id', auth()->user()->id)->update(['is_profile_completed'=>1]);
                $cats = $data['cats'];
                    $catData = collect($cats)->map(function($item){ return ['category_id'=>$item, 'user_id'=>auth()->user()->id];})->toArray();
                    UserCategory::insert($catData);
            }

        }

        session()->flash('success','Profile Updated Successfully');
        return response()->json(['type'=>'success', 'to'=>route('user.dashboard')]);
    }

    public function registerUser(UserRegisterRequest $request)
    {
        // dd($request->all());
        $userObj = new User();
        $userObj->name = trim($request->first_name).' '.trim($request->last_name);
        // $userObj->last_name = trim($request->last_name);
        $userObj->email = trim($request->email);
        $userObj->phone_number = trim($request->phone_number);
        $userObj->password = Hash::make($request->password);
        $userObj->save();
        $insertId = $userObj->id;

        UserRole::create([
            'user_id' => $insertId,
            'user_type_id' => config('constants')['userRoles'][$request->role],
        ]);

        return redirect('/user/login')->withSuccess('User has been registered successfully!');
    }

    public function logIn()
    {
        return view('site.logIn');
    }

    public function loginWithPassword(HttpRequest $request)
    {


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            if(auth()->user()->is_block){
                auth()->logout();
                return redirect('/user/login')->withError('Your account has been blocked. Please contact Admin!');
            }

            return redirect('/user/dashboard')->withSuccess('You have been logged in successfully!');
        }
        else{

            return redirect('/user/login')->withError('Invalid Login Credentials!');
        }

    }
    public function dashboard(HttpRequest $request)
    {
        $id = auth()->user()->id;
        $user = User::where('id', $id)->first();
        $dashboard = array_flip(config('constants.userRoles'))[UserRole::where('user_id',$id)->first()->user_type_id];

        return view('site.dashboard.'.$dashboard, compact('user', ));
    }

    public function changePassword()
    {
        return view('site.changePassword');
    }


    public function uploadFiles(HttpRequest $request){
        $userFiles = $request->file('files');
        $filetype = $request->get('filetype');
        if($filetype==1){
            $ref_id=auth()->user()->id;
        }
        else{
            $ref_id=$request->get('ref_id');
        }
        $filesRes = Upload::where(['filetype'=>$filetype, 'ref_id'=>$ref_id]);
        $files = $filesRes->get();
        if($files){
            foreach ($files as $f){
                if(File::exists(storage_path().'/'.$f->filepath)){
                    File::delete(storage_path().'/'.$f->filepath);
                }
            }

        }
        $filesRes->delete();
        // return response()->json($request->file());
        foreach($userFiles as $userFile){
            $uploadFolder = 'files';
            $uploadedPath = $userFile->store($uploadFolder, 'public');

            $input['path'] = $uploadFolder."/". basename($uploadedPath);
            $input['filename'] = $userFile->getClientOriginalName();
            Upload::create([
                'filename' => $input['filename'],
                'filepath' => $input['path'],
                'filetype'=> $filetype,
                'ref_id' => $ref_id,
            ]);
        }
    }

    /**
     * For Supplier
     *
     * @return JSON
     */
    public function myMatchingRequirements(){
        $users = User::whereHas('addresses')->closestTo(['latitude'=>'30.87681','longitude'=>'75.78498'])->get();
        dd($users);
    }

    // public function updatePassword(ChangePasswordRequest $request)
    // {
    //     $user = auth()->user();
    //     $userPassword = $user->password;
    //     if (!Hash::check($request->current_password, $userPassword)) {
    //         return redirect()
    //             ->back()
    //             ->withErrors(['passwordErrors' => 'You have inputed wrong current password!']);
    //     }
    //     $user = User::find(auth()->user()->id);
    //     $user->password = Hash::make($request->password);
    //     $user->save();
    //     return redirect('/change_password')
    //             ->withSuccess('Password updated successfully!');
    // }


    public function updatePersonalDetails(HttpRequest $request){

        $name = $request->name;
        $phone = $request->phone;
        User::where('id', auth()->user()->id)->update(['name'=>$name, 'phone_number'=>$phone]);
        return response()->json(['type'=>'error', 'msg'=>'Personal detail has been updated successfully']);
    }


    public function markedAllRead(HttpRequest $request){

        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['type'=>'success', 'msg'=>'Read status updated successfully']);
    }


    public function updatePassword(HttpRequest $request){
        // dd($request->all());
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;
        $rePassword = $request->re_password;
        if($newPassword==$rePassword){
            if(Hash::check($oldPassword,auth()->user()->password)){
                User::where('id', auth()->user()->id)->update(['password'=>Hash::make($newPassword)]);
                return response()->json(['type'=>'success', 'msg'=>"Password Updated Successfully"]);
            }
            else{
                return response()->json(['type'=>'error', 'msg'=>"Current password didn't matched"],500);
            }
        }
        else{
            return response()->json(['type'=>'error', 'msg'=>'Confirm Password should be same as New Password']);
        }


    }



    public function search(HttpRequest $request){
        $role = Helper::myRoleVerb(auth()->user()->id);
        $q = $request->search;

        if($role=='Retailer'){
            //Search Suppliers
            $suppliers = User::query()->suppliers($q)->get();
            dd($suppliers);
        }
        if($role=='Supplier'){
            //Search Retailers
        }


    }


    public function publicProfile(HttpRequest $request, $uid){
        $user = User::with('catalog','catalog.title','userRole', 'addresses', 'categories.category')->find($uid);
        if($request->ajax()){
            return response()->json($user);
        }
        return view('site.profile.public', compact('uid', 'user'));
    }

    public function getMyQuotes(HttpRequest $request, $uid){
        $myQuotes = SupplierRequirement::with('requirement')->where('user_id', $uid)->paginate(20);
        return RequirementsResurce::collection($myQuotes);
        return response()->json($myQuotes);
    }

    public function quotes(HttpRequest $request){
        $myQuotes = SupplierRequirement::with('requirement')->where('user_id', auth()->user()->id)->latest()->get();
        $settings = GeneralSetting::all();
        return view('site.quotes',compact('myQuotes','settings'));
        // return RequirementsResurce::collection($myQuotes);
        // return response()->json($myQuotes);
    }


    public function forgotPassword(){
        return view('site.forgotpassword');
    }


    public function forgotPasswordLink(HttpRequest $request){
        $email = $request->get('email');
        $user = User::where('email',$email)->first();


        if(!$user){
            return redirect()->back()->withError('Invalid Email Id');
        }
        else{
            $uuid = (string) Str::uuid();
            User::where('email',$email)->update(['reset_token'=>$uuid]);
        }

        Mail::to($user)->send(new ResetPasswordMail($uuid));

        return redirect()->to('/user/login')->withSuccess('Mail has been sent to Reset Password. Please check in Spam, if its not there in your mail inbox.');
    }

    public function resetPassword(HttpRequest $request, $token){
        $user = User::where('reset_token',$token)->first();
        if(!$user){
            return redirect()->route('user.login')->withError('Token Expired.');
        }
        $uid = $user->id;

        if(!$user){
            return redirect()->back()->withError('Invalid Email Id');
        }
        else{
            return view('site.resetpassword', compact('uid'));
        }

    }

    public function saveResetPassword(HttpRequest $request){

        $newPassword = $request->new_password;
        $uid = $request->uid;
        $rePassword = $request->confirm_password;

        if($newPassword==$rePassword){
                User::where('id', $uid)->update(['password'=>Hash::make($newPassword)]);
                return redirect()->to('/user/login')->withSuccess('Password Updated Successfully.');
        }
        else{
            return redirect()->back()->withError('Confirm Password should be same as New Password');
        }

    }
}
