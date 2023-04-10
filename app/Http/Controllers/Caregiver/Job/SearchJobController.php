<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SearchJobController extends Controller
{
    use ApiResponse;

    public function search(Request $request){
        try{
            if($request->care_type == null && $request->amount_from == null){
                return $this->error('Oops! Failed To Fetch Job. Select At least One Search Criteria.', null, null, 400);
            }else{
                $amount_from = $request->amount_from;
                $amount_to  = $request->amount_to;
    
                if($amount_from == null && $amount_to == null){
                    $get_jobs = AgencyPostJob::where('payment_status', 1)->where('care_type','LIKE','%'.$request->care_type.'%')->get();
                    return $this->success('Great! Jobs Fetched Successfully', $get_jobs, null, 200);
                }else{
                    $get_jobs = AgencyPostJob::where('payment_status', 1)
                                ->where('care_type','LIKE','%'.$request->care_type.'%')
                                ->whereBetween('points', [$amount_from, $amount_to])->get();
                    return $this->success('Great! Jobs Fetched Successfully', $get_jobs, null, 200);
                }
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null ,null, 500);
        }
    }
}
