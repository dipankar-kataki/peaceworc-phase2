<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LogOutController extends Controller
{
    use ApiResponse;
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return $this->success('Great! Logged Out Successfully', null, null, 200);
    }
}
