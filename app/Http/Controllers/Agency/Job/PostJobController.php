<?php

namespace App\Http\Controllers\Agency\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostJobController extends Controller
{
    use ApiResponse;

    public function createJob(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required | string',
            'care_type' => 'required | string',
            'care_items' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'amount' => 'required',
            'address' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $create = AgencyPostJob::create([
                    'user_id' => Auth::user()->id,
                    'title' => $request->title,
                    'care_type' => $request->care_type,
                    'care_items' => json_encode($request->care_items),
                    'date' => $request->date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'amount' => $request->amount,
                    'address' => $request->address,
                    'description' => $request->description,
                    'medical_history' => json_encode($request->medical_history),
                    'experties' => json_encode($request->experties),
                    'other_requirements' => json_encode($request->other_requirements),
                    'check_list' => json_encode($request->check_list)
                ]);
                if($create){
                    return $this->success('Great! Job Posted Successfully', null, null, 201);
                }else{
                    return $this->error('Oops! Something Went Wrong.', null, null, 500);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function getJob(){
        $job_details = AgencyPostJob::where('user_id', Auth::user()->id)->latest()->get();
        $job_owner = AgencyProfileRegistration::with('user')->where('user_id',Auth::user()->id)->first();
        $get_job_details = [];
        foreach($job_details as $job){
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

    public function deleteJob(){

        if($_GET['id'] == null){
            return $this->error('Oops! Something Went Wrong. Invalid Request', null, null, 500);
        }else{
            try{
                $check_if_job_exists = AgencyPostJob::where('id', $_GET['id'])->exists();
                if(!$check_if_job_exists){
                    return $this->error('Oops! Failed To Delete. Job Not Found', null, null, 500);
                }else{
                    AgencyPostJob::where('id', $_GET['id'])->delete();
                    return $this->success('Great! Job Deleted Successfully.', null, null, 200);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Failed To Delete Job. Something Went Wrong.'.$e, null, null, 500);
            }
        }
        
    }
}
