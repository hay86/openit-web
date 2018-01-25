<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // max string length for utf8mb4
        Schema::defaultStringLength(191);

        // set local datetime display
        \Carbon\Carbon::setLocale('zh');

        // add @openit.vip validator
        Validator::extend('email_domain', function ($attribute, $value, $parameters, $validator) {
            return in_array(strstr($value, '@'), ['@openit.vip']);
        });

        // add array of integer validator
        Validator::extend('int_array', function($attribute, $value, $parameters, $validator)
        {
            if (!is_array($value)) return false;
            foreach($value as $v)
                if(!is_numeric($v) || (int)$v!=$v) return false;
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
