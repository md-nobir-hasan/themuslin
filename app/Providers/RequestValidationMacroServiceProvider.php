<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Mews\Purifier\Facades\Purifier;

class RequestValidationMacroServiceProvider extends ServiceProvider
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
        Request::macro('sanitize_html', fn ($value) =>  htmlspecialchars(strip_tags(Request::get($value))));
        Request::macro('custom_html', fn ($value) =>  Purifier::clean(Request::get($value)));
    }
}
