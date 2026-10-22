<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CurrencyServiceFacades extends Facade {
   protected static function getFacadeAccessor()
    {
        return 'currencyservice';
    }

}