<?php

namespace App\Http\Controllers\Agency\Job;

use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyPostJob;
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

                $get_status = AgencyInformationStatus::where('user_id', Auth::user()->id)->first();
                if($get_status->is_business_info_complete == 0 || $get_status->is_other_info_added == 0 || $get_status->is_authorize_info_added == 0){
                    return $this->error('Oops! The Agency Profile Has To Be Completed First Before Posting A Job.', null, null, 500);
                }else{
                    try{
                        $create = AgencyPostJob::create([
                            'user_id' => Auth::user()->id,
                            'title' => $request->title,
                            'care_type' => $request->care_type,
                            'care_items' => json_encode($request->care_items),
                            'date' => date_create($request->date)->format('Y-m-d'),
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
                        return $this->error('Oops! Something Went Wrong.'.$e->getMessage(), null, null, 500);
                    }
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function getJob(){
        $job_details = AgencyPostJob::where('user_id', Auth::user()->id)->latest()->get();
        return $this->success('Great! Job Fetched Successfully', $job_details, null, 200);
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
