<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\GeneralSetting;

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
        $data  = GeneralSetting::first();

        $result = array(
                        'gs_printout_head_letter'      => $data->printout_head_letter,
                        'gs_system_logo'    => $data->system_logo,
                );

         view()->share('gs_info',$result);
    }
}
