<?php

namespace App\Http\Middleware;

use Closure;

//We need the line below to use localization. 
use App;

//This middleware is required to redirect an unauthenticated user 
//to login page when attempting to access any admin panel page.

class AuthMiddleware
{
    public function handle($request, Closure $next) {
        if (auth()->guest()) {
            return $this->redirect_to_login();
        }
        return $next($request);
    }
    
    private function redirect_to_login() {
        if (App::isLocale('en')) {
            return redirect('admin');
        } else {
            return redirect('ru/admin');
        }
    }
}
