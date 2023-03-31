<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    use ApiResponse;
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
                    return $this->error('Invalid credentials. User unauthorized',null, 'null', 401);
                }else if($user->is_otp_verified == 0){
                    return $this->error('Invalid credentials. User unauthorized',null, 'null', 401);
                }else{
                    if ( ! Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => Role::Agency_Owner]) ){
                        return $this->error('Invalid credentials. User unauthorized',null, 'null', 401);
                    }else{
                        $user = User::where('email', $request->email)->firstOrFail();
                        $auth_token =  $user->createToken('auth_token')->plainTextToken;
                        $data = [
                            'name' => $user->name,
                            'email' => $user->email
                        ];

                        $check_app_device_token_exists = AppDeviceToken::where('fcm_token', $request->fcm_token)->first();

                        if($check_app_device_token_exists->fcm_token != null){
                            $data=[];
                            $data['message']= "Welcome Back! ".$user->name;
                            $token = [];
                            $token[] = $check_app_device_token_exists->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $data);
                        }else{

                            $create = AppDeviceToken::create([
                                'user_id' => Auth::user()->id,
                                'fcm_token' => $request->fcm_token,
                                'role' => $user->role
                            ]);

                            $data=[];
                            $data['message']= "Welcome Back! ".$user->name;
                            $token = [];
                            $token[] = $create->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $data);
                        }
                        
    
                        return $this->success('Great! Login Successful', $data, $auth_token, 200);
                    }
                }

                
            }catch(\Exception $e){
                return $this->error('Opps! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
