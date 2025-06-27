<?php

namespace Modules\BlogSimple\Providers;

use Illuminate\Support\ServiceProvider;

class BlogSimpleServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        //
    }
}
