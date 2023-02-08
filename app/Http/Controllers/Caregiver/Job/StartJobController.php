<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StartJobController extends Controller
{
    use ApiResponse;
    public function startJob(Request $request){

        $validator = Validator::make($request->all(),[
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Something Went Wrong. Failed To Start Job', null, null, 500);
        }else{
        
            try{

                $check_if_job_is_ongoing = AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->where('status', JobStatus::OnGoing)->exists();

                if($check_if_job_is_ongoing ){
                    return $this->error('Oops! Failed To Start Job. One job is already in an ongoing state', null, null, 500);
                }else{
                    $check_if_job_exists_inside_schedule = AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->where('status', JobStatus::JobAccepted)->exists();
                    if($check_if_job_exists_inside_schedule){
                        AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->update([
                            'status' => JobStatus::OnGoing
                        ]);
    
                        AgencyPostJob::where('id', $request->job_id)->update([
                            'status' => JobStatus::OnGoing
                        ]);
    
                        return $this->success('Great! Job Started Successfully', null, null, 201);
                    }else{
                        return $this->error('Oops! Something Went Wrong. Failed To Start Job', null, null, 500);
                    }
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Failed To Start Job', null, null, 500);
            }
        }
        
    }
}
