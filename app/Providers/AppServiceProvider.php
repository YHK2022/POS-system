<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        // if( (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
        //     URL::forceScheme('https');
        // }
        //setting language
        if(isset($_COOKIE['language'])) {
            \App::setLocale($_COOKIE['language']);
        } else {
            \App::setLocale('en');
        }
        //get general setting value    
        // $company = auth()->user()->id;  
        
        View::composer('*', function ($view) {
    if (Auth::check()) {
        $general_setting = \App\GeneralSetting::where('company_id', Auth::user()->company_id)->first();
        $currency = \App\Currency::find($general_setting->currency);
        View::share('general_setting', $general_setting);
        View::share('currency', $currency);
        config(['staff_access' => $general_setting->staff_access, 'date_format' => $general_setting->date_format,
       'currency' => $currency->code, 'currency_position' => $general_setting->currency_position]);

        $alert_product = DB::table('products')->where('is_active', true)
        ->where('company_id', Auth::user()->company_id)
        ->whereColumn('alert_quantity', '>', 'qty')->count();
        View::share('alert_product', $alert_product);
        Schema::defaultStringLength(191);


    }
});


        
        
        // $currency = \App\Currency::find($general_setting->currency);
      
        // View::share('general_setting', $general_setting);
        // View::share('currency', $currency);
        // config(['staff_access' => $general_setting->staff_access, 'date_format' => $general_setting->date_format,
        //  'currency' => $currency->code, 'currency_position' => $general_setting->currency_position]);
        
        // $alert_product = DB::table('products')->where('is_active', true)
        // ->whereColumn('alert_quantity', '>', 'qty')->count();
        // View::share('alert_product', $alert_product);
        // Schema::defaultStringLength(191);
    }
}