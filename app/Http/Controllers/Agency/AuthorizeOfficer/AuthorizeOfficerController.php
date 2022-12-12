<?php

namespace App\Http\Controllers\Agency\AuthorizeOfficer;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AuthorizeOfficer;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthorizeOfficerController extends Controller
{
    use ApiResponse;


    public function authorizeOfficer(){
        try{
            $authorizeOfficerDetails = AuthorizeOfficer::where('agency_id', Auth::user()->id)->latest()->get();
            $ownerOfficer = User::where('id', Auth::user()->id)->where('role', Role::Agency_Owner)->first();
            $officers = [];
            $detailsOwner = [
                'user_id' => $ownerOfficer->id,
                'agency_id' => $ownerOfficer->id,
                'name' => $ownerOfficer->name,
                'email' => $ownerOfficer->email,
                'phone' => $ownerOfficer->phone,
                'role' => $ownerOfficer->role
            ];

            array_push($officers, $detailsOwner);

            if(!$authorizeOfficerDetails->isEmpty()){
                foreach($authorizeOfficerDetails as $authOfficers){
                    

                    $detailsAuthorize = [
                        'user_id' => $authOfficers->id,
                        'agency_id' => $authOfficers->agency_id,
                        'name' => $authOfficers->name,
                        'email' => $authOfficers->email,
                        'phone' => $authOfficers->phone,
                        'role' => $authOfficers->role
                    ];

                    array_push($officers, $detailsAuthorize);
                }
            }
            
            return $this->success('Great! Authorize Officer Details Fetched Successfully.', $officers, null, 200);
        }catch(\Exception $e){
            return $this->error($e, null, null, 500);
        }
    }


    public function createAuthorizeOfficer(Request $request){
        $validator = Validator::make($request->all(),[
            'fullname' => 'required|string',
            'email' => 'required|email|unique:authorize_officers',
            'phone' => 'required|max:11',
            'role' => 'required|string'
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
                            'name' => $request->fullname,
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'role' => $request->role
                        ]);
        
                        if($create){
                            $authorizeOfficerDetails = AuthorizeOfficer::where('agency_id', Auth::user()->id)->latest()->get();
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
                        'name' => $request->fullname,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'role' => $request->role
                    ]);
    
                    if($create){
                        $authorizeOfficerDetails = AuthorizeOfficer::where('agency_id', Auth::user()->id)->latest()->get();
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


    public function deleteAuthorizeOfficer(){

        if($_GET['id'] == null){
            return $this->error('Oops! Something Went Wrong. Invalid Request', null, null, 500);
        }else{
            try{
                $check_if_officer_exists = AuthorizeOfficer::where('id', $_GET['id'])->exists();
                if(!$check_if_officer_exists){
                    return $this->error('Oops! Failed To Delete. Officer Not Found', null, null, 500);
                }else{
                    AuthorizeOfficer::where('id', $_GET['id'])->delete();
                    return $this->success('Great! Authorize Officer Deleted Successfully.', null, null, 200);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Failed To Delete Authorize Officer. Something Went Wrong.'.$e, null, null, 500);
            }
        }
        
        
    }
}
