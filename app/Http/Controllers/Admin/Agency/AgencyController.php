<?php

namespace App\Http\Controllers\Admin\Agency;

use App\Common\Role;
use App\Common\VisibilityStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyPayment;
use App\Models\AgencyProfileRegistration;
use App\Models\AuthorizeOfficer;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    public function getList(){
        $agency_list = AgencyProfileRegistration::get();
        return view('agency.list')->with(['agency_list' => $agency_list ]);
    }

    public function getAgencyDetails($id){

        try{
            $id = decrypt($id);

            $completion_rate = 10;
            $agency_profile_completion_status = AgencyInformationStatus::where('user_id', $id)->first();
            if($agency_profile_completion_status != null){
                if($agency_profile_completion_status->is_business_info_complete == 1){
                    $completion_rate = $completion_rate+30;
                }
                if($agency_profile_completion_status->is_other_info_added == 1){
                    $completion_rate = $completion_rate+30;
                }
                if($agency_profile_completion_status->is_authorize_info_added == 1){
                    $completion_rate = $completion_rate+30;
                }
    
            }
            $agency_details = AgencyProfileRegistration::with('user')->where('user_id', $id)->first();

            $authorize_officer = AuthorizeOfficer::where('agency_id', $id)->get();

            $reviews = Review::with('caregiver', 'agency')->where('agency_id', $id)->get();

            $get_payments_made = AgencyPayment::with('job')->where('agency_id', $id)->get();

            return view('agency.profile.index')->with(
                [
                    'agency_details' => $agency_details,
                    'completion_rate' => $completion_rate, 
                    'is_profile_approved' => $agency_profile_completion_status->is_profile_approved ?? 'missing',
                    'authorize_officer' => $authorize_officer,
                    'reviews' => $reviews,
                    'get_payments_made' => $get_payments_made
                ]
            );
        }catch(\Exception $e){
           echo 'Oops! Something Went Wrong'.$e;
        }
        
    }

    public function profile(){
        return view('agency.profile.index');
    }
}
