<?php

namespace App\Providers;

use App\Models\BankDetail;
use App\Models\GeneralSetting;
use App\Models\ProductBidding;
use App\Observers\BankDetailObserver;
use App\Observers\BidAcceptedObserver;
use App\Services\Helpers\Contact;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // foreach (glob(app_path().'/Http/Helpers/*.php') as $filename){
        //     require_once($filename);
        // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        BankDetail::observe(BankDetailObserver::class);
        ProductBidding::observe(BidAcceptedObserver::class);

        $this->app->bind('razorpay', function($app){
            $settings = GeneralSetting::all();
            $api_key = $settings->filter(function($item){ return $item->title == 'razor_pay_auth_token'; })->first()->value;
            $api_secret = $settings->filter(function($item){ return $item->title == 'razor_pay_secret_key'; })->first()->value;
            // dd($settings);
            return new PaymentService($api_key, $api_secret);
        });
        $this->app->bind('razorpay_contact', function($app){

            return new Contact();
        });
    }
}
