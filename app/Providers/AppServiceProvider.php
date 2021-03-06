<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Laravel\Passport\Passport;

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
    protected $policies = [
        \App\Model::class => \App\Policies\ModelPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
        Schema::defaultStringLength(191);

        Gate::define('isAdmin', function ($user) {
            return ($user->roles == 'Admin') ? true : false;
        });

        Gate::define('isUser', function ($user) {
            return $user->roles == 'User';
        });

        Passport::routes();

    }
}