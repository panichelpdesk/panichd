<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models;

class EnvironmentReadyMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\PanicHDMember::count() != 0
            and Models\Category::count() != 0
            and Models\Priority::count() != 0
            and Models\Status::count() != 0) {
            return $next($request);
        }

        return redirect()->back()->with('warning', trans('panichd::lang.environment-not-ready'));
    }
}
