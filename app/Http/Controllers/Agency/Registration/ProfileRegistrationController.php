<?php

namespace App\Http\Controllers\Agency\Registration;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileRegistrationController extends Controller
{
    use ApiResponse;

    public function addBusinessInfo(Request $request){
        $validator = Validator::make($request->all(),[
            'photo' => 'required|image|mimes:jpg,png,jpeg|max:1048',
            'email' => 'required|email',
            'phone' => 'required',
            'tax_id_or_ein_id' => 'required | max:9',
            'street' => 'required',
            'city_or_district' => 'required',
            'state' => 'required',
            'zip_code' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            $check_if_email_exists = AgencyProfileRegistration::where('email',$request->email)->exists();
            $check_if_phone_exists = AgencyProfileRegistration::where('phone', $request->phone)->exists();
            $check_if_tax_id_or_ein_id_exists = AgencyProfileRegistration::where('tax_id_or_ein_id', $request->tax_id_or_ein_id)->exists();

            if($check_if_email_exists){
                return $this->error('Oops! Failed To Add Business information. Email Already Exists.', null, null, 400);
            }else if($check_if_phone_exists){
                return $this->error('Oops! Failed To Add Business information. Phone Number Already Exists.', null, null, 400);
            }else if($check_if_tax_id_or_ein_id_exists){
                return $this->error('Oops! Failed To Add Business information. Tax Id Or EIN Already Exists.', null, null, 400);
            }else{
                try{

                    if ($request->hasFile('photo')) {
                        $image = time() . '.' . $request->photo->extension();
                        $request->photo->move(public_path('Agency/Uploads/Profile/image/'), $image);
                        $imageName = 'Agency/Uploads/Profile/image/' . $image;
                    }
        
                    $create = AgencyProfileRegistration::where('user_id', Auth::user()->id,)->update([
                        'photo' => $imageName,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'tax_id_or_ein_id' => $request->tax_id_or_ein_id,
                        'street' => $request->street,
                        'city_or_district' => $request->city_or_district,
                        'state' => $request->state,
                        'zip_code' => $request->zip_code,
                    ]);

                    if($create){
                        try{
                            AgencyInformationStatus::create([
                                'user_id' => Auth::user()->id,
                                'is_business_info_complete' => 1
                            ]);
                        }catch(\Exception $e){
                            Log::error('Not Able To Update Registration Complete Status ====>',$e);
                        }
                        return $this->success('Great! Business information Added Successfully.', null, null, 201 );
                    }else{
                        return $this->error('Oops! Failed To Add Business information. Something Went Wrong.', null, null, 500);
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Add Business information. Something Went Wrong.', null, null, 500);
                }
            }
            
        }

    }

    public function addOptionalInfo(Request $request){

        if($request->number_of_employee == null && $request->years_in_business == null && $request->annual_business_revenue == null && $request->legal_structure == null && $request->organization_type == null &&  $request->country_of_business == null){
            return $this->error('Not Able To Add Other Information. Empty Data Provided', null, null, 400);
        }else{
            try{
                $create = AgencyProfileRegistration::where('user_id', Auth::user()->id)->update([
                    'number_of_employee' => $request->number_of_employee,
                    'years_in_business' => $request->years_in_business,
                    'annual_business_revenue' => $request->annual_business_revenue,
                    'legal_structure' => $request->legal_structure,
                    'organization_type' => $request->organization_type,
                    'country_of_business' => $request->country_of_business,
                ]);
    
                if($create){
                    try{
                        AgencyInformationStatus::where('user_id', Auth::user()->id)->update([
                            'is_other_info_added' => 1
                        ]);
                    }catch(\Exception $e){
                        Log::error('Not Able To Update Status Of Other Information =====>', $e);
                    }
                    return $this->success('Great! Other information Added Successfully.', null, null, 201 );
                }
            }catch(\Exception $e){
                Log::error('Not Able To Create Other information =====>', $e);
                return $this->error('Not Able To Add Other Information. Something Went Wrong', null, null, 500);
            }
        }
        
    }

    public function editBasicProfileDetails(Request $request){

        $check_if_user_data_exists = AgencyProfileRegistration::where('user_id', Auth::user()->id)->exists();
        if(!$check_if_user_data_exists ){
            return $this->error('Oops! Failed To Update Profile. User Data Donot Exists.', null, null, 404);  
        }else{
            try{

                $getAgencyProfileDetails = AgencyProfileRegistration::where('user_id', Auth::user()->id)->first();

                if ($request->hasFile('photo')) {
                    $image = time() . '.' . $request->photo->extension();
                    $request->photo->move(public_path('Agency/Uploads/Profile/image/'), $image);
                    $imageName = 'Agency/Uploads/Profile/image/' . $image;
                }
                    
                $update = AgencyProfileRegistration::where('user_id', Auth::user()->id)->update([
                    'photo' => $request->photo == null ? $getAgencyProfileDetails->photo :  $imageName,
                    'phone' => $request->phone == null ? $getAgencyProfileDetails->phone :  $request->phone,
                    'legal_structure' => $request->legal_structure == null ? $getAgencyProfileDetails->legal_structure :  $request->legal_structure,
                    'organization_type' => $request->organization_type == null ? $getAgencyProfileDetails->organization_type :  $request->organization_type,
                    'street' => $request->street == null ? $getAgencyProfileDetails->street :  $request->street,
                    'city_or_district' => $request->city_or_district == null ? $getAgencyProfileDetails->city_or_district :  $request->city_or_district,
                    'state' => $request->state == null ? $getAgencyProfileDetails->state :  $request->state,
                    'zip_code' => $request->zip_code == null ? $getAgencyProfileDetails->zip_code :  $request->zip_code,
                    'number_of_employee' => $request->number_of_employee == null ? $getAgencyProfileDetails->number_of_employee :  $request->number_of_employee,
                    'years_in_business' => $request->years_in_business == null ? $getAgencyProfileDetails->years_in_business :  $request->years_in_business,
                    'country_of_business' => $request->country_of_business  == null ? $getAgencyProfileDetails->country_of_business :  $request->country_of_business,
                    'annual_business_revenue' => $request->annual_business_revenue == null ? $getAgencyProfileDetails->annual_business_revenue :  $request->annual_business_revenue

                ]);

                if($update){
                    $agencyProfileDetails = AgencyProfileRegistration::where('user_id', Auth::user()->id)->first();
                    return $this->success('Great! Details Inserted Successfully.', $agencyProfileDetails, null, 201 );
                }else{
                    return $this->error('Oops! Failed To Update Profile. Something Went Wrong.', null, null, 500);
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Failed To Update Profile. Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function getProfileDetails(){
        
    }
}
