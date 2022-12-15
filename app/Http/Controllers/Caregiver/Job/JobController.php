<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use ApiResponse;
    public function getJobs(Request $request){
        $get_jobs = AgencyPostJob::where('status', JobStatus::Open)->latest()->get();
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
                'description' => $job->description,
                'medical_history' => $job->medical_history,
                'experties' => $job->experties,
                'other_requirements' => $job->other_requirements,
                'check_list' => $job->check_list,
                'status' => $job->status,
                'created_at' => $job->created_at->diffForHumans(),

            ];

            array_push($get_job_details, $details);
        }

        return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
    }
}
