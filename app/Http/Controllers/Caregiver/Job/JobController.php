<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    use ApiResponse;
    public function getJobs(Request $request){
        $get_jobs = AgencyPostJob::where('status', 1)->latest()->get();
        return $this->success('Great! Job Posted Successfully', $get_jobs, null, 200);
    }
}
