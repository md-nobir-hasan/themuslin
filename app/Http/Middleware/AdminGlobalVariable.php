<?php

namespace App\Http\Middleware;

use App\Cause;
use App\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminGlobalVariable
{

    public function handle(Request $request, Closure $next)
    {
       
        $lang = !empty(session()->get('lang')) ? session()->get('lang') :
            Cache::remember("all-languages",60,function (){ return Language::where('default',1)->first()->slug; });

        $all_languages = Cache::remember("all-languages",60,function (){ return Language::all(); });
        $home_page_variant_number = get_static_option('home_page_variant');
        $admin_languages = Language::where(['default'=>1,'status'=>'publish'])->first();
        $admin_default_lang = $admin_languages->slug;

        $data = [
            'lang' => $lang,
            'all_languages' => $all_languages,
            'home_page_variant_number' => $home_page_variant_number,
            'admin_default_lang' => $admin_default_lang,
        ];

        view()->composer('*', function ($view) use ($data) {
            $view->with($data);
        });
      
        return $next($request);
    }
}
