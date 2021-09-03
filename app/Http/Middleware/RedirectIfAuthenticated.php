<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

//We need the line below to use localization. 
use App;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return $this->redirect_to_admin_start();
        }

        return $next($request);
    }
    
    private function redirect_to_admin_start() {
        if (App::isLocale('en')) {
            return redirect('admin/start');
        } else {
            return redirect('ru/admin/start');
        }
    }
}
