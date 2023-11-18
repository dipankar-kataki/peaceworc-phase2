<?php

namespace App\Http\Controllers\Caregiver\Profile;

use App\Http\Controllers\Controller;
use App\Mail\ChangePasswordMail;
use App\Models\AppDeviceToken;
use App\Models\CaregiverCertificate;
use App\Models\CaregiverEducation;
use App\Models\CaregiverFlag;
use App\Models\CaregiverProfileRegistration;
use App\Models\CaregiverStatusInformation;
use App\Models\Flag;
use App\Models\Reward;
use App\Models\Strike;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                    return $this->error('Oops! Current password not matched', null, null, 400);
                }else{
                    
                    try{

                        if(Hash::check($request->password, $get_details->password)){
                            return $this->error('Oops! You have already used this password. Please create a new strong password.', null, null, 400);
                        }else{
                            
                            $update = User::where('id', Auth::user()->id)->update([
                                'password' => Hash::make($request->password)
                            ]);
    
                            $fcm_token = AppDeviceToken::where('user_id', $get_details->id)->first();
                        
                            Mail::to($get_details->email)->send(new ChangePasswordMail);
    
                            $message = "Hurray! Password changed successfully.";
                            $token = $fcm_token;
                    
                            $this->sendWelcomeNotification($token, $message);
            
                            if($update){
                                return $this->success('Great! Password changed successfully.', null, null, 201);
                            }else{
                                return $this->error('Oops! Something Went Wrong. Failed to change password.', null, null, 500);
                            }
                        }
                        
                    }catch(\Exception $e){
                        return $this->error('Oops! Something went wrong. Failed to change password.', null, null, 500);
                    }
                    
                    
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong. Server Error.', null, null, 500);
            }
            
        }
    }

    public function getDetails(){
        try{
            $get_details = CaregiverProfileRegistration::with('user:id,email')
                            ->where('user_id', Auth::user()->id)
                            ->select('user_id','photo','bio','phone','dob','gender','experience','care_completed')
                            ->first();
            $get_education = CaregiverEducation::where('user_id', Auth::user()->id)->get();
            $get_certificate = CaregiverCertificate::where('user_id', Auth::user()->id)->get();
            $get_profile_status = CaregiverStatusInformation::where('user_id', Auth::user()->id)
                                ->select('is_basic_info_added', 'is_optional_info_added', 'is_documents_uploaded', 'is_profile_approved')
                                ->first();
            if($get_profile_status == null){
                $get_profile_status = null;
            }
            
            $get_rewards_earned = CaregiverProfileRegistration::where('user_id', Auth::user()->id)->first();
        

            $strikes = Strike::where('user_id', Auth::user()->id)->count();

            $flags = CaregiverFlag::where('user_id', Auth::user()->id)->count();

            $details = [
                'profile_completion_status' => $get_profile_status,
                'basic_info' => $get_details,
                'rewards' => $get_rewards_earned,
                'strikes' => $strikes,
                'flags' => $flags,
                'education' => $get_education,
                'certificate' => $get_certificate
            ];
            return $this->success('Great! Profile Fetched Successfully.', $details, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.'.$e, null, null, 500);
        }
    }

    public function addBio(Request $request){

        $validator = Validator::make($request->all(), [
            'bio' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                CaregiverProfileRegistration::where('user_id', Auth::user()->id)->update([
                    'bio' => $request->bio
                ]);
                return $this->success('Great! Bio Added Successfully.', null, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
        
    }


    public function addEducation(Request $request){

        $validator = Validator::make($request->all(), [
            'school_or_university' => 'required',
            'degree' => 'required',
            'start_year' => 'required',
            'end_year' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                CaregiverEducation::create([
                    'user_id' => Auth::user()->id,
                    'school_or_university' => $request->school_or_university,
                    'degree' => $request->degree,
                    'start_year' => $request->start_year,
                    'end_year' => $request->end_year
                ]);
                return $this->success('Great! Education Added Successfully.', null, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
        
    }

    public function editEducation(Request $request){

        $validator = Validator::make($request->all(), [
            'edu_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $get_edu_details = CaregiverEducation::where('id', $request->edu_id)->where('user_id', Auth::user()->id)->first();

                if($get_edu_details == null){
                    return $this->error('Oops! Something Went Wrong. No Data Found', null, null, 500);
                }else{
                    CaregiverEducation::where('id', $request->edu_id)->where('user_id', Auth::user()->id)->update([
                        'school_or_university' => $request->school_or_university ?? $get_edu_details->school_or_university,
                        'degree' => $request->degree ?? $get_edu_details->degree, 
                        'start_year' => $request->start_year ?? $get_edu_details->start_year,
                        'end_year' => $request->end_year ?? $get_edu_details->end_year
                    ]);
                    return $this->success('Great! Education Updated Successfully.', null, null, 201);
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function addCertificate(Request $request){
        $validator = Validator::make($request->all(), [
            'certificate_or_course' => 'required',
            'start_year' => 'required',
            'end_year' => 'required',
            'document' => 'required|image|mimes:jpg,png,jpeg|max:1048',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                if ($request->hasFile('document')) {
                    $image = time() . '.' . $request->document->extension();
                    $request->document->move(public_path('Caregiver/Uploads/Cerificate/'), $image);
                    $imageName = 'Caregiver/Uploads/Cerificate/' . $image;
                }

                CaregiverCertificate::create([
                    'user_id' => Auth::user()->id,
                    'certificate_or_course' => $request->certificate_or_course,
                    'start_year' => $request->start_year,
                    'end_year' => $request->end_year,
                    'document' => $imageName
                ]);
                return $this->success('Great! Certificate Added Successfully.', null, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function editCertificate(Request $request){
        $validator = Validator::make($request->all(), [
            'cert_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $get_cert_details = CaregiverCertificate::where('id', $request->cert_id)->where('user_id', Auth::user()->id)->first();
                if($get_cert_details == null){
                    return $this->error('Oops! Something Went Wrong. No Data Found', null, null, 500);
                }else{

                    if ($request->hasFile('document')) {
                        $image = time() . '.' . $request->document->extension();
                        $request->document->move(public_path('Caregiver/Uploads/Cerificate/'), $image);
                        $imageName = 'Caregiver/Uploads/Cerificate/' . $image;
                    }
    
                    CaregiverCertificate::where('id', $request->cert_id)->where('user_id', Auth::user()->id)->update([
                        'certificate_or_course' => $request->certificate_or_course ?? $get_cert_details->certificate_or_course,
                        'start_year' => $request->start_year ?? $get_cert_details->start_year,
                        'end_year' => $request->end_year ?? $get_cert_details->end_year,
                        'document' => $imageName ?? $get_cert_details->document
                    ]);
                    return $this->success('Great! Certificate Updated Successfully.', null, null, 201);
                }

                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function changePhoto(Request $request){
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:1048',
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                if ($request->hasFile('photo')) {
                    $image = time() . '.' . $request->photo->extension();
                    $request->photo->move(public_path('Caregiver/Uploads/Profile/image/'), $image);
                    $imageName = 'Caregiver/Uploads/Profile/image/' . $image;
                }

                CaregiverProfileRegistration::where('user_id', Auth::user()->id)->update([
                    'photo' => $imageName
                ]);
                return $this->success('Great! Profile Photo Updated Successfully.', $imageName, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function editPhoneOrExperience(Request $request){
        try{
            $get_phone_or_exp_details = CaregiverProfileRegistration::where('user_id', Auth::user()->id)->first();

            CaregiverProfileRegistration::where('user_id', Auth::user()->id)->update([
                'phone' => $request->phone ?? $get_phone_or_exp_details->phone,
                'experience' => $request->experience ?? $get_phone_or_exp_details->experience,
            ]);
            return $this->success('Great! Details Updated Successfully.', null, null, 201);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. Failed To Update Details.', null, null, 500);
        }
    }  
}
