<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuickCallController extends Controller
{
    use ApiResponse;
    public function getQuickCallJobs(){

        if(!isset($_GET['id'])){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }else{

            if($_GET['id'] == ''){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }else{
                if($_GET['id'] == 0){

                    try{
                        $get_jobs = AgencyPostJob::where('status', JobStatus::QuickCall)->latest()->get();
    
                        $get_job_details = [];
                
                        foreach($get_jobs as $job){
                            $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $job->user_id)->first();
                            $details = [
                                'job_id' => $job->id,
                                'company_name' => $job_owner->company_name,
                                'company_photo' => $job_owner->photo,
                                'job_title' => $job->title,
                                'care_type' => $job->care_type,
                                'care_items' => $job->care_items,
                                'date' => $job->date,
                                'start_time' => $job->start_time,
                                'end_time' => $job->end_time,
                                'amount' => $job->amount,
                                'address' => $job->address,
                                'short_address' => $job->short_address,
                                'lat' => $job->lat,
                                'long' => $job->long,
                                'description' => $job->description,
                                'medical_history' => $job->medical_history,
                                'experties' => $job->experties,
                                'other_requirements' => $job->other_requirements,
                                'check_list' => $job->check_list,
                                'status' => $job->status,
                                // 'bidding_start_time' => $job->bidding_start_time,
                                // 'bidding_end_time' => $job->bidding_end_time,
                                'created_at' => $job->created_at->diffForHumans(),
                
                            ];
                
                            array_push($get_job_details, $details);
                        }
                
                        return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
                    }catch(\Exception $e){
                        return $this->error('Oops! Something Went Wrong.', null, null, 500);
                    }
                    
                }else{
                    try{
                        $get_jobs = AgencyPostJob::where('id', $_GET['id'])->where('status', JobStatus::QuickCall)->first();
                        $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $get_jobs->user_id)->first();
                        $job_list = [];
                            $details = [
                                'job_id' => $get_jobs->id,
                                'company_name' => $job_owner->company_name,
                                'company_photo' => $job_owner->photo,
                                'job_title' => $get_jobs->title,
                                'care_type' => $get_jobs->care_type,
                                'care_items' => $get_jobs->care_items,
                                'date' => $get_jobs->date,
                                'start_time' => $get_jobs->start_time,
                                'end_time' => $get_jobs->end_time,
                                'amount' => $get_jobs->amount,
                                'address' => $get_jobs->address,
                                'short_address' => $get_jobs->short_address,
                                'lat' => $get_jobs->lat,
                                'long' => $get_jobs->long,
                                'description' => $get_jobs->description,
                                'medical_history' => $get_jobs->medical_history,
                                'experties' => $get_jobs->experties,
                                'other_requirements' => $get_jobs->other_requirements,
                                'check_list' => $get_jobs->check_list,
                                'status' => $get_jobs->status,
                                // 'bidding_start_time' => $job->bidding_start_time,
                                // 'bidding_end_time' => $job->bidding_end_time,
                                'created_at' => $get_jobs->created_at->diffForHumans(),
                
                            ];
                        array_push($job_list, $details);
                        return $this->success('Great! Job Fetched Successfully', $job_list, null, 200);
                    }catch(\Exception $e){
                        return $this->error('Oops! Something Went Wrong.', null, null, 500);
                    }
                }
            }
            
        }
       
    }
}
