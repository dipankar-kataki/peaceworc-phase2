<?php

namespace App\Http\Controllers\Caregiver\Auth;

use App\Common\Role;
use App\Events\SendOtpMailEvent;
use App\Http\Controllers\Controller;
use App\Mail\SendEmailVerificationOTPMail;
use App\Models\AppDeviceToken;
use App\Models\CaregiverProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\WelcomeNotification;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller
{
    use ApiResponse, WelcomeNotification;

    public function signUp(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'email' => 'required|email',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $otp = rand(100000, 999999);

                $user = User::where('email', $request->email)->first();

                if( $user != null){

                    if($user->is_otp_verified == 0){

                        User::where('email', $request->email)->update([
                            'otp' =>  $otp,
                            'otp_validity' => date('Y-m-d h:i:s')
                        ]);

                        Event::dispatch(new SendOtpMailEvent($request->email, $otp));

                        return $this->success('Great! Email Verification OTP Sent Successfully. ', null, null, 201);
                    }else{
                        return $this->error('Oops! User Already Registered. ', null, null, 400);
                    }
                }else{

                    $create = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role' => Role::Caregiver,
                        'otp' =>  $otp,
                        'otp_validity' => date('Y-m-d h:i:s')
                    ]);
    
                    if($create){
                        
                        AppDeviceToken::create([
                            'user_id' => $create->id,
                            'fcm_token' => $request->fcm_token,
                            'role' => Role::Caregiver,
                        ]);

                        Event::dispatch(new SendOtpMailEvent($request->email, $otp));
                        return $this->success('Great! Email Verification OTP Sent Successfully', null, null, 201);
                    }else{
                        return $this->error('Oops! SignUp Failed', null, null, 400);
                    }
                }
                
            }catch(\Exception $e){
                return $this->error('Opps! Something Went Wrong.', null, null, 500);
            }
        }
        
    }

    public function resendOtp(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return $this->error('Opps!'.$validator->errors()->first(), null, null, 400);
        }else{
            $is_email_exists = User::where('email', $request->email)->exists();
            if(!$is_email_exists){
                return $this->error('Opps! Failed To Sent OTP. Invalid Email Id.', null, null, 400);
            }else{
                try{
                    $otp = rand(100000, 999999);

                    User::where('email', $request->email)->update([
                        'otp' =>  $otp,
                        'otp_validity' => date('Y-m-d h:i:s')
                    ]);

                    Event::dispatch(new SendOtpMailEvent($request->email, $otp));

                    return $this->success('Great! Email Verification OTP Sent Successfully. ', null, null, 201);
                    
                }catch(\Exception $e){
                    return $this->error('Oops! Something Went Wrong.', null, null, 500);
                }
                
            }
        }
    }

    public function verifyOtp(Request $request){
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
                                'is_otp_verified' => 1,
                                'is_agreed_to_terms' => 1,
                                'email_verified_at' => date('Y-m-d h:i:s')
                            ]);

                            $app_device_token = AppDeviceToken::where('user_id', $get_user_details->id)->first();

                            if($app_device_token->fcm_token != null){
                                
                                $message= "Welcome Aboard! Thankyou For Joining Peaceworc.";
                                $token = $app_device_token->fcm_token;
                        
                                $this->sendWelcomeNotification($token, $message);
                            }

                            $auth_token = $get_user_details->createToken('auth_token')->plainTextToken;
                            
                            return $this->success('Great! OTP Verified. SignUp Successful.', null, $auth_token, 201);

                        }
                }
            }catch(\Exception $e){
                return $this->error('Oops!. Something Went Wrong.', null, null, 500);
            }
            
        }
    }
}
