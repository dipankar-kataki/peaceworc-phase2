<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Events\SendOtpMailEvent;
use App\Http\Controllers\Controller;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\WelcomeNotification;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use ApiResponse, WelcomeNotification;

    public function sendForgotPasswordMail(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $otp = rand(100000, 999999);
                $user = User::where('email', $request->email)->first();

                if( $user != null){

                    User::where('email', $request->email)->update([
                        'otp' =>  $otp,
                        'otp_validity' => date('Y-m-d h:i:s')
                    ]);

                    Event::dispatch(new SendOtpMailEvent($request->email, $otp));

                    return $this->success('Great! Email Verification OTP Sent Successfully. ', null, null, 201);
                   
                }else{
                    return $this->error('Oops! Failed To Send OTP. Not A Valid User.', null, null, 400);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }

    }

    public function verifyForgotOtp(Request $request){
        $validator = Validator::make($request->all(), [
            'otp' => 'required',
            'email' => 'required|email',
        ]);

        if($validator->fails()){
            return $this->error('Opps! '.$validator->errors()->first(), null, null, 400);
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
    
                        $time_diff_in_minutes = $current_time->diff(new DateTime($otp_validity_time));
                        
                        if(  $time_diff_in_minutes->i > 3){
                            return $this->error('Oops! OTP Expired.', null, null, 400);
                        }else{

                            User::where('email', $request->email)->update([
                                'is_otp_verified' => 1,
                                'is_agreed_to_terms' => 1,
                                'email_verified_at' => date('Y-m-d h:i:s')
                            ]);

                            return $this->success('Great! OTP Verified.', null, null, 201);

                        }
                    }
                }

            }catch(\Exception $e){
                return $this->error('Oops!. Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function updateForgotPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6',
            'fcm_token' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Opps! '.$validator->errors()->first(), null, null, 400);
        }else{

            try{
                $get_user_details = User::where('email', $request->email)->first();

                if($get_user_details == null){
                    return $this->error('Oops! Invalid Email Id. Failed To Update Password.', null, null, 400);
                }else{
                    User::where('email', $request->email)->update([
                        'password' => Hash::make($request->password)
                    ]);
    
                    AppDeviceToken::where('user_id', $get_user_details->id)->update([
                        'fcm_token' => $request->fcm_token,
                    ]);
    
                    $message = "Hurray! Password Recovered Successfully.";
                    $token = $request->fcm_token;
            
                    $this->sendWelcomeNotification($token, $message);

                    return $this->success('Great! Password Updated Successfully', null, null, 201);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
