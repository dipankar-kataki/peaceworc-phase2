<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;
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

                    return $this->error('Login Failed. Credentials did not match with our records.', null, null, 400);
                }else{
                    $user = User::where('email', $request->email)->firstOrFail();
                    // $auth_token =  $user->createToken('auth_token')->plainTextToken;

                    // return $this->success('Login Successful', null, $auth_token, 200 );
                    return view('admin.dashboard.index')->with(['user_data' => $user]);
                    // return response()->json(['message' => 'Great! Login Successful.', 'url' => "{{route('admin.dashboard')}}", 'status' => 1]);
                }
            }catch(\Exception $e){
                return response()->json(['message' => 'Oops! Something Went Wrong.',  'status' => 0]);
            }
        }
    }
}
