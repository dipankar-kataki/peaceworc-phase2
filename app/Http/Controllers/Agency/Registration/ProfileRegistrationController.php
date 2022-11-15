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
    public function profileRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'phone' => 'required',
            'legal_structure' => 'required',
            'organization_type' => 'required',
            'tax_id_or_ein_id' => 'required | max:9',
            'street' => 'required',
            'city_or_district' => 'required',
            'state' => 'required',
            'zip_code' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            $check_if_phone_exists = AgencyProfileRegistration::where('phone', $request->phone)->exists();
            $check_if_tax_id_or_ein_id_exists = AgencyProfileRegistration::where('tax_id_or_ein_id', $request->tax_id_or_ein_id)->exists();

            if($check_if_phone_exists){
                return $this->error('Oops! Failed To Register Profile. Phone Number Already Exists.', null, null, 400);
            }else if($check_if_tax_id_or_ein_id_exists){
                return $this->error('Oops! Failed To Register Profile. Tax Id Or EIN Already Exists.', null, null, 400);
            }else{
                try{
                    $create = AgencyProfileRegistration::create([
                        'user_id' => Auth::user()->id,
                        'phone' => $request->phone,
                        'legal_structure' => $request->legal_structure,
                        'organization_type' => $request->organization_type,
                        'tax_id_or_ein_id' => $request->tax_id_or_ein_id,
                        'street' => $request->street,
                        'city_or_district' => $request->city_or_district,
                        'state' => $request->state,
                        'zip_code' => $request->zip_code,
                        'number_of_employee' => $request->number_of_employee,
                        'years_in_business' => $request->years_in_business,
                        'country_of_business' => $request->country_of_business,
                        'annual_business_revenue' => $request->annual_business_revenue
                    ]);

                    if($create){
                        try{
                            AgencyInformationStatus::create([
                                'user_id' => Auth::user()->id,
                                'is_registration_complete' => 1
                            ]);
                        }catch(\Exception $e){
                            Log::error('Not Able To Update Registration Complete Status ====>',$e);
                        }
                        return $this->success('Great! Profile Registration Successful.', null, null, 201 );
                    }else{
                        return $this->error('Oops! Failed To Register Profile. Something Went Wrong.', null, null, 500);
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Register Profile. Something Went Wrong.', null, null, 500);
                }
            }
            
        }
    }


    public function editProfileRegistration(Request $request){

        $validator = Validator::make($request->all(),[
            'tax_id_or_ein_id' => 'min:9 | max:9'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Failed To Update Profile. '.$validator->errors()->first(), null, null, 400);
        }else{

            $check_if_user_data_exists = AgencyProfileRegistration::where('user_id', Auth::user()->id)->exists();
            if(!$check_if_user_data_exists ){
                return $this->error('Oops! Failed To Update Profile. User Data Donot Exists.', null, null, 404);  
            }else{
                try{

                    $getAgencyProfileDetails = AgencyProfileRegistration::where('user_id', Auth::user()->id)->first();

                    $check_if_phone_exists = AgencyProfileRegistration::where('phone', $request->phone)->exists();
                    $check_if_tax_id_or_ein_id_exists = AgencyProfileRegistration::where('tax_id_or_ein_id', $request->tax_id_or_ein_id)->exists();

                    if( ($check_if_phone_exists) && ($request->phone != $getAgencyProfileDetails->phone) ){
                        return $this->error('Oops! Failed To Update Profile. Phone Number Associated With Another User.', null, null, 400);  
                    }else if( ($check_if_tax_id_or_ein_id_exists) && ($request->tax_id_or_ein_id != $getAgencyProfileDetails->tax_id_or_ein_id ) ){
                        return $this->error('Oops! Failed To Update Profile. Tax Id Or EIN Associated With Another User.', null, null, 400);  
                    }else{
                        
                        $update = AgencyProfileRegistration::where('user_id', Auth::user()->id)->update([

                            'phone' => $request->phone == null ? $getAgencyProfileDetails->phone :  $request->phone ,
                            'legal_structure' => $request->legal_structure == null ? $getAgencyProfileDetails->legal_structure :  $request->legal_structure,
                            'organization_type' => $request->organization_type == null ? $getAgencyProfileDetails->organization_type :  $request->organization_type,
                            'tax_id_or_ein_id' => $request->tax_id_or_ein_id == null ? $getAgencyProfileDetails->tax_id_or_ein_id :  $request->tax_id_or_ein_id,
                            'street' => $request->street == null ? $getAgencyProfileDetails->street :  $request->street,
                            'city_or_district' => $request->city_or_district == null ? $getAgencyProfileDetails->city_or_district :  $request->city_or_district,
                            'state' => $request->state == null ? $getAgencyProfileDetails->state :  $request->state,
                            'zip_code' => $request->zip_code == null ? $getAgencyProfileDetails->zip_code :  $request->zip_code,
                            'number_of_employee' => $request->number_of_employee,
                            'years_in_business' => $request->years_in_business,
                            'country_of_business' => $request->country_of_business,
                            'annual_business_revenue' => $request->annual_business_revenue

                        ]);
    
                        if($update){
                            $agencyProfileDetails = AgencyProfileRegistration::where('user_id', Auth::user()->id)->first();
                            return $this->success('Great! Details Inserted Successfully.', $agencyProfileDetails, null, 201 );
                        }else{
                            return $this->error('Oops! Failed To Update Profile. Something Went Wrong.', null, null, 500);
                        }
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Update Profile. Something Went Wrong.', null, null, 500);
                }
            }
        }
    }
}
