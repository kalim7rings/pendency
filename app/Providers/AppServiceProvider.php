<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Zttp\ZttpResponse;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        ZttpResponse::macro('soap', function () {
            return json_decode(simplexml_load_string($this->body()), true);
        });
    }
}
