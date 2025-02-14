<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  Request  $request
     * @return string
     */
    protected function redirectTo($request): string
    {
        if (! $request->expectsJson()) {
            if ($request->is('admin-home') || $request->is('admin-home/*')) {
                return route('admin.login');
            }

            if ($request->is('user-home') || $request->is('user-home/*')) {
                return route('user.login');
            }

            if ($request->is('vendor-home') || $request->is('vendor-home/*')) {
                return route('vendor.login');
            }

            return route('user.login');
        }

        return '';
    }
}
