<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Upload;
use App\Models\CategoryFormData;
use App\Models\ProductRating;
use App\Http\Requests\SaveRatingRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\AllProductCollection;
use App\Http\Resources\SellOptionsResource;
use App\Http\Requests\UploadProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Admin;
use App\Notifications\UserUploadProduct;
use Illuminate\Support\Facades\Notification;
use App\Models\ProductBidding;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $product_name = $request->product_name;
        if (!empty($product_name)) {
            $products = Product::with(['uploads' => function($q) {
                $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
            }])
            ->where('title', $product_name)->get();

        } else {
            $products = Product::with(['uploads' => function($q) {
                $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
            }])->get();
        }
        foreach ($products as $datakey => $product) {
            if (!empty($product['uploads'])) {
                foreach ($product['uploads'] as $fileKey => $uploaddata) {
                    $products[$datakey]['uploads'][$fileKey]['filepath'] = 'storage/'.$uploaddata['filepath'];
                    foreach (config('constants.fileTypes') as $filetype => $value) {
                        if ($uploaddata['filetype'] == $value) {
                            $products[$datakey]['uploads'][$fileKey]['filetype'] = $filetype;
                        }

                    }
                }
            }
        }
        return new ProductCollection($products);
    }

    public function getProductOverviews(Request $request)
    {
        $product_id = $request->product_id;
        if (!empty($product_id)) {
            $products = product::where('id', $product_id)->get();
            if (!$products->isEmpty()) {
                return new ProductCollection($products);
            }
            else {
                return response()->json([
                    'message'=>__('apiMessage.productNotExist'),
                ]);
            }
        }
        else {
            return response()->json([
               'message'=>__('apiMessage.errorMsg'),
            ]);
        }
    }

    public function getAllProducts(Request $request)
    {
        // $products = Product::with(['uploads' => function($q) {
        //     $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
        // }])->get();
        // ,'bids'=>function($query){
        //     return $query->where('added_by',auth()->user()->id);
        // }
        $user = auth()->user();
        $userProductTypes = [];
        if (!empty($user)) {
            $userDetail = User::select('id','is_pro')->with('types.role:id,title')
            ->where('id', $user->id)->get()->toArray();
            //print_r($userDetail);die;
            $productTypesRoles =  config('constants.roleSaleMapping');
            $userRoles = $userDetail[0]['types'];
            foreach($userRoles as $userRole) {
                $userroleid = $userRole['role']['id'];
                foreach($productTypesRoles as $role_id => $productType) {
                    if ($role_id == $userroleid) {
                        $userProductTypes[] = $productType;
                    }
                    if ($userDetail[0]['is_pro'] == 1) {
                        $userProductTypes[] = config('constants.sellOptions.share');
                    }
                }
            }
        }
        //print_r($userProductTypes);die;
        $products = Product::with(['category','subcategory:id,title','addresses:id,address','user:id,name', 'uploads' => function($q) {
            $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
        }])->whereIn('sale_option_id', $userProductTypes)
        ->where('status', config('constants.productStatus.accepted'))
        ->whereNotIn('id', ProductBidding::where('added_by', $user->id)->pluck('product_id'))
        ->where('user_id', '!=', $user->id)
        ->latest()->get();

        return new ProductCollection($products);
    }

    public function getAllSellOptions() {
        $data =  config('constants.sellOptions');
        $sellOptions = [];
        foreach($data as $option => $value) {
            $sellOptions[$value]['id'] = $value;
            $sellOptions[$value]['option'] = ucfirst($option);
        }
        return new SellOptionsResource($sellOptions);
    }

    public function uploadProduct(UploadProductRequest $request ) {
        $user =  auth()->user();
        if (!empty($user)) {
            $productUploaded = Product::create([
                'title' => $request->product_name,
                'price' => $request->price,
                'description' => $request->description,
                'sale_option_id' => $request->sale_option_id,
                'time_slots' => $request->time_slots,
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
            ]);
            if ($request->hasFile('product_photo')) {
                $product_photos = $request->file('product_photo');
                foreach($product_photos as $product_photo){
                    $uploadFolder = 'productPhotos';
                    $uploadedPath = $product_photo->store($uploadFolder, 'public');
                    $input['path'] = $uploadFolder."/". basename($uploadedPath);
                    $input['filename'] = $product_photo->getClientOriginalName();
                    Upload::create([
                        'filename' => $input['filename'],
                        'filepath' => $input['path'],
                        'filetype'=> config('constants.fileTypes.productPhoto'),
                        'ref_id' => $productUploaded['id'],
                    ]);
                }
            }
            $custom_data = $request->custom_data ?? '';
            $customArray = json_decode($custom_data, true);
            if ($request->hasFile('custom_file')) {
                $custom_files = $request->file('custom_file');
                foreach ($custom_files as $fieldname => $custom_file) {
                    $uploadFolder = 'customFormFiles';
                    $profileUploadedPath = $custom_file->store($uploadFolder, 'public');
                    $input['path'] = "storage/".$uploadFolder."/". basename($profileUploadedPath);
                    $input['filename'] = $custom_file->getClientOriginalName();
                    $input['fieldname'] = $fieldname;
                    foreach ($customArray as $key => $customData) {
                        if ($customData['type'] == 'file' && $customData['isAttachment'] == true) {
                                if ($input['fieldname'] == $customData['name']) {
                                $customArray[$key]['filepath'] = $input['path'];
                            }
                        }
                    }
                }
            }

            if (!empty($customArray)) {
                CategoryFormData::create([
                    'user_id' => $user->id,
                    'category_dynamic_form_id' => $request->category_dynamic_form_id,
                    'data' => json_encode($customArray),
                    'type_id' => $productUploaded['id'],
                    'type' => 'product'
                ]);
            }


            if ($productUploaded) {
                $notificationDetails = [];
                $notificationDetails['product'] = $productUploaded;
                $notificationDetails['uploadedBy'] = User::find($productUploaded['user_id']);

                $adminUsers = Admin::where('role_id', 1)->get();
                Notification::send($adminUsers, new UserUploadProduct($notificationDetails));
                return response()->json([
                    'message' => __('apiMessage.productUploaded'),
                    'status' => 'success'
                ]);
            } else {
                return response()->json(['message' => __('apiMessage.errorMsg')]);
            }
        } else {
            return response()->json([
                'message' => __('apiMessage.unauthorized'),
                'status' => 'error'
            ]);
        }
    }

    public function list(Request $request){
        if($request->has('debug')){return response()->json($request->all());}
        $products = Product::with('bids', 'bids.user');

        $perPage = $request->has('per_page')?$request->get('per_page'):10;

        if($request->has('sort_by')){

            $products = $products->orderBy($request->get('sort_by'),$request->get('sort_direction')==-1?'DESC':'ASC');
        }
        if($request->has('filterKey')){
            foreach ($request->filterKey as $key => $col) {
                # code...

                $val = $request->filterVal[$key];
                if($val != ''){
                    if(strpos($col, '.')){
                        $collArr = explode('.',$col);
                        $products->whereHas($collArr[0], function($q) use($collArr,$val) { $q->where($collArr[1], "LIKE", "%".$val."%"); } );
                    }
                    else{
                        $products = $products->where($col, "LIKE", "%".$val."%");
                    }

                }

            }
        }
        $products = $products->paginate($perPage);
        return new ProductCollection($products);
    }

    public function show(Request $request, $product){

        $productLoaded = Product::with(['category:id,title','subcategory:id,title','addresses','addresses.city', 'addresses.state', 'addresses.country','user:id,name',  'uploads' => function($q) {
            $q->where('filetype', '=', config('constants.fileTypes.productPhoto'));
        }])->where('id', $product)->first();
        //return response()->json($productLoaded);
        return new ProductResource($productLoaded);
    }

    public function submitProductRating(SaveRatingRequest $request)
    {
        $user =  auth()->user();
        if (!empty($user)) {
            $RatingSubmitted = ProductRating::create($request->validated() + ['user_id' => $user->id]);
            if ($RatingSubmitted) {
                return response()->json([
                    'message' => __('apiMessage.productRatingSubmitted'),
                    'status' => 'success'
                ]);
            } else {
                return response()->json(['message' => __('apiMessage.errorMsg')]);
            }
        } else {
            return response()->json([
                'message' => __('apiMessage.unauthorized'),
                'status' => 'error'
            ]);
        }
    }


    public function updateStatus(Request $request, $id){
        $method = $request->method();
        $product = Product::find($id);
        $idUpdated = match($method){
            'GET'   =>  $product->update(['status'=>config('constants.responseStatus.accepted')]),
            'POST'   =>  $product->update(['status'=>config('constants.responseStatus.rejected'), 'status_reason' => $request->reason])
        };
        if ($idUpdated) {
            $msg = match($method){
                'GET'   =>  __('apiMessage.productApproved'),
                'POST'   =>  __('apiMessage.productRejected')
            };
            return response()->json(['message' => $msg, 'id'=>$idUpdated, 'status'=>config('constants.responseStatus.accepted') ]);
        } else {
            return response()->json(['message' => __('apiMessage.errorMsg')]);

        }
    }


    public function getRelatedUsers(Request $request, $product){
        // $request->
        $productData = Product::where('id', $product)->first();
        $users = User::whereHas('companies.categories' , function($query) use($productData){
            $query->where('subcategory_id',$productData->subcategory_id);
        })->with(['companies.categories', 'bids'=>function($query) use ($product) {
            $query->where('product_id', $product);
        }])->get();

        return response()->json($users);
    }


    public function saveRelatedUsers(Request $request, Product $product){

        $recyclers = array_unique(is_array($request->selected_users)?$request->selected_users:[$request->selected_users]);


        //$message = $request->message;

        $product_id = $request->pid;
        $charges = $request->amount_to_be_deducted;
        Product::where('id', $product_id)->update(['ia_amount'=>$charges]);
        //ProductBidding::where('product_id',$product_id)->delete();
        $alreadyBidded = ProductBidding::where('product_id',$product_id)->get()->pluck('added_by')->toArray();
        $idsToBeRemoved = array_diff($alreadyBidded,$recyclers );
        ProductBidding::where(['product_id'=>$product_id, 'status'=> config('constants.bidStatus.pending')])->delete();
        if(count($idsToBeRemoved)){
            ProductBidding::where('product_id',$product_id)->whereIn('added_by',$idsToBeRemoved)->delete();
        }
        if(count($recyclers)){
            foreach($recyclers as $recycler){
                if(!ProductBidding::where(['product_id'=>$product_id,'added_by'=>$recycler])->count()){
                    ProductBidding::create(['charges'=>$charges, 'bid_amount'=>($product->price + $charges), 'total_amount'=> $product->price??0, 'product_id'=>$product_id, 'added_by'=>$recycler, 'status'=>config('constants.bidStatus.pending')]);
                }
            }
        }
        return response()->json([
            'message' => __('apiMessage.productBiddingRequested'),
            'status' => 'success'
        ]);
        /**
        * Hook Notification Here
        */

        //ProductBidding::create();


    }

    public function acceptBidding(){

    }

    public function refundBiddingAmount(){

    }

    public function assignProductToCharitableOrganization(){

    }


    public function assignPartner(Request $request, $product_id, $admin_id){
        Product::where('id',$product_id)->update(['inspection_agent'=>$admin_id, 'ia_status'=>config('constants.iaStatus.assigned')]);
        return response()->json([
            'message'=>__('apiMessage.ia_assigned'),
        ]);
    }

    public function assignDeliveryPartner(Request $request, $product_id, $admin_id){
        Product::where('id',$product_id)->update(['inspection_agent'=>$admin_id, 'ia_status'=>config('constants.iaStatus.assigned')]);
        return response()->json([
            'message'=>__('apiMessage.ia_assigned'),
        ]);
    }

    public function assignProductTo(Request $request, $bidId){
        $bidded = ProductBidding::where('id',$bidId);

        $bidded->update(['status'=>config('constants.bidStatus.paid_plus_assigned')]);
        $remainingProducts = ProductBidding::where(['product_id'=>$bidded->first()->product_id, 'status'=>2]);
        $remainingProducts->update(['status'=>config('constants.bidStatus.refundable')]);
        return response()->json([
            'message'=>__('apiMessage.ia_assigned'),
        ]);
    }
    public function markPaid(Request $request, $bidId){
        $bidded = ProductBidding::where('id',$bidId);

        $bidded->update(['status'=>config('constants.bidStatus.paid_plus_assigned')]);

        /**
         * Make Product Order Entry (If not)
         * Make Order Payment Entry
         */

        $remainingProducts = ProductBidding::where(['product_id'=>$bidded->first()->product_id, 'status'=>2]);
        $remainingProducts->update(['status'=>config('constants.bidStatus.refundable')]);
        return response()->json([
            'message'=>__('apiMessage.ia_assigned'),
        ]);
    }

    public function payDeliveryPartner(Request $request, $product_id, $admin_id){
        Product::where('id',$product_id)->update(['inspection_agent'=>$admin_id, 'ia_status'=>config('constants.iaStatus.assigned')]);
        return response()->json([
            'message'=>__('apiMessage.ia_assigned'),
        ]);
    }



    public function getNearestCharitables(Request $request, $product){
        $productData = Product::where('id', $product)->first();
        $lat = $productData->addresses->latitude;
        $lon = $productData->addresses->longitude;

        DB::table("user_companies")
            ->select("posts.id"
                ,DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(posts.lat))
                * cos(radians(posts.lon) - radians(" . $lon . "))
                + sin(radians(" .$lat. "))
                * sin(radians(posts.lat))) AS distance"))
                ->groupBy("posts.id")
                ->get();
    }

}
