<?php

use App\Events\ChatEvent;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\StateController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\RequirementController;
use App\Http\Controllers\UserCatalogController;
use App\Http\Controllers\UserController;
use App\Models\Chat;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------

| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('/broadcast/private', function(){
    ChatEvent::dispatch(Chat::find(1));
});

Route::group(['prefix'=>'/user'], function(){

    Route::get('/login', function () {
        if(auth()->check()){
            return redirect()->route('user.dashboard');
        }
        return view('login');
    })->name(('user.login'))->middleware('prevent-back-history');
    Route::get('/logout', function () {
        auth()->logout();

        return redirect()->route('user.login');
    })->name('user.logout');
    Route::get('/register', function () {
        return view('signup');
    })->name('user.register');
    Route::post('/registration', [UserController::class, 'registerUser'])->name('user.post.register');
    Route::post('/login', [UserController::class, 'loginWithPassword'])->name('user.post.login');
    Route::get('/forgot', [UserController::class, 'forgotPassword'])->name('user.password.forgot');
    Route::post('/forgot', [UserController::class, 'forgotPasswordLink'])->name('user.password.forgot');
    Route::get('/resetPassword/{token}', [UserController::class, 'resetPassword'])->name('user.password.reset');
    Route::post('/updatePassword', [UserController::class, 'saveResetPassword'])->name('user.password.update');


    Route::get('/profile',[UserController::class, 'completeProfile'])->name('user.profile.complete');
    Route::post('/save/timeslots',[UserController::class, 'saveTimeSlots']);
    Route::post('/uploadFiles',[UserController::class, 'uploadFiles']);

    Route::group(['middleware'=>'profile'], function(){
        Route::get('/chat/{uid}/{rid}',[ChatController::class, 'index'])->name('chat.rooms');
        Route::get('/chat/{room}',[ChatController::class, 'chatSlug'])->name('chat.slug');
        Route::get('/nearby',[UserController::class, 'myMatchingRequirements']);

        Route::get('/details/{id}/{rid}',[UserController::class, 'details']);
        Route::get('/details/{id}',[UserController::class, 'details']);
        Route::get('/details',[UserController::class, 'details']);
        Route::get('/acceptItemQuote/{id}',[RequirementController::class, 'acceptItemQuote']);
        Route::get('/rejectItemQuote/{id}',[RequirementController::class, 'rejectItemQuote']);

        Route::get('/public/profile/{id}',[UserController::class, 'publicProfile'])->name('user.profile.public');
        Route::get('/myprofile',[UserController::class, 'profile'])->name('user.profile');
        Route::get('/markedAllRead',[UserController::class, 'markedAllRead']);
        Route::get('/myQuotes',[UserController::class, 'quotes'])->name('user.supplier.quotes');
        Route::post('/update/personal',[UserController::class, 'updatePersonalDetails']);
        Route::post('/update/password',[UserController::class, 'updatePassword']);
        Route::get('/dashboard',[UserController::class, 'dashboard'])->name('user.dashboard');
        Route::group(['prefix'=>'/invite'],function(){
            Route::get('/send/{requirement}', [RequirementController::class, 'sendInvite'])->name('user.invite.send');
            Route::get('/sendSelected/{requirement}', [RequirementController::class, 'sendSelected'])->name('user.invite.sendSelected');
            Route::post('/accept', [RequirementController::class, 'acceptInvite'])->name('user.invite.accept');
            Route::post('/updateRequirement', [RequirementController::class, 'updateRequirement'])->name('user.invite.updateRequirement');
        });
        Route::group(['prefix'=>'/requirement'],function(){
            Route::get('/getRequirement/{requirement}', [RequirementController::class, 'getRequirement']);
            Route::get('/getRequirementQuote/{requirement}/{qid}', [RequirementController::class, 'getRequirementQuote']);
            Route::get('/view/{requirement}', [RequirementController::class, 'view'])->name('user.requirement.view');
            Route::get('/showInvoice/{requirement}', [RequirementController::class, 'showInvoice'])->name('requirement.show.invoice');


            Route::post('/approveQuote', [RequirementController::class, 'approveQuote']);
            Route::post('/saveRating', [RequirementController::class, 'saveRating']);
            Route::post('/rejectQuote', [RequirementController::class, 'rejectQuote']);
            Route::get('/viewQuotes/{requirement}', [RequirementController::class, 'viewQuotes'])->name('user.requirement.viewQuotes');
            Route::get('/close/{requirement}', [RequirementController::class, 'close'])->name('user.requirement.close');
            Route::get('/viewQuote/{quoteId}', [RequirementController::class, 'viewQuote'])->name('user.requirement.viewQuote');
            Route::get('/completeQuote/{quoteId}', [RequirementController::class, 'completeQuote'])->name('user.requirement.completeQuote');
            Route::get('/edit/{requirement}', [RequirementController::class, 'edit'])->name('user.requirement.edit');
            Route::get('/delete/{requirement}', [RequirementController::class, 'delete'])->name('user.requirement.delete');
            Route::get('/add', [RequirementController::class, 'add'])->name('user.requirement.add');
            Route::get('/list', [RequirementController::class, 'list'])->name('user.requirement.list');
            Route::post('/create', [RequirementController::class, 'create'])->name('user.requirement.create');
            Route::post('/update/{requirement}', [RequirementController::class, 'update'])->name('user.requirement.update');
            Route::get('/getSingle/{rid}', [RequirementController::class, 'getSingle']);
        });
        Route::group(['prefix'=>'/catalog', 'middleware'=>'auth'],function(){
            Route::get('/add', [UserCatalogController::class, 'add'])->name('user.catalog.add');
            Route::get('/edit/{catalog}', [UserCatalogController::class, 'edit'])->name('user.catalog.edit');
            Route::get('/getSingle/{catalog}', [UserCatalogController::class, 'getSingle']);
            Route::post('/create', [UserCatalogController::class, 'create'])->name('user.catalog.create');
            Route::post('/createProductTitle', [UserCatalogController::class, 'createProductTitle']);
            Route::get('/list', [UserCatalogController::class, 'list'])->name('user.catalog.list');
            Route::delete('/delete', [UserCatalogController::class, 'delete'])->name('user.catalog.delete');
        });
    });

});

Route::group(['prefix'=>'/search', 'middleware'=>'auth'], function(){
    Route::get('/categories',[CategoryController::class, 'getCategoryByType']);
    Route::get('/categoriesForProduct',[CategoryController::class, 'categoriesForProduct']);
    Route::get('/countries',[CountryController::class, 'searchCountries']);
    Route::post('/product_titles/selected',[UserCatalogController::class, 'productTitlesSelected']);
    Route::get('/product_titles/all',[UserCatalogController::class, 'productTitles']);
    Route::get('/countries/all',[CountryController::class, 'getCountries']);
    Route::get('/states',[StateController::class, 'getStates']);
    Route::get('/cities',[CityController::class, 'getCities']);
    Route::get('/',[UserController::class, 'dashboard'])->name('search');
});
