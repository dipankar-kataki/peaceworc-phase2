<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptJobController extends Controller
{
    use ApiResponse;
    public function acceptJob(Request $request){
        
        try{
            $create = AcceptJob::create([
                'user_id' => Auth::user()->id,
                'job_id' => $request->job_id,
                'status' => JobStatus::JobAccepted
            ]);

            if($create){
                AgencyPostJob::where('id', $request->job_id)->update([
                    'status' => JobStatus::JobAccepted
                ]);
            }
            return $this->success('Great! Job Accepted Successfully', null, null, 201);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. Failed To Accept job.', null, null, 500);
        }
           
    }
}
