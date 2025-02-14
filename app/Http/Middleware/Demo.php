<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Route;

class Demo
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
        if(auth('admin')->check()){
            if(auth('admin')->user()->username === 'administrator'){
                return $next($request);
            }
        }

        $not_allow_path = [
          'admin-home',
          'seller',
          'buyer',
          'admin-home/pos/view'
        ];

        $allow_path = [
            'admin-home/visited/os',
            'admin-home/visited/browser',
            'admin-home/visited/device',
            'admin-home/visited-url',
            'admin-home/media-upload/all',
            'seller/logout',
            'buyer/send',
            'seller/send',
            'broadcasting/auth',
            'seller/get-dependent-subcategory',
            'seller/get-child-category-by-subcategory',
            //safecart
            '/chat/fetch-chat-user-record',
            ];


        // check previous url
        if(
            ($request->isMethod('POST') || $request->isMethod('PUT')) &&
            Str::contains(url()->previous(), "admin-home/pos/view") && Route::currentRouteName() === "admin.pos.checkout"

        ){
            return response()->json(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
        }

        $contains = Str::contains($request->path(), $not_allow_path);
        if($request->isMethod('POST') || $request->isMethod('PUT')) {

            if($contains && !in_array($request->path(),$allow_path)){
                if ($request->ajax()){
                    return response()->json(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
                }

                return redirect()->back()->with(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
            }

        }

        if($request->ajax()) {
            if($contains && !in_array($request->path(),$allow_path)){
                return response()->json(['type' => 'warning' , 'msg' => 'This is demonstration purpose only, you may not able to change few settings, once your purchase this script you will get access to all settings.']);
            }
        }

        return $next($request);
    }
}
