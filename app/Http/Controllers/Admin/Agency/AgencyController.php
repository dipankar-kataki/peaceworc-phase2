<?php

namespace App\Http\Controllers\Admin\Agency;

use App\Common\Role;
use App\Common\VisibilityStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyProfileRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgencyController extends Controller
{
    public function getList(Request $request){
        
        if($request->id != null){
            $completion_rate = 10;
            $agency_profile_completion_status = AgencyInformationStatus::where('user_id', $request->id)->first();
            if($agency_profile_completion_status){
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
            $agency_details = AgencyProfileRegistration::with('user')->where('id', $request->id)->first();
            return view('agency.profile.index')->with(['agency_details' => $agency_details, 'completion_rate' => $completion_rate ]);
        }else{
            $agency_list = AgencyProfileRegistration::get();
            return view('agency.list')->with(['agency_list' => $agency_list ]);
        }
    }

    public function profile(){
        return view('agency.profile.index');
    }
}
