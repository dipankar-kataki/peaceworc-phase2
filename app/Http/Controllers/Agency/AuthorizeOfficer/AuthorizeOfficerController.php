<?php

namespace App\Http\Controllers\Agency\AuthorizeOfficer;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AuthorizeOfficer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizeOfficerController extends Controller
{
    use ApiResponse;

    public function createAuthorizeOfficer(Request $request){
        $validator = Validator::make($request->all(),[
            'firstname' => 'required | string',
            'lastname' => 'required | string',
            'email' => 'required | email | unique:authorize_officers',
            'phone' => 'required | max:11'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Failed To Add Authorize Officer. '.$validator->errors()->first(), null, null, 400);
        }else{
            $check_agency_auth_officer_added = AuthorizeOfficer::where('agency_id', Auth::user()->id)->exists();

            if($check_agency_auth_officer_added){
                $check_phone_exists = AuthorizeOfficer::where('phone', $request->phone)->exists();

                if($check_phone_exists){
                    return $this->error('Oops! Failed To Add Authorize Officer. Phone Number Associated With Another User.', null, null, 400);
                }else{
                    try{
                        $create = AuthorizeOfficer::create([
                            'agency_id' => Auth::user()->id,
                            'firstname' => $request->firstname,
                            'lastname' => $request->lastname,
                            'email' => $request->email,
                            'phone' => $request->phone
                        ]);
        
                        if($create){
                            $authorizeOfficerDetails = AuthorizeOfficer::where('agency_id', Auth::user()->id)->get();
                            return $this->success('Great! Authorize Officer Added Successfully.', $authorizeOfficerDetails, null, 201);
                        }else{
                            return $this->error('Oops! Failed To Add Authorize Officer.', null, null, 500);
                        }
                    }catch(\Exception $e){
                        return $this->error('Oops! Failed To Add Authorize Officer. Something Went Wrong.', null, null, 500);
                    }
                }
            }else{
                try{
                    $create = AuthorizeOfficer::create([
                        'agency_id' => Auth::user()->id,
                        'firstname' => $request->firstname,
                        'lastname' => $request->lastname,
                        'email' => $request->email,
                        'phone' => $request->phone
                    ]);
    
                    if($create){
                        $authorizeOfficerDetails = AuthorizeOfficer::where('agency_id', Auth::user()->id)->get();
                        AgencyInformationStatus::where('user_id', Auth::user()->id)->update([
                            'is_authorize_info_added' => 1
                        ]);
                        return $this->success('Great! Authorize Officer Added Successfully.', $authorizeOfficerDetails, null, 201);
                    }else{
                        return $this->error('Oops! Failed To Add Authorize Officer.', null, null, 500);
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Add Authorize Officer. Something Went Wrong.', null, null, 500);
                }
            }
            
        }
    }
}
