<?php

namespace App\Http\Controllers\Agency\Auth;

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

                $user = User::where('email', $request->email)->first();

                if($user == null){
                    return $this->error('Invalid credentials. User Not Found.',null, 'null', 401);
                }else if($user->is_otp_verified == 0){
                    return $this->error('Invalid credentials. User Unauthorized',null, 'null', 401);
                }else{
                    if ( ! Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => Role::Agency_Owner]) ){
                        return $this->error('Invalid credentials. Failed To Login.',null, 'null', 401);
                    }else{

                        $user = User::where('email', $request->email)->firstOrFail();
                        $auth_token =  $user->createToken('auth_token')->plainTextToken;
                        $user_data = [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email
                        ];

                        $check_app_device_token_exists = AppDeviceToken::where('fcm_token', $request->fcm_token)->first();

                        if($check_app_device_token_exists != null){

                            $message= "Welcome Back! ".$user->name;
                            $token = $check_app_device_token_exists->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $message);
                        }else{

                            if($user->role == 'Agency_Owner'){
                                $user->role = 3;
                            }
                            if($user->role == 'Agency_Admin'){
                                $user->role = 4;
                            }
                            if($user->role == 'Agency_Operator'){
                                $user->role = 5;
                            }

                            AppDeviceToken::create([
                                'user_id' => Auth::user()->id,
                                'fcm_token' => $request->fcm_token,
                                'role' => $user->role
                            ]);

                            $message= "Welcome Back! ".$user->name;
                            $token = $request->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $message);
                        }
                        
    
                        return $this->success('Great! Login Successful', $user_data, $auth_token, 200);
                    }
                }

                
            }catch(\Exception $e){
                return $this->error('Opps! Something Went Wrong.'.$e, null, null, 500);
            }
        }
    }
}
