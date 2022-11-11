<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller
{
    use ApiResponse;
    public function signUp(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $this->error('Opps! Validation Error. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $create = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => Role::Agency
                ]);
                if($create){
                    $token = $create->createToken('auth_token')->plainTextToken;
                    return $this->success('Great! SignUp Completed Successfully', null, $token, 201);
                }else{
                    return $this->success('Oops! SignUp Failed', null, null, 500);
                }
            }catch(\Exception $e){
                return $this->error('Opps! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
