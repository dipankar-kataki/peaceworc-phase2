<?php

namespace App\Http\Controllers\Caregiver\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse, WelcomeNotification;
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                if ( ! Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => Role::Caregiver]) ){
                    return $this->error('Invalid credentials. User unauthorized',null, 'null', 401);
                }else{
                    
                    $user = User::where('email', $request->email)->firstOrFail();

                    $auth_token =  $user->createToken('auth_token')->plainTextToken;
                    $user_data = [
                        'name' => $user->name,
                        'email' => $user->email
                    ];

                    $check_app_device_token_exists = AppDeviceToken::where('fcm_token', $request->fcm_token)->first();

                    if($check_app_device_token_exists){
                        $data=[];
                        $data['message']= "Welcome Back!. ".$user->name;
                        $token = [];
                        $token[] = $check_app_device_token_exists->fcm_token;
                
                        $this->sendWelcomeNotification($token, $data);
                    }else{

                        AppDeviceToken::create([
                            'user_id' => Auth::user()->id,
                            'fcm_token' => $request->fcm_token,
                            'role' => $user->role
                        ]);

                        $data=[];
                        $data['message']= "Welcome Back! ".$user->name;
                        $token = [];
                        $token[] = $request->fcm_token;
                
                        $this->sendWelcomeNotification($token, $data);
                    }

                    return $this->success('Great! Login Successful', $user_data, $auth_token, 200);
                }
            }catch(\Exception $e){
                Log::error('Not Able To Login ====>', $e);
                return $this->error('Opps! Something Went Wrong.'.$e->getMessage(), null, null, 500);
            }
        }

    }
}
