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
    public function getList(){
        $agency_list = AgencyProfileRegistration::where('status', VisibilityStatus::Open)->get();
        return view('agency.list')->with(['agency_list' => $agency_list ]);
    }

    public function profile(){
        return view('agency.profile.index');
    }
}
