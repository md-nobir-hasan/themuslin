<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserEmailVerify
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth("web")->check() && auth("web")->user()->email_verified == 0 && empty(get_static_option('disable_user_email_verify'))){
            return redirect()->route('user.email.verify');
        }else if (auth("vendor")->check() && auth("vendor")->user()->email_verified == 0 && empty(get_static_option('disable_vendor_email_verify')) && empty(get_static_option('disable_vendor_email_verify'))){
            return redirect()->route('vendor.email.verify');
        }

        return $next($request);
    }
}
