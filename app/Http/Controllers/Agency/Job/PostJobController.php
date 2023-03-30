<?php

namespace App\Http\Controllers\Agency\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyPostJob;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DateTime;
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
            'date' => 'required|date_format:m-d-Y',
            'start_time' => 'required',
            'end_time' => 'required',
            'amount' => 'required',
            'address' => 'required',
            'short_address' => 'required',
            'street' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $get_status = AgencyInformationStatus::where('user_id', Auth::user()->id)->first();
                if($get_status == null){
                    return $this->error('Oops! The Agency Profile Has To Be Completed First Before Posting A Job.', null, null, 400);
                }else{
                    if($get_status->is_business_info_complete == 0 ||  $get_status->is_authorize_info_added == 0){
                        return $this->error('Oops! The Agency Profile Has To Be Completed First Before Posting A Job.', null, null, 400);
                    }else{
                            $job_date = DateTime::createFromFormat("m-d-Y" , $request->date);
    
                            $full_job_date_time = Carbon::parse($job_date->format('Y-m-d').''.$request->start_time);
                            $current_time = Carbon::now();
    
                            $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                            $from = Carbon::createFromFormat('Y-m-d H:s:i', $full_job_date_time);
    
                            $diff_in_hours = $to->diffInHours($from);
    
                            $status = 0;
    
                            if( $diff_in_hours <= 5){
                                $status = JobStatus::QuickCall;
                            }else if($diff_in_hours > 5){
                               $status = JobStatus::Open;
                            }
                            
    
                        try{
                            
                            $create = AgencyPostJob::create([
                                'user_id' => Auth::user()->id,
                                'title' => $request->title,
                                'care_type' => $request->care_type,
                                'care_items' => json_encode($request->care_items),
                                'date' => $job_date->format('Y-m-d'),
                                'start_time' => $request->start_time,
                                'end_time' => $request->end_time,
                                'amount' => $request->amount,
                                'address' => $request->address,
                                'short_address' => $request->short_address,
                                'street' => $request->street,
                                'appartment_or_unit' => $request->appartment_or_unit,
                                'floor_no' => $request->floor_no,
                                'city' => $request->city,
                                'state' => $request->state,
                                'zip_code' => $request->zip_code,
                                'country' => $request->country,
                                'lat' => $request->lat,
                                'long' => $request->long,
                                'description' => $request->description,
                                'medical_history' => json_encode($request->medical_history),
                                'expertise' => json_encode($request->expertise),
                                'other_requirements' => json_encode($request->other_requirements),
                                'check_list' => json_encode($request->check_list),
                                'status' => $status,
                            ]);

                            if($create){
                                $get_job_details = AgencyPostJob::where('user_id', Auth::user()->id)->latest()->first();
                                return $this->success('Great! Job Posted Successfully', $get_job_details, null, 200);

                            }else{
                                return $this->error('Oops! Something Went Wrong. Failed To Post Job.', null, null, 500);
                            }


                        }catch(\Exception $e){
                            return $this->error('Oops! Something Went Wrong.', null, null, 500);
                        }
                    }
                }
                
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
        }
    }

    public function getJob(){
        if(!isset($_GET['id'])){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job.', null, null, 500);
        }else{
            if($_GET['id'] == 0){
                $job_details = AgencyPostJob::where('user_id', Auth::user()->id)
                            ->where('payment_status', 1)
                            ->latest()->paginate('5');
                return $this->success('Great! Job Fetched Successfully', $job_details, null, 200);
            }else{
                return $this->error('Oops! No Jobs Found.',null, null, 200);
            }
           
        }
        
    }

    public function getSingleJob(){
        if(!isset($_GET['id'])){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job.', null, null, 500);
        }else{
            if($_GET['id'] == 0){
                return $this->error('Oops! No Jobs Found.', null, null, 200);
            }else{
                $job_details = AgencyPostJob::where('user_id', Auth::user()->id)->where('id', $_GET['id'])->first();
                return $this->success('Great! Job Fetched Successfully', $job_details, null, 200);
            }
           
        }
    }

    public function deleteJob(){

        if(!isset($_GET['id'])){
            return $this->error('Oops! Something Went Wrong. Failed To Delete Job.', null, null, 500);
        }else{
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
                    return $this->error('Oops! Failed To Delete Job. Something Went Wrong.', null, null, 500);
                }
            }
        }
        
        
    }
}
