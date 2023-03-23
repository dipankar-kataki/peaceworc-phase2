<?php

namespace App\Http\Controllers\Admin\Agency\Access;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AuthorizeOfficer;
use App\Models\User;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function updateStatus(Request $request){
        try{
            $updatingFor = $request->updatingFor;
            if($updatingFor == 'agency'){

                AgencyInformationStatus::where('user_id', $request->id)->update([
                    'is_profile_approved' => $request->access
                ]);

                if($request->access == 0){
                    return response()->json(['message' => 'Great! Profile Blocked Successfully.', 'btnText' => 'Unblock Agency', 'status' => 1]);
                }else{
                    return response()->json(['message' => 'Great! Profile Approved Successfully.', 'btnText' => 'Block Agency', 'status' => 1]);
                }
            }

            if($updatingFor == 'authOfficer'){

                AuthorizeOfficer::where('id', $request->id)->update([
                    'status' => $request->access
                ]);

                if($request->access == 0){
                    return response()->json(['message' => 'Great! Profile Blocked Successfully.', 'btnText' => 'Unblock  Officer', 'status' => 1]);
                }else{
                    return response()->json(['message' => 'Great! Profile Unblocked Successfully.', 'btnText' => 'Block  Officer', 'status' => 1]);
                }
            }
        }catch(\Exception $e){
            return response()->json(['message' => 'Oops! Something Went Wrong.', 'data' => null, 'status' => 0]);
        }
    }
}
