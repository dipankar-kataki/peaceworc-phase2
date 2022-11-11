<?php

namespace App\Http\Controllers\Agency\Auth;

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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Opps! Validation Error. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                if ( ! Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => Role::Agency]) ){
                    return $this->error('Invalid credentials. User unauthorized',null, 'null', 401);
                }else{
                    $user = User::where('email', $request->email)->firstOrFail();
                    $token =  $user->createToken('auth_token')->plainTextToken;
                    $data = [
                        'name' => $user->name,
                        'email' => $user->email
                    ];

                    return $this->success('Great! Login Successful', $data, $token, 200);
                }
            }catch(\Exception $e){
                return $this->error('Opps! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
