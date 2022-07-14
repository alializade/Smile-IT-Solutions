<?php

namespace AliAlizade\Customer\Providers;

use Illuminate\Support\ServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}