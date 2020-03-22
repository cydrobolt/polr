<?php

namespace App\Http\Middleware;

use App;
use Closure;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = locale_accept_from_http($request->server->get('HTTP_ACCEPT_LANGUAGE'));
        App::setLocale($lang);
        return $next($request);
    }
}
