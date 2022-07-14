<?php

namespace AliAlizade\Transfer\Providers;

use Carbon\Laravel\ServiceProvider;

class TransferServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {
        // $this->app->bind(IUser::class, Customer::class);
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}