<?php

namespace App\Http\Controllers\Caregiver\Status;

use App\Http\Controllers\Controller;
use App\Models\CaregiverStatusInformation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusInformationController extends Controller
{
    use ApiResponse;
    public function profileCompletionStatus(){
        $status = CaregiverStatusInformation::where('user_id', Auth::user()->id)->first();
        if($status == null){
            $status = [
                'is_basic_info_added' => 0,
                'is_optional_info_added' => 0,
                'is_documents_uploaded' => 0,
                'is_profile_approved' => 0
            ];
        }
        return $this->success('Great! Profile Completion Status Fetched Successfully', $status, null , 200);
    }
}
