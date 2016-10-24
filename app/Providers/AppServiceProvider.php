<?php

namespace App\Providers;

use App\Facade\ResponseHelper;
use Illuminate\Support\ServiceProvider;
use App\Advice;
use App\Elastic\Advice as ElasticAdvice;
use App\Repositories\Elastic\AdviceRepository;
use App\Repositories\Eloquent\PageRepository;

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

        $this->app->bind('PageRepo', function(){
            return new PageRepository();
        });
    }
}
    