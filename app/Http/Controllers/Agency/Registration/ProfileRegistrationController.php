<?php

namespace App\Http\Controllers\Agency\Registration;

use App\Http\Controllers\Controller;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileRegistrationController extends Controller
{
    use ApiResponse;
    public function profileRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'phone' => 'required',
            'legal_structure' => 'required',
            'organization_type' => 'required',
            'tax_id_or_ein_id' => 'required',
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
                return $this->error('Oops! Failed To Register Profile. Tax Id Or EIN Id Already Exists.', null, null, 400);
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
                        $profile_data = AgencyProfileRegistration::where('user_id', Auth::user()->id)->first();
                        return $this->success('Great! Profile Registration Successful.', $profile_data, null, 201 );
                    }else{
                        return $this->error('Oops! Failed To Register Profile. Something Went Wrong.', null, null, 500);
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Register Profile. Something Went Wrong.', null, null, 500);
                }
            }
            
        }
    }
}
