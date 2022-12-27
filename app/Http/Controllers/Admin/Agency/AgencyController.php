<?php

namespace App\Http\Controllers\Admin\Agency;

use App\Common\Role;
use App\Common\VisibilityStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyProfileRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function getList(Request $request){
       
        if($request->id != null){
            $agency_details = AgencyProfileRegistration::with('user')->where('id', $request->id)->first();
            return view('agency.profile.index')->with(['agency_details' => $agency_details ]);
        }else{
            $agency_list = AgencyProfileRegistration::get();
            return view('agency.list')->with(['agency_list' => $agency_list ]);
        }
    }

    public function profile(){
        return view('agency.profile.index');
    }
}
