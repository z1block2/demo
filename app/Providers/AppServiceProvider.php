<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        //\Log::info('$clientType:'.$client);
        DB::listen(function ($query){
            $tmp = str_replace('%', '', $query->sql);
            $tmp = str_replace('?', '"'.'%s'.'"', $tmp);
            $tmp = vsprintf($tmp, $query->bindings);
            $tmp = str_replace("\\","",$tmp);
            Log::info($tmp.' ; time:'.$query->time);
        });
    }
}
