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
            $amount_from = $request->amount_from;
            $amount_to  = $request->amount_to;

            if(( $request->care_type != null)  && ($amount_from == null && $amount_to == null) ){
                $get_jobs = AgencyPostJob::where('payment_status', 1)->where('care_type', $request->care_type)->get();
                return $this->success('Great! Jobs Fetched Successfully 1', $get_jobs, null, 200);
            }else if( ($request->care_type == null) && ($amount_from != null && $amount_to != null)){
                $get_jobs = AgencyPostJob::where('payment_status', 1)->whereBetween('amount', [$amount_from, $amount_to])->get();
                return $this->success('Great! Jobs Fetched Successfully 2', $get_jobs, null, 200);
            }else{
                $get_jobs = AgencyPostJob::where('payment_status', 1)
                            ->where('care_type', $request->care_type)
                            ->whereBetween('amount', [$amount_from, $amount_to])->get();
                return $this->success('Great! Jobs Fetched Successfully 3', $get_jobs, null, 200);
            }
            
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null ,null, 500);
        }
    }
}
