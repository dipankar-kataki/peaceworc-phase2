<?php

namespace App\Http\Controllers\Caregiver\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class LogOutController extends Controller
{
    use ApiResponse;
    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return $this->success('Great! Logged Out Successfully', null, null, 200);
    }
}
