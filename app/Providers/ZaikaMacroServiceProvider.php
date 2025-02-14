<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Mews\Purifier\Facades\Purifier;

class ZaikaMacroServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Request::macro('sanitize_html', function ($value) {
            return htmlspecialchars(strip_tags(Request::get($value)));
        });

        Request::macro('custom_html', function ($value) {
            return Purifier::clean(Request::get($value));
        });
    }
}
