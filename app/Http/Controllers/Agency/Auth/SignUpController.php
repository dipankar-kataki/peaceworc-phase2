<?php

namespace App\Http\Controllers\Agency\Auth;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Mail\SendEmailVerificationOTPMail;
use App\Models\AgencyProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class SignUpController extends Controller
{
    use ApiResponse;

    public function checkEmailExists(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:users',
        ]);

        if($validator->fails()){
            return $this->error('Opps! Validation Error. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $otp = rand(100000, 999999);
                Cache::put('otp', $otp, now()->addMinutes(1));
                Mail::to($request->email)->queue(new SendEmailVerificationOTPMail($otp));
    
                return $this->success('Great! Email Verification OTP Sent Successfully. ', null, null, 200);
            }catch(\Exception $e){
                Log::error('Email Verification Mail Error', $e->getMessage());
                return $this->error('Oops!. Something Went Wrong.', null, null, 200);
            }
            
        }
    }

    public function signUp(Request $request){
        $validator = Validator::make($request->all(),[
            'otp' => 'required', 
            'company_name' => 'required|string',
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'required|min:6'
        ]);

        if($validator->fails()){
            return $this->error('Opps! Validation Error. '.$validator->errors()->first(), null, null, 400);
        }else{

            $otp = $request->otp;
            if(Cache::get('otp') != $otp){
                return $this->error('Opps! Invalid OTP. Signup Failed', null, null, 400);
            }else{
                Cache::forget('otp');
                try{
                    $create = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'email_verified_at' => date('Y-m-d H:i:s'),
                        'password' => Hash::make($request->password),
                        'role' => Role::Agency_Owner
                    ]);
    
                    if($create){
                        $user = User::where('email', $request->email)->first();
                        AgencyProfileRegistration::create([
                            'user_id' => $user->id,
                            'company_name' => $request->company_name
                        ]);
                        $token = $create->createToken('auth_token')->plainTextToken;
                        return $this->success('Great! SignUp Completed Successfully', null, $token, 201);
                    }else{
                        return $this->error('Oops! SignUp Failed', null, null, 500);
                    }
                }catch(\Exception $e){
                    return $this->error('Opps! Something Went Wrong.', null, null, 500);
                }
            }
            
        }
    }
}
