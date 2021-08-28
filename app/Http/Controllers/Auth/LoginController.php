<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
           return back()->withErrors([
               'message' => 'Please check your credentials and try again.'
           ]);
       }
        
        return redirect('admin/start');;
    }
    
    public function destroy() {
        auth()->logout();
        
        return redirect('admin');
    }
}
