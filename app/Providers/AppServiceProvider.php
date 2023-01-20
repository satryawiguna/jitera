<?php

namespace App\Providers;

use App\Service\AuthService;
use App\Service\BaseService;
use App\Service\Contract\IAuthService;
use App\Service\Contract\IService;
use App\Service\Contract\IUserService;
use App\Service\UserService;
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
        $this->app->bind(IService::class, BaseService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserService::class, UserService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
