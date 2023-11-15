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
use Illuminate\Support\Facades\Validator;

class AcceptJobController extends Controller
{
    use ApiResponse;
    public function acceptJob(Request $request){

        $validator = Validator::make($request->all(),[
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '. $validator->errors()->first(), null, null, 400);
        }else{
            try{

                // $check_if_profile_status_data_exists = CaregiverStatusInformation::where('user_id', Auth::user()->id)->first();
                // if($check_if_profile_status_data_exists != null){
    
                //     if($check_if_profile_status_data_exists->is_basic_info_added == 1 && $check_if_profile_status_data_exists->is_documents_uploaded == 1){
                //        $get_request_job = AgencyPostJob::where('id', $request->job_id)->first();

                //        $get_job_status = $get_request_job->status;

                //        if($get_job_status ==  JobStatus::QuickCall){
                //             $check_active_quick_call_job_exists = AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->where('status', Job)->exists(); 
                //        }

                //     }else{
                //         return $this->error('Oops! Profile not approved. Please wait for your profile to get approved to accept jobs.');
                //     }
                // }
    
    
    
    
    
    
    
    
                $check_if_profile_completion_status_exists = CaregiverStatusInformation::where('user_id',Auth::user()->id )->first();
                if($check_if_profile_completion_status_exists != null){
                    if($check_if_profile_completion_status_exists){
                        if($check_if_profile_completion_status_exists->is_basic_info_added == 0 || $check_if_profile_completion_status_exists->is_documents_uploaded == 0){
                            return $this->error('Oops! Profile Incomplete. Failed To Accept job.', null, null, 500);
                        }else{
    
                            $check_if_job_is_already_accepted = AcceptJob::where('job_id', $request->job_id)->exists();
                            if($check_if_job_is_already_accepted){
                                return $this->error('Oops! This Job Has Already Been Awarded.', null, null, 500);
                            }else{
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
                            }
                        }
                    }else{
                        return $this->error('Oops! Profile Incomplete. Failed To Accept job.', null, null, 500);
                    }
                }else{
                    return $this->error('Oops! Profile Incomplete. Failed To Accept job.', null, null, 500);
                }
                
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Failed To Accept job.'.$e, null, null, 500);
            }
        }
        
        
           
    }
}
