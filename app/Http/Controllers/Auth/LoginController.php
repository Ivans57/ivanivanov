<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

//We need the line below to use localization. 
use App;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/admin/start';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'destroy']);
    }
    
    public function create() {
        return view('adminpages.admin_login');
    }
    
    public function store() {
        if (! auth()->attempt(request(['name', 'password']))) {
            return $this->show_error_message();
        }      
        if (App::isLocale('en')) {
            return redirect('admin/start');
        } else {
            return redirect('ru/admin/start');
        }
    }
    
    public function destroy() {
        auth()->logout();
        
        if (App::isLocale('en')) {
            return redirect('admin');
        } else {
            return redirect('ru/admin');
        }
    }
    
    private function show_error_message () {      
        if (App::isLocale('en')) {
            return back()->withErrors([
            'message' => 'Please check your credentials and try again.'
        ]);
        } else {
            return back()->withErrors([
            'message' => 'Пожалуйста, проверьте вводимые данные и попробуйте снова.'
        ]);
        }
    }
}
