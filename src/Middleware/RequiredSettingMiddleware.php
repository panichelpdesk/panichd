<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models\Setting;

class RequiredSettingMiddleware
{
    /**
     * Run the request filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $controller)
    {
        if ($controller == 'Notices') {
            if (Setting::grab('departments_notices_feature')) {
                return $next($request);
            }
        } else {
            return $next($request);
        }

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
