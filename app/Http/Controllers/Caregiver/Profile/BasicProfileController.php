<?php

namespace App\Http\Controllers\Caregiver\Profile;

use App\Http\Controllers\Controller;
use App\Models\CaregiverProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BasicProfileController extends Controller
{
    use ApiResponse;
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
                    return $this->error('Oops! Current Password Not Matched', null, null, 400);
                }else{
                    
                    try{
                        $update = User::where('id', Auth::user()->id)->update([
                            'password' => Hash::make($request->password)
                        ]);
        
                        if($update){
                            return $this->success('Great! Password Successfully.', null, null, 201);
                        }else{
                            return $this->error('Oops! Something Went Wrong. Failed To Change Password.', null, null, 500);
                        }
                    }catch(\Exception $e){
                        Log::error('Failed To Change Caregiver Password ===>', $e->getMessage());
                        return $this->error('Oops! Something Went Wrong. Server Error.', null, null, 500);
                    }
                    
                    
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Server Error.', null, null, 500);
            }
            
        }
    }

    public function getDetails(){
        try{
            $get_details = CaregiverProfileRegistration::where('user_id', Auth::user()->id)->first();
            return $this->success('Great! Profile Fetched Successfully.', $get_details, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
    }
}
