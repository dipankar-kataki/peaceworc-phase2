<?php

namespace App\Http\Controllers\Agency\Job\CancelledJob;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CancelledJobController extends Controller
{
    use ApiResponse;
    public function getCanceledJob(){
        try{
            $get_canceled_jobs = AgencyPostJob::where('status', JobStatus::JobCancelled)->get();
            return $this->success('Great! Jobs Fetched Successfully', $get_canceled_jobs, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
       
    }
}
