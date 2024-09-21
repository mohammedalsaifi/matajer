<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        JsonResource::withoutWrapping();

        Validator::extend('filter', function ($attribute, $value, $params) {
            return ! in_array(strtolower($value), $params);
        }, 'The Value Is Prohipted!');

        Paginator::useBootstrapFive();
        Paginator::defaultView('pagination.custom');
    }
}
