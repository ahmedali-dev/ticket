<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $seesion = session("locale", config('app.locale'));
        if (!in_array($seesion, ['en', 'ar'])) {
            $session = 'ar';
        }

        App::setLocale($seesion);
        return $next($request);
    }
}
