<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Currencies\CurrencyService;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // App::bind('usercurrency',function() {
        //     return new UserCurrency;
        //  });

         app()->bind('currencyservice', function(){  //Keep in mind this "check" must be return from facades accessor
            return new CurrencyService;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
