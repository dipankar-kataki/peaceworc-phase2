<?php

namespace App\Http\Controllers\Admin\AuthorizeOfficer;

use App\Http\Controllers\Controller;
use App\Models\AuthorizeOfficer;
use Illuminate\Http\Request;

class AuthorizeOfficerController extends Controller
{
    public function getList(Request $request){
        if($request->id != null){

        }else{
            $get_auth_officer = AuthorizeOfficer::with('agency')->get();
            return view('agency.auth-officer.list')->with(['get_auth_officer'=> $get_auth_officer]);
        }
    }
}
