<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

                $check_if_job_is_ongoing = AcceptJob::where('user_id', Auth::user()->id)->where('status', JobStatus::OnGoing)->exists();

                if($check_if_job_is_ongoing ){
                    return $this->error('Oops! Failed To Start Job. One job is already in an ongoing state', null, null, 500);
                }else{
                    $get_job_date_time = AgencyPostJob::where('id', $request->job_id)->first(['start_date', 'start_time', 'end_date', 'end_time']);

                    $selected_start_date_time_for_the_job = Carbon::parse($get_job_date_time->start_date.''.$get_job_date_time->start_time);

                    $selected_end_date_time_for_the_job = Carbon::parse($get_job_date_time->end_date.''.$get_job_date_time->end_time);
                    

                    $current_time = Carbon::now();

                    //Here checking if my selected job time has arrived or not.

                    if($selected_start_date_time_for_the_job >= $current_time){
                        return $this->error('Oops! Job start time has not arrived yet. Please wait.', null, null, 200);
                    }else if($selected_end_date_time_for_the_job < $current_time){
                        return $this->error('Oops! This job has expired.', null, null, 200);
                    }else{

                        // return $this->error('Oops! Ready to start job.', null, null, 500);
                        $check_if_job_exists_inside_schedule = AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->where('status', JobStatus::JobAccepted)->exists();


                        if($check_if_job_exists_inside_schedule){
                            try{

                                DB::beginTransaction();

                                AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->update([
                                    'status' => JobStatus::OnGoing
                                ]);
            
                                AgencyPostJob::where('id', $request->job_id)->update([
                                    'status' => JobStatus::OnGoing
                                ]);

                                DB::commit();

                                return $this->success('Great! Job Started Successfully', null, null, 201);

                            }catch(\Exception $e){

                                DB::rollBack();
                                Log::error('Oops! Failed to start job.');
                                return $this->error('Oops! Something went wrong. Not able to start the job.', null, null, 500);
                                
                            }
                            
                        }else{
                            return $this->error('Oops! Something Went Wrong. Failed To Start Job', null, null, 500);
                        }
                    }
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Failed To Start Job', null, null, 500);
            }
        }
        
    }
}
