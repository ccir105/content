<?php

namespace App\Providers;

use App\Facade\ResponseHelper;
use Illuminate\Support\ServiceProvider;
use App\Advice;
use App\Elastic\Advice as ElasticAdvice;
use App\Repositories\AdviceRepository;

class AppServiceProvider extends ServiceProvider
{
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
        $this->app->bind('responseHelper',function(){
            return new ResponseHelper();
        });

        $this->app->bind('AdviceRepo', function(){
            return new AdviceRepository(new ElasticAdvice, new Advice);
        });
    }
}
    