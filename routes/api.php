<?php

use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\NotificationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\UserSubscriptionPlanController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ProductBiddingController;
use App\Http\Controllers\Api\FavouriteItemController;
use App\Http\Controllers\Api\UserCompanyController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderPaymentController;
use App\Http\Controllers\Api\ResponseController;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminSectionController;
use App\Http\Controllers\Api\Admin\AdminSubscriptionPlanController;
use App\Http\Controllers\Api\Admin\AdminUserPermissionController;
use App\Http\Controllers\Api\Admin\GeneralSettingController;
use App\Http\Controllers\Api\Admin\TicketCategoryController;
use App\Http\Controllers\Api\Admin\TicketCommentController;
use App\Http\Controllers\Api\Admin\TicketController;
use App\Http\Controllers\BankDetailController;
use App\Http\Controllers\DynamicFormController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\UserController;
use App\Models\DynamicForm;
use App\Models\GeneralSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Razorpay\Api\Api;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/getOtp', [AuthController::class, 'getOtp']);
Route::get('/sendOtp', [AuthController::class, 'sendOtp']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('admin/login', [AdminUserController::class, 'adminLogin']);
Route::post('/loginWithPassword', [AuthController::class, 'loginWithPassword']);
Route::get('/getUserTypes', [AuthController::class, 'getUserTypes']);
Route::get('/test/{serviceRequestId}', [ServiceRequestController::class, 'autoAssignServiceProvider']);

Route::get('phpmyinfo', function () {
    phpinfo();
})->name('phpmyinfo');


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/submitUserDetail', [AuthController::class, 'submitUserDetail']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/requirements/rlist/{id}', [RequirementController::class, 'rlist']);
    Route::get('/user/details/{id}', [UserController::class, 'publicProfile']);
    Route::get('/user/getMyQuotes/{id}', [UserController::class, 'getMyQuotes']);
    Route::get('/user/getMyRequirements/{id}', [UserController::class, 'getMyQuotes']);
    Route::get('/getScrapCategories', [CategoryController::class, 'getScrapCategoryList']);
    Route::get('/getCharitableCategories', [CategoryController::class, 'getCharitableCategoryList']);
    Route::get('/getServiceCategories', [CategoryController::class, 'getServiceCategoryList']);
    Route::get('/getRecyclerCategories', [CategoryController::class, 'getRecyclerCategoryList']);
    Route::get('/getCountries', [CountryController::class, 'getCountries']);
    Route::get('/getStates', [StateController::class, 'getStates']);
    Route::get('/getCities', [CityController::class, 'getCities']);
    Route::get('/getProducts', [ProductController::class, 'getProducts']);
    Route::get('/getServices', [ServiceController::class, 'getServices']);
    Route::post('/submitUserRoles', [AuthController::class, 'submitUserRoles']);
    Route::post('/submitUserCompanyProfile', [UserCompanyController::class, 'submitUserCompanyProfile']);
    Route::get('/notification', [NotificationController::class, 'allNotifications']);
    Route::put('/markNotificationsAsRead', [NotificationController::class, 'markNotificationsAsRead']);
    Route::get('/getUserCompanyProfile', [UserCompanyController::class, 'getUserCompanyProfile']);
    Route::get('/getUser', [AuthController::class, 'getUserDetails']);
    Route::post('/saveAddress', [AddressController::class, 'addUpdateAddress']);
    Route::get('/getMyRequests', [ServiceRequestController::class, 'getMyRequests']);
    Route::get('/getAllSubscriptions', [UserSubscriptionPlanController::class, 'getAllSubscriptions']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);
    Route::get('/getSavedAddresses', [AddressController::class, 'getSavedAddresses']);
    Route::get('/getProductCategories', [CategoryController::class, 'getProductCategoryList']);
    Route::post('/requestService', [ServiceRequestController::class, 'requestService']);
    Route::get('/getOldServiceProvider', [UserCompanyController::class, 'getOldServiceProvider']);
    Route::post('/saveTransaction', [TransactionController::class, 'saveTransaction']);
    Route::get('/getAllTransactions', [TransactionController::class, 'getTransactions']);
    Route::post('/updateGeneralProfileData', [AuthController::class, 'updateGeneralProfileData']);
    Route::get('/getProductOverviews', [ProductController::class, 'getProductOverviews']);
    Route::get('/getAllProducts', [ProductController::class, 'getAllProducts']);
    Route::post('/submitUserSubscription', [UserSubscriptionPlanController::class, 'submitUserSubscription']);
    Route::get('/getOrderSummary', [OrderController::class, 'getOrderSummary']);
    Route::get('/getcatByType/{catType}', [CategoryController::class, 'getCatByType']);
    Route::get('/getAllSellOptions', [ProductController::class, 'getAllSellOptions']);
    Route::post('/uploadProduct', [ProductController::class, 'uploadProduct']);
    Route::post('/submitProductRating', [ProductController::class, 'submitProductRating']);
    Route::post('/saveFavouriteItem', [FavouriteItemController::class, 'saveFavouriteItem']);
    Route::delete('/deleteFavouriteItem', [FavouriteItemController::class, 'deleteFavouriteItem']);
    Route::get('/getAssignedServiceProvider', [ServiceRequestController::class, 'getAssignedServiceProvider']);
    Route::get('/getMyWishlist', [FavouriteItemController::class, 'getMyWishlist']);
    Route::get('/getMultipleCities', [CityController::class, 'getMultipleCities']);
    Route::get('/getServiceRequestOverview', [ServiceRequestController::class, 'getServiceRequestOverview']);
    Route::post('/updateRequestStatus', [ServiceRequestController::class, 'updateRequestStatus']);

    Route::post('/updateBidStatus', [ProductBiddingController::class, 'updateBidStatus']);
    Route::get('/getAllBids', [ProductBiddingController::class, 'getAllBids']);

    Route::get('/getMyResponses', [ResponseController::class, 'getMyResponses']);
    Route::post('/saveResponse', [ResponseController::class, 'saveResponse']);
    Route::post('/submitPaymentDetails', [OrderPaymentController::class, 'submitPaymentDetails']);
    Route::post('/addBidOnProduct', [ProductBiddingController::class, 'addBidOnProduct']);
    Route::post('/saveOrder', [OrderController::class, 'saveOrder']);
    Route::get('/getCustomFormsData/{catid}', [CategoryController::class, 'getCustomFormsData']);
    Route::group(['prefix' => 'service'], function() {
        Route::post('/create', [ServiceController::class, 'createService']);
        Route::delete('/delete/{service}', [ServiceController::class, 'deleteService']);
    });
    Route::group(['prefix' => 'ticket'], function() {
        Route::post('/comment', [TicketCommentController::class, 'createUserComment']);
    });
});

