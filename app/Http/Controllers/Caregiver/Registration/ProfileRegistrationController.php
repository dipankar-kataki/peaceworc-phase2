<?php

namespace App\Http\Controllers\Caregiver\Registration;

use App\Http\Controllers\Controller;
use App\Models\CaregiverProfileRegistration;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileRegistrationController extends Controller
{
    use ApiResponse;

    public function profileRegistration(Request $request){
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:1048',
            'phone' => 'required|numeric',
            'dob' => 'required|date_format:m-d-Y',
            'gender' => 'required',
            'ssn' => 'required',
            'full_address' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $check_if_phone_exists = CaregiverProfileRegistration::where('phone', $request->phone)->exists();
                $check_if_ssn_exists = CaregiverProfileRegistration::where('ssn', $request->ssn)->exists();
                if($check_if_phone_exists){
                    return $this->error('Oops! Failed To Register. Phone Number Associated With Another User.', null, null, 400);
                }else if($check_if_ssn_exists){
                    return $this->error('Oops! Failed To Register. SSN Number Associated With Another User.', null, null, 400);
                }else{

                    if ($request->hasFile('photo')) {
                        $image = time() . '.' . $request->photo->extension();
                        $request->photo->move(public_path('Caregiver/Uploads/Profile/image/'), $image);
                        $imageName = 'Caregiver/Uploads/Profile/image/' . $image;
                    }
                   
                    $create = CaregiverProfileRegistration::create([
                        'user_id' => Auth::user()->id,
                        'photo' => $imageName,
                        'phone' => $request->phone,
                        'dob' =>  Carbon::parse($request->dob)->format('Y-m-d'),
                        'gender' => $request->gender,
                        'ssn' => $request->ssn,
                        'full_address' => $request->full_address,
                        'experience' => $request->experience,
                        'job_type' => $request->job_type,
                        'street' => $request->street,
                        'city_or_district' => $request->city_or_district,
                        'state' => $request->state,
                        'zip_code' => $request->zip_code,
                        'education' => json_encode($request->education),
                        'certificate' => json_encode($request->certificate)
                    ]);

                    if($create){
                        return $this->success('Great! Registration Successfull.', null, null, 201);
                    }else{
                        return $this->error('Oops! Something Went Wrong. Registration Failed.', null, null, 500);
                    }
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
            
        }
    }
}
