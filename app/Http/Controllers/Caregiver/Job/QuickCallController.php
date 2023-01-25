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
    }
}