Route::get( '/unauthenticated', function () {
    return "Token is wrong.";
}
)->name('login');



// Routes for admin section made in vue js
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function() {
    Route::group(['prefix' => 'razorpay'], function() {
        Route::post('/', function(){
            $settings = GeneralSetting::all();
            $api_key = $settings->filter(function($item){ return $item->title == 'razor_pay_auth_token'; })->first()->value;
            $api_secret = $settings->filter(function($item){ return $item->title == 'razor_pay_secret_key'; })->first()->value;

            $op = Http::withBasicAuth('rzp_test_TeFaVpdqd9Ljf1', 'rbk8iyTSm4kvS9vab2zg2nfF')->post("https://api.razorpay.com/v1/payouts",[

                    "account_number"=> "2323230021640185",
                    "fund_account_id"=> "fa_JkWyVoR7O7W5oG",
                    "amount"=> 200,
                    "currency"=> "INR",
                    "mode"=> "IMPS",
                    "purpose"=> "refund",
                    "queue_if_low_balance"=> true,
                    "reference_id"=> "Acme Transaction ID 12345",
                    "narration"=> "Acme Corp Fund Transfer",
                    "notes"=> [
                      "notes_key_1"=>"Tea, Earl Grey, Hot",
                    ]
            ]);
            // $settings = GeneralSetting::all();

            // $api_key = $settings->filter(function($item){ return $item->title == 'razor_pay_auth_token'; })->first()->value;
            // $api_secret = $settings->filter(function($item){ return $item->title == 'razor_pay_secret_key'; })->first()->value;

            // $api = new Api($api_key, $api_secret);
            // //$api->customer->create(array('name' => 'Razorpay User', 'email' => 'customer@razorpay.com','contact'=>'9123456780','notes'=> array('notes_key_1'=> 'Tea, Earl Grey, Hot','notes_key_2'=> 'Tea, Earl Grey… decaf')));
            // $created = $api->fundAccount->create([
            //     //'customer_id'=>'cust_JkT7rbCvNf5EOZ',
            //     'contact_id'=>'cont_JkULskPh3Q7GSN',
            //     "account_type"=>"bank_account",
            //     "bank_account"=>[
            //         "name"=>"Amandeep Singh",
            //         "ifsc"=>"HDFC0000053",
            //         "account_number"=>"765432123456789"
            //     ]
            // ]);
            // $accounts = $api->fundAccount->all();
            return response()->json([$op]);


        });


    });
    Route::get('/adminDetail', [AdminUserController::class, 'adminDetail']);
    Route::post('/logout', [AdminUserController::class, 'adminLogout']);
    Route::post('/changePassword', [AdminUserController::class, 'changePassword']);
    Route::get('/userList', [AdminUserController::class, 'getUserList']);
    Route::get('/getUserData/{user}', [AdminUserController::class, 'getUserData']);

    Route::get('/getAllUsersByType/{type}', [AdminUserController::class, 'getAllUsersByType']);
    Route::get('/getAllUsersByTypes', [AdminUserController::class, 'getAllUsersByTypes']);
    Route::get('/getAllUsers', [AdminUserController::class, 'getAllUsers']);
    Route::get('/assignPartner/{project}/{admin}', [ProductController::class, 'assignPartner']);
    Route::get('/assignDeliveryPartner/{project}/{admin}', [ProductController::class, 'assignDeliveryPartner']);
    Route::get('/assignProductTo/{bidid}', [ProductController::class, 'assignProductTo']);
    Route::get('/markPaid/{bidid}', [ProductController::class, 'markPaid']);

    Route::get('/blockUser/{id}', [AdminUserController::class, 'blockUser']);
    Route::get('/unBlockUser/{id}', [AdminUserController::class, 'unBlockUser']);
    Route::get('/markPro/{id}', [AdminUserController::class, 'markPro']);
    Route::get('/markNormal/{id}', [AdminUserController::class, 'markNormal']);
    Route::get('/notification', [NotificationController::class, 'allNotifications']);
    Route::put('/markNotificationsAsRead', [NotificationController::class, 'markNotificationsAsRead']);
    Route::put('/updateDetails/{id}', [AdminUserController::class, 'updateDetails']);
    Route::delete('/deleteUser/{id}', [AdminUserController::class, 'deleteUser']);
    Route::post('/createAdmin', [AdminUserController::class, 'createAdmin']);
    Route::get('/adminList/{role?}', [AdminUserController::class, 'getAllAdminList']);
    Route::get('/userDetail/{admin}', [AdminUserController::class, 'getUserDetail']);
    Route::put('/blockUnblockAdminUser/{admin}', [AdminUserController::class, 'blockUnblockAdminUser']);
    Route::delete('/deleteAdminUser/{admin}', [AdminUserController::class, 'deleteAdminUser']);
    Route::put('/changeUserPassword/{admin}', [AdminUserController::class, 'changeUserPassword']);

    Route::group(['prefix' => 'category'], function() {
        Route::post('/create', [CategoryController::class, 'createCategory']);
        Route::get('/getTypes', [CategoryController::class, 'getTypes']);
        Route::post('/editCategory', [CategoryController::class, 'editCategory']);
        Route::delete('/deleteCategory', [CategoryController::class, 'deleteCategory']);
        Route::get('/getCategoryDetails', [CategoryController::class, 'getCategoryDetails']);
        Route::get('/getScrapCategories', [CategoryController::class, 'getScrapCategories']);
        Route::get('/getCharitableCategories', [CategoryController::class, 'getCharitableCategories']);
        Route::get('/getServiceCategories', [CategoryController::class, 'getServiceCategories']);
        Route::get('/getRecyclerCategories', [CategoryController::class, 'getRecyclerCategories']);
        Route::get('/getProductCategories', [CategoryController::class, 'getProductCategories']);
        Route::get('/getAllParentCat', [CategoryController::class, 'getAllParentCat']);
        Route::post('/saveSearchFields', [CategoryController::class, 'saveSearchFields']);

    });

    Route::group(['prefix' => 'generalSetting'], function() {
        Route::get('/getFieldTypes', [GeneralSettingController::class, 'getFieldTypes']);
        Route::post('/bulkUpdate', [GeneralSettingController::class, 'bulkUpdate']);
        Route::post('/emptyTables', [GeneralSettingController::class, 'emptyTables']);
    });
    Route::apiResource('generalSetting', GeneralSettingController::class);

    Route::group(['prefix' => 'form'], function() {
        Route::post('/create', [DynamicFormController::class, 'create']);
        Route::post('/update', [DynamicFormController::class, 'update']);
        Route::get('/list', [DynamicFormController::class, 'list']);
        Route::get('/listLikedToCat/{catid}', [DynamicFormController::class, 'listLikedToCat']);
        Route::get('/getFormFields/{form}', [DynamicFormController::class, 'getFormFields']);
        Route::get('/attachForm/{category}/{form}', [CategoryController::class, 'attachForm']);
        Route::delete('/delete/{form}', [DynamicFormController::class, 'deleteForm']);
    });

    Route::group(['prefix' => 'product'], function() {
        Route::get('/list', [ProductController::class, 'list']);
        Route::get('/getRelatedUsers/{product}', [ProductController::class, 'getRelatedUsers']);
        Route::post('/saveRelatedUsers/{product}', [ProductController::class, 'saveRelatedUsers']);
        Route::get('/show/{prodcut}', [ProductController::class, 'show']);
        Route::get('/updateStatus/{product}', [ProductController::class, 'updateStatus']);
        Route::post('/updateStatus/{product}', [ProductController::class, 'updateStatus']);

    });

    Route::group(['prefix' => 'service'], function() {
        Route::get('/list', [ServiceRequestController::class, 'list']);
        Route::get('/show/{servicerequest}', [ServiceRequestController::class, 'show']);
        Route::get('/updateStatus/{servicerequest}', [ServiceRequestController::class, 'updateStatus']);
        Route::post('/updateStatus/{servicerequest}', [ServiceRequestController::class, 'updateStatus']);
    });

    Route::group(['prefix' => 'permission'], function() {
        Route::post('/create', [AdminUserPermissionController::class, 'create']);
        Route::put('/update/{id}', [AdminUserPermissionController::class, 'update']);
    });

    Route::apiResource('role', AdminRoleController::class);
    Route::apiResource('section', AdminSectionController::class);
    Route::apiResource('subscription', AdminSubscriptionPlanController::class);
    Route::apiResource('ticketCategory', TicketCategoryController::class);
    Route::group(['prefix' => 'ticket'], function() {
        Route::get('/comment', [TicketCommentController::class, 'getTicketComment']);
        Route::post('/updateStatus/{ticket}', [TicketController::class, 'updateStatus']);
        Route::post('/comment', [TicketCommentController::class, 'createAdminComment']);
        Route::put('/comment/{comment}', [TicketCommentController::class, 'updateComment']);
    });
    Route::apiResource('ticket', TicketController::class);

    Route::group(['prefix' => 'bankDetail'], function() {
        Route::post('/', [BankDetailController::class, 'store']);
        Route::post('/user', [BankDetailController::class, 'storesUserDetails']);
        Route::put('/', [BankDetailController::class, 'update']);
        Route::put('/userupdate', [BankDetailController::class, 'updateUserDetails']);
        Route::get('/', [BankDetailController::class, 'show']);
    });
});
