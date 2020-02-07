<?php

namespace Laraveldbtransactions;

use Illuminate\Support\ServiceProvider;

class TransactionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app('router')->aliasMiddleware('TransactionHandler', Middleware\TransactionHandler::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
