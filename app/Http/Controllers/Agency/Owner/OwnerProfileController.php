<?php

namespace App\Http\Controllers\Agency\Owner;

use App\Common\AgencyNotificationType;
use App\Http\Controllers\Controller;
use App\Mail\ChangePasswordMail;
use App\Models\AgencyNotification;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\WelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OwnerProfileController extends Controller
{
    use ApiResponse, WelcomeNotification;

    public function editPhone(Request $request){

        $validator = Validator::make($request->all(),[
            'phone' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $check_if_phone_exists = User::where('phone', $request->phone)->exists();
                if($check_if_phone_exists){
                    return $this->error('Oops! Phone Number Already Exist.', null, null, 400);
                }else{
                    $update = User::where('id', Auth::user()->id)->update([
                        'phone' => $request->phone
                    ]);
        
                    if($update){
                        return $this->success('Great! Owner Phone Number Added Successfully.', null, null, 201);
                    }else{
                        return $this->error('Oops! Something Went Wrong. Failed To Add Phone Number.', null, null, 500);
                    }
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Server Error', null, null, 500);
            }
            
        }
    }


    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{

            try{
                $get_details = User::where('id', Auth::user()->id)->first();

                if (!Hash::check($request->current_password, $get_details->password)) {
                    return $this->error('Oops! Current password not matched.', null, null, 400);
                }else{

                    if(Hash::check($request->password, $get_details->password)){
                        return $this->error('Oops! You have already used this password. Please create a new strong password.', null, null, 400);
                    }else{
                        
                        $update = User::where('id', Auth::user()->id)->update([
                            'password' => Hash::make($request->password)
                        ]);
    
                        $fcm_token = AppDeviceToken::where('user_id', $get_details->id)->first();
                        
                        Mail::to($get_details->email)->send(new ChangePasswordMail);
    
                        $message = "Hurray! Owner password changed successfully.";
                        $token = $fcm_token;
                
                        $this->sendWelcomeNotification($token, $message);

                        AgencyNotification::create([
                            'user_id' => Auth::user()->id,
                            'content' => 'Hurray! Password has been changed successfully.',
                            'type' => AgencyNotificationType::Security
                        ]);
        
                        if($update){
                            return $this->success('Great! Owner password changed successfully.', null, null, 201);
                        }else{
                            return $this->error('Oops! Something went wrong. Failed to change password.', null, null, 500);
                        }
                    }
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong. Server Error.', null, null, 500);
            }
            
        }
    }
}
