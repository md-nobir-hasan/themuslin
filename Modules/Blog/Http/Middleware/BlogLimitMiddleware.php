<?php

namespace Modules\Blog\Http\Middleware;

use App\Helpers\FlashMsg;
use Closure;
use Illuminate\Http\Request;
use Modules\Blog\Entities\Blog;

class BlogLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $blog_count = Blog::count();
        $blog_limit = tenant()?->payment_log?->package?->blog_permission_feature;

        if ($blog_count >= $blog_limit)
        {
            return back()->with(FlashMsg::explain('danger','You can not upload more blogs due to your blog upload limit!'));
        }

        return $next($request);
    }
}
