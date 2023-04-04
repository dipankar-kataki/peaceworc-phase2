<?php

namespace App\Http\Controllers\Agency\Job\AcceptedJob;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\CaregiverProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpcomingJobController extends Controller
{
    use ApiResponse;
    public function getUpcomingJob(){
        try{
            $get_upcoming_job = AgencyPostJob::where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->latest()->paginate('5');
            $all_details = [];
            foreach($get_upcoming_job as $job){
                $check_accepted_by = AcceptJob::where('job_id', $job->id)->where('status', JobStatus::JobAccepted)->latest()->get();

                foreach($check_accepted_by as $accepted_by){

                    $get_caregiver_details = User::with('caregiverProfile')->where('id', $accepted_by->user_id)->first();
                    

                    $details = [
                        'job_id' => $job->id,
                        'job_accepted_by' => [
                            'user_id' => $get_caregiver_details->id,
                            'name' => $get_caregiver_details->name,
                            'photo' => $get_caregiver_details->caregiverProfile->photo
                        ],
                        'title' => $job->title,
                        'care_type' => $job->care_type,
                        'care_items' => $job->care_items,
                        'start_date' => Carbon::parse($job->start_date)->format('m-d-Y'),
                        'start_time' => $job->start_time,
                        'end_date' => Carbon::parse($job->start_date)->format('m-d-Y'),
                        'end_time' => $job->end_time, 
                        'amount' => $job->amount, 
                        'address' => $job->address, 
                        'short_address' => $job->short_address, 
                        'description' => $job->description,
                        'medical_history' =>  $job->medical_history,
                        'expertise' => $job->expertise,
                        'other_requirements' => $job->other_requirements,
                        'check_list' => $job->check_list,
                        'status' => $job->status,
    
                    ];
                }
                array_push($all_details, $details);
                
            }
            return $this->success('Great! Upcoming Jobs Fetched Successfully.',  $all_details, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
        
    }
}
