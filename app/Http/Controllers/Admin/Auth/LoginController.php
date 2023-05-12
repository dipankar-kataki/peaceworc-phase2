<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required | email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['message'=>'Oops! Validation Failed. '.$validator->errors()->first(), 'status' => 0]);
        }else{
            try{
                if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => Role::Admin])) {
                    return response()->json(['message' => 'Login Failed. Credentials did not match with our records.', 'status' => 0]);
                }else{
                    $user = User::where('email', $request->email)->firstOrFail();
                    $auth_token =  $user->createToken('auth_token')->plainTextToken;
                    // return response()->json(['message' => 'Login Successful', 'url' => "/admin/dashboard",'status' => 1]);
                    return response()->json(['message' => 'Login Successful', 'access_token' => $auth_token, 'status' => 1]);
                }
            }catch(\Exception $e){
                return response()->json(['message' => 'Something Went Wrong While Login.', 'status' => 0]);
            }
        }
    }
}
