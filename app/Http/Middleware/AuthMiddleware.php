<?php

namespace App\Http\Middleware;

use Closure;

//This middleware is required to redirect an unauthenticated user 
//to login page when attempting to access any admin panel page.

class AuthMiddleware
{
    public function handle($request, Closure $next) {
        if (auth()->guest()) {
            return redirect('admin');
        }

        return $next($request);
    }
}
