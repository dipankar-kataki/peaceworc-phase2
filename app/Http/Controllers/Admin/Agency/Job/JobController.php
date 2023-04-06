<?php

namespace App\Http\Controllers\Admin\Agency\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyPostJob;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function getAllJobs($id = null){

        if($id === null){
            $get_all_jobs = AgencyPostJob::latest()->get();
            return view('agency.job.list')->with(['get_all_jobs' => $get_all_jobs]);
        }else{
            $job_id = decrypt($id);
            $get_single_job_details = AgencyPostJob::with('agency_profile', 'agency')->where('id', $job_id)->first();
            $is_profile_approved = AgencyInformationStatus::where('user_id', $get_single_job_details->user_id)->first();
            return view('agency.job.job-details')->with(['get_single_job_details' => $get_single_job_details, 'is_profile_approved' => $is_profile_approved->is_profile_approved ]);
        }
       
    }
}
