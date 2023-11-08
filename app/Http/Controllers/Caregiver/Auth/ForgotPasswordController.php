<?php

namespace App\Http\Controllers\Caregiver\Auth;

use App\Events\PasswordChangeMailEvent;
use App\Events\SendOtpMailEvent;
use App\Http\Controllers\Controller;
use App\Mail\ChangePasswordMail;
use App\Mail\PasswordChangeMail;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use DateTime;

class ForgotPasswordController extends Controller
{
    use ApiResponse, WelcomeNotification;
    public function sendOTPEmail(Request $request){
        $validator = Validator::make($request->all(), [
            "email"=> "required|email",
        ]);

        if ($validator->fails()) {
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $user = User::where('email', $request->email)->first();
                if($user == null){
                    return $this->error('Oops! Invalid user. Email Id does not exists.', null, null, 400);
                }else{
                    $otp = rand(100000, 999999);

                    User::where('email', $request->email)->update([
                        'otp' =>  $otp,
                        'otp_validity' => date('Y-m-d h:i:s')
                    ]);

                    Event::dispatch(new SendOtpMailEvent($user->email, $otp));

                    return $this->success('Great! Email verification OTP sent successfully. ', null, null, 201);
                }


            }catch(\Exception $e){  
                return $this->error('Oops! Something went wrong.', null, null, 500);
            }
        }
    }


    public function verifyOTP(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $get_user_details = User::where('email', $request->email)->first();
                if($get_user_details == null){
                    return $this->success('Oops! Invalid Email Id. OTP not verified', null, null, 400);
                }else{
                    if($get_user_details->otp != $request->otp){
                        return $this->error('Oops! Invalid OTP.', null, null, 400);
                    }else{
                        $otp_validity_time =  $get_user_details->otp_validity;
    
                        $current_time = new DateTime();

                        // $otp_val = new Carbon($otp_validity_time);

                        // $time_diff_in_minutes = $current_time->diffInMinutes($otp_val);
                        $time_diff_in_minutes =  $current_time->diff(new DateTime($otp_validity_time));

                        // return response()->json(['Difference' =>  $time_diff_in_minutes->i]);

                        if( $time_diff_in_minutes->i >= 3){
                            return $this->error('Oops! OTP Expired.', null, null, 400);
                        }else{


                            $get_user_details->update([
                                'email_verified_at' => date('Y-m-d h:i:s')
                            ]);

                            // $app_device_token = AppDeviceToken::where('user_id', $get_user_details->id)->first();

                            // if($app_device_token->fcm_token != null){
                                
                            //     $message= "Welcome Aboard! Thankyou For Joining Peaceworc.";
                            //     $token = $app_device_token->fcm_token;
                        
                            //     $this->sendWelcomeNotification($token, $message);
                            // }

                            // $auth_token = $get_user_details->createToken('auth_token')->plainTextToken;

                            // $verified_user_id = $get_user_details->id;
                            
                            return $this->success('Great! OTP verified successfully.', null, null, 201);

                        }
                    }
    
                }
            }catch(\Exception $e){
                return $this->error('Opps! Something went wrong.', null, null, 500);
            }
        }
    }

    public function createNewPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{

            try{

                $user_details = User::where('email', $request->email)->first();

                if($user_details == null){
                    return $this->error('Oops! Invalid Email Id. Failed to update password.', null, null, 400);
                }else{

                    if (Hash::check($request->password, $user_details->password)) {
                        return $this->error('Oops! You have already used this password. Please create a new strong password.', null, null, 400);
                    }else{
                        User::where('email', $request->email)->update([
                            'password' => Hash::make($request->password),
                        ]);
    
                        AppDeviceToken::where('user_id', $user_details->id)->update([
                            'fcm_token' => $request->fcm_token,
                        ]);
                        
                        Mail::to($user_details->email)->send(new ChangePasswordMail);
    
                        $message = "Hurray! Password Recovered Successfully.";
                        $token = $request->fcm_token;
                
                        $this->sendWelcomeNotification($token, $message);
    
    
    
                        return $this->success('Great! Password Changed successfully.', null, null, 200);
                    }
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong.', null, null, 500);
            }
            
        }
    }  
}
