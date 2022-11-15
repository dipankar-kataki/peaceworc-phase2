<?php

namespace App\Http\Controllers\Agency\Status;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InformationStatusController extends Controller
{
    use ApiResponse;

    public function informationStatus(){
        $status = AgencyInformationStatus::where('user_id', Auth::user()->id)->first();
        return $this->success('Great! Agency Information Status Fetched Successfully', $status, null , 200);
    }
}
