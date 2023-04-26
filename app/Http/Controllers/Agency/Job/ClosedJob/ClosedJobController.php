<?php

namespace App\Http\Controllers\Agency\Job\ClosedJob;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClosedJobController extends Controller
{
    use ApiResponse;

    public function closeJob(Request $request){

        $validator = Validator::make($request->all(), [
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                AgencyPostJob::where('id', $request->job_id)->update([
                    'status' => JobStatus::Closed
                ]);

                AcceptJob::where('job_id', $request->job_id)->update([
                    'status' => JobStatus::Closed
                ]);

                return $this->success('Great! Job Closed Successfully.', null, null, 201);

            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong', null, null, 500);
            }
        }
    }
}
