<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\settings;

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
        Paginator::useBootstrap();
        //
        // Paginator::useBootstrapFive();
        view()->composer('*', function ($view)
        {
            $siteSetting = settings::pluck('value', 'key')->toArray();
            // $siteSetting = Setting::pluck('value', 'key')->toArray(); 
            $view->with(['siteSetting'=> $siteSetting,'paginateData' => [
                '10',
                '30',
                '50',
                '70',
                '100',
            ],'bookingStatus' => [
                'pending',
                'collected',
                'complete',
                'arrived',
                'report uploaded',
                'inprogress',
                ]]);

          
        });
       
    }
}
