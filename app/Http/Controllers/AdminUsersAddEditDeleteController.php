<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

class AdminUsersAddEditDeleteController extends Controller
{
    public function __construct() {
        //The line below is required not to allow an unauthenticated user to open pages related to this controller.
        $this->middleware('auth.custom');
    }
    
    public function create_for_albums() {
        $a = 'Hello World!';
        return $a;
    }
}
