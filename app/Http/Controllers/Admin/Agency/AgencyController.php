<?php

namespace App\Http\Controllers\Admin\Agency;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function getList(){
        $agency_list = User::where('role', Role::Agency_Owner)->get();
        return view('agency.list')->with(['agency_list' => $agency_list ]);
    }
}
