<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OngoingJobController extends Controller
{
    use ApiResponse;
    public function ongoingJob(){
        try{
            $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::OnGoing)->first();
            $all_details = [];
            if($get_job != null){
            
                $agency = AgencyProfileRegistration::where('user_id',$get_job->job->user_id)->select('company_name','photo')->first();


                $details = [
                    'job_id' => $get_job->job_id,
                    'agency_name' => $agency->company_name,
                    'agency_photo' => $agency->photo,
                    'agency_address' => $get_job->job->short_address,
                    'title' => $get_job->job->title,
                    'care_type' => $get_job->job->care_type,
                    'care_items' => $get_job->job->care_items,
                    'start_date' => Carbon::parse($get_job->job->start_date)->format('m-d-Y') ,
                    'start_time' => $get_job->job->start_time,
                    'end_date' => Carbon::parse($get_job->job->end_date)->format('m-d-Y') ,
                    'end_time' => $get_job->job->end_time,
                    'amount' => $get_job->job->amount,
                    'address' => $get_job->job->amount,
                    'short_address' => $get_job->job->short_address,
                    'description' => $get_job->job->description,
                    'medical_history' => $get_job->job->medical_history,
                    'expertise' => $get_job->job->expertise,
                    'other_requirements' => $get_job->job->other_requirements,
                    'check_list' => $get_job->job->check_list,
                    'lat' => $get_job->job->lat,
                    'long' => $get_job->job->long,
                    'status' => $get_job->job->status,
                ];
                array_push($all_details, $details);
                
                return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);

            }else{
                return $this->success('Oops! No Ongoing Job Found' , $all_details, null, 200);
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
        }
    }
}
