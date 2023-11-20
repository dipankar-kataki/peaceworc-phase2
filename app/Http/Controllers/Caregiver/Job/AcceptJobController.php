<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\CaregiverStatusInformation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AcceptJobController extends Controller
{
    use ApiResponse;
    public function acceptJob(Request $request){

        return 'Url hiting';

        $validator = Validator::make($request->all(),[
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '. $validator->errors()->first(), null, null, 400);
        }else{
            try{

                $check_if_profile_status_data_exists = CaregiverStatusInformation::where('user_id', Auth::user()->id)->first();

                if($check_if_profile_status_data_exists != null){
    
                    if($check_if_profile_status_data_exists->is_basic_info_added == 1  && $check_if_profile_status_data_exists->is_documents_uploaded == 1){


                       $get_request_job = AgencyPostJob::where('id', $request->job_id)->first();

                        if($get_request_job == null){
                            return $this->error('Oops! Failed to accept the job. Job may be expired or deleted by the agency.', null, null, 400);
                        }else{
                            $get_job_type = $get_request_job->job_type;

                            if($get_job_type ==  JobStatus::QuickCall){
                                $check_active_quick_call_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->get(); 
                                if(!$check_active_quick_call_job->isEmpty()){
                                    foreach($check_active_quick_call_job as $active_job){
                                        if($active_job->job->job_type == JobStatus::QuickCall){
                                            return $this->error('Oops! A caregiver can accept one quick call job at a time.', null, null, 400);
                                        }else{
                                            $get_last_accepted_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->last(); 
        
                                            $end_date_time_of_last_accepted_job = Carbon::parse($get_last_accepted_job->end_date.''.$get_last_accepted_job->end_time);

                                            $about_to_accept_job_start_date_time = Carbon::parse($get_request_job->start_date.''.$get_request_job->start_time);

                                            $difference_in_hours = $end_date_time_of_last_accepted_job->diffInHours($about_to_accept_job_start_date_time);

                                            if($difference_in_hours > 3){

                                                try{
                                                    DB::beginTransaction();

                                                    AcceptJob::create([
                                                        'user_id' => Auth::user()->id,
                                                        'job_id' => $request->job_id,
                                                        'status' => JobStatus::JobAccepted,
                                                        'job_accepted_time' => Carbon::now()
                                                    ]);
        
                                                    AgencyPostJob::where('id', $request->job_id)->update([
                                                        'status' => JobStatus::JobAccepted
                                                    ]);

                                                    DB::commit();

                                                    return $this->success('Great! Job accepted successfully.', null, null, 201);
        

                                                }catch(\Exception $e){
                                                    DB::rollBack();
                                                    Log::error('Error in accept job transaction');

                                                    return $this->error('Oops! Failed to accept the job. Something went wrong.', null, null, 500);
                                                    
                                                }
                                                

                                            }else{
                                                return $this->error('Oops! Failed to accept the job. There must be a minimum 3-hour difference from the end of the last job to accept a new one.', null, null, 200);
                                            }
                                        }
        
                                    }
                                }else{
                                    try{
                                        DB::beginTransaction();

                                        AcceptJob::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::JobAccepted,
                                            'job_accepted_time' => Carbon::now()
                                        ]);

                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::JobAccepted
                                        ]);

                                        DB::commit();

                                        return $this->success('Great! Job accepted successfully.', null, null, 201);


                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        Log::error('Error in accept job transaction');

                                        return $this->error('Oops! Failed to accept the job. Something went wrong.', null, null, 500);
                                        
                                    }
                                }
                                

                            }else{
                                $get_last_accepted_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->last(); 
                                $end_date_time_of_last_accepted_job = Carbon::parse($get_last_accepted_job->end_date.''.$get_last_accepted_job->end_time);
                                
                                $about_to_accept_job_start_date_time = Carbon::parse($get_request_job->start_date.''.$get_request_job->start_time);
                                $difference_in_hours = $end_date_time_of_last_accepted_job->diffInHours($about_to_accept_job_start_date_time);

                                if($difference_in_hours > 3){

                                    try{
                                        DB::beginTransaction();

                                        AcceptJob::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::JobAccepted,
                                            'job_accepted_time' => Carbon::now()
                                        ]);

                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::JobAccepted
                                        ]);

                                        DB::commit();

                                        return $this->success('Great! Job accepted successfully.', null, null, 201);


                                    }catch(\Exception $e){
                                        // If an error occurs, rollback the transaction
                                        DB::rollBack();
                                        // Log the error
                                        Log::error('Error in accept job transaction');

                                        return $this->error('Oops! Failed to accept the job. Something went wrong.', null, null, 500);
                                    }
                                    

                                }else{
                                    return $this->error('Oops! Failed to accept the job. There must be a minimum 3-hour difference from the end of the last job to accept a new one.', null, null, 200);
                                }
                            }
                        }

                        

                    }else{
                        return $this->error('Oops! Profile not approved. Please wait for your profile to get approved to accept jobs.', null, null, 200);
                    }
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Failed To Accept job.', null, null, 500);
            }
        }  
    }
}
