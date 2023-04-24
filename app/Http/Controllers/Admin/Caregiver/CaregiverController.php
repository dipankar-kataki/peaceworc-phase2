<?php

namespace App\Http\Controllers\Admin\Caregiver;

use App\Common\Role;
use App\Http\Controllers\Controller;
use App\Models\CaregiverProfileRegistration;
use App\Models\User;
use Illuminate\Http\Request;

class CaregiverController extends Controller
{
    public function getList(Request $request){
        $get_caregiver_details = User::with('caregiverProfile')->where('role', Role::Caregiver )->get();
        return view('caregiver.profile.index')->with(['get_caregiver_details' => $get_caregiver_details]);
    }

    public function getCaregiverDetails($id){
        try{
            $id = decrypt($id);
            $get_caregiver_details = User::with('caregiverProfile')->where('id', $id)->first();
            return view('caregiver.profile.details')->with(['get_caregiver_details' => $get_caregiver_details]);
        }catch(\Exception $e){
            echo 'Oops! Something Went Wrong.';
        }
    }
}
