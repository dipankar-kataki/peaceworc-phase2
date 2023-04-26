<?php

namespace App\Http\Controllers\Agency\Job\Search;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SearchJobController extends Controller
{
    use ApiResponse;

    public function search(Request $request){
        $validator = Validator::make($request->all(),[
            'job_status' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                if($request->job_status == 'Open Job'){
                    $job_status =  1;
                }else if($request->job_status == 'Bidding Started'){
                    $job_status =  6;
                }else if($request->job_status == 'Quick Call'){
                    $job_status =  8;
                }

                $get_jobs = AgencyPostJob::where('user_id', Auth::user()->id)->where('status', $job_status)->get();
                return $this->success('Great! Jobs Fetched Successfully', $get_jobs, null, 200);
            }catch(\Exception $e){  
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
