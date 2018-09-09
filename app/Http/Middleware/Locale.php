<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Support\Facades\App;

class Locale
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
        switch ( $request->segment(1) ) {
            case 'ru':
                App::setLocale('ru');
                break;
            default:
                App::setLocale('en');
                break;
        }
        
        return $next($request);
    }
}
