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
    public function acceptJob(){
        if(!isset($_GET['job_id'])){
            return $this->error('Oops! Something Went Wrong. Failed To Accept job.', null, null, 500);
        }else{
            if($_GET['job_id'] == null){
                return $this->error('Oops! Something Went Wrong. Failed To Accept job.', null, null, 500);
            }else{
                try{
                    $create = AcceptJob::create([
                        'user_id' => Auth::user()->id,
                        'job_id' => $_GET['job_id'],
                        'status' => JobStatus::JobAccepted
                    ]);

                    if($create){
                        AgencyPostJob::where('id', $_GET['job_id'])->update([
                            'status' => JobStatus::JobAccepted
                        ]);
                    }
                    return $this->success('Great! Job Accepted Successfully', null, null, 201);
                }catch(\Exception $e){
                    return $this->error('Oops! Something Went Wrong. Failed To Accept job.', null, null, 500);
                }
            }
        }
    }
}
