<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogOutController extends Controller
{
    public function logout(Request $request){
        
        Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
