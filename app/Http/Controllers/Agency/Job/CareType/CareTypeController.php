<?php

namespace App\Http\Controllers\Agency\Job\CareType;

use App\Http\Controllers\Controller;
use App\Models\CareType;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CareTypeController extends Controller
{
    use ApiResponse;
    public function getCareTypes(){
        try{
            $get_care_types = CareType::where('status', 1)->get();
            return $this->success('Great! Care Types Fetched Successfully', $get_care_types, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong', null, null, 500);
        }   
    }
}
