<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompleteJobController extends Controller
{   
    use ApiResponse;

    public function getCompleteJob(){
        try{
            $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::Completed)->get();

            $all_details = [];
            
            foreach($get_job as $job){
                $agency = AgencyProfileRegistration::where('user_id', $job->job->user_id)->select('company_name','photo')->first();


                $details = [
                    'job_id' => $job->job_id,
                    'agency_name' => $agency->company_name,
                    'agency_photo' => $agency->photo,
                    'agency_address' => $job->job->short_address,
                    'title' => $job->job->title,
                    'care_type' => $job->job->care_type,
                    'care_items' => $job->job->care_items,
                    'date' => Carbon::parse($job->job->date)->format('m-d-Y') ,
                    'start_time' => $job->job->start_time,
                    'end_time' => $job->job->end_time,
                    'amount' => $job->job->amount,
                    'address' => $job->job->amount,
                    'short_address' => $job->job->short_address,
                    'description' => $job->job->description,
                    'medical_history' => $job->job->medical_history,
                    'experties' => $job->job->experties,
                    'other_requirements' => $job->job->other_requirements,
                    'check_list' => $job->job->check_list,
                    'lat' => $job->job->lat,
                    'long' => $job->job->long,
                    'status' => $job->job->status,
                ];
                
            }
                
            array_push($all_details, $details);
                
            return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
        }
       
    }

    public function completeJob(Request $request){
        $validator = Validator::make($request->all(),[
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }else{
            try{
                AgencyPostJob::where('id', $request->job_id)->update([
                    'status' => JobStatus::Completed
                ]);

                AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->update([
                    'status' => JobStatus::Completed
                ]);
                return $this->success('Great! Job Completed Successfully.', null, null, 201);
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }
}
