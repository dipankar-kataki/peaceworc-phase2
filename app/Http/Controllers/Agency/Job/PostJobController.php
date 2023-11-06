<?php

namespace App\Http\Controllers\Agency\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyInformationStatus;
use App\Models\AgencyPostJob;
use App\Models\CaregiverProfileRegistration;
use App\Models\User;
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
            'client_id' => 'required',
            'title' => 'required | string',
            'care_type' => 'required | string',
            'care_items' => 'required',
            'start_date' => 'required|date_format:m-d-Y',
            'start_time' => 'required',
            'end_date' => 'required|date_format:m-d-Y',
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
                            $job_start_date = DateTime::createFromFormat("m-d-Y" , $request->start_date);

                            $job_end_date = DateTime::createFromFormat("m-d-Y" , $request->end_date);
    
                            $requested_start_date_time_for_the_job = Carbon::parse($job_start_date->format('Y-m-d').''.$request->start_time);

                            $requested_end_date_time_for_the_job = Carbon::parse($job_end_date->format('Y-m-d').''.$request->end_time);


                            $current_time = Carbon::now();

                            //Checking if the selected date is an old date or current date. If current date or upcoming approve the condition.

                            if( $requested_start_date_time_for_the_job < $current_time ){ 
                                return $this->error('Oops! Selected Start Date Is An Old Date. Please Provide Current Date Or Upcomming Date.', null, null, 400);
                            }else if( $requested_end_date_time_for_the_job < $current_time ){ 
                                return $this->error('Oops! Selected End Date Is An Old Date. Please Provide Current Date Or Upcomming Date.', null, null, 400);
                            }else{ 

                                $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                                $from = Carbon::createFromFormat('Y-m-d H:s:i', $requested_start_date_time_for_the_job);
        
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
                                        'client_id' => $request->client_id,
                                        'title' => $request->title,
                                        'care_type' => $request->care_type,
                                        'care_items' => json_encode($request->care_items),
                                        'start_date' => $job_start_date->format('Y-m-d'),
                                        'start_time' => $request->start_time,
                                        'end_date' => $job_end_date->format('Y-m-d'),
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
                            ->whereIn('status',[ JobStatus::Open,JobStatus::BiddingStarted,JobStatus::BiddingEnded,JobStatus::QuickCall])
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
                        $check_if_job_is_accepted = AcceptJob::where('job_id', $_GET['id'])->exists();

                        $get_job_details = AgencyPostJob::where('id', $_GET['id'])->first();

                        $current_time = Carbon::now();
                        $job_start_time = $get_job_details->start_date.''.$get_job_details->start_time;
    
                        $time_diff_in_seconds_till_job_start = $current_time->diffInSeconds($job_start_time);
                        $time_diff_in_hour_till_job_start = gmdate('H', $time_diff_in_seconds_till_job_start);

                        if($check_if_job_is_accepted){
                            return $this->error('Oops! Job Cannot Be Deleted. Job Is Already Accepted By A Caregiver. ', null, null, 400);
                        }else if( $time_diff_in_hour_till_job_start < 04){
                            return $this->error('Oops! Job Cannot Be Deleted. Less than 4 hours remaining till the job starts. ', null, null, 400);
                        }else{
                            AgencyPostJob::where('id', $_GET['id'])->update([
                                'status' => JobStatus::JobDeleted,
                                'deleted_at' => Carbon::now()
                            ]);
                            return $this->success('Great! Job Deleted Successfully.', null, null, 200);
                        }
                        
                    }
                }catch(\Exception $e){
                    return $this->error('Oops! Failed To Delete Job. Something Went Wrong.', null, null, 500);
                }
            }
        }
        
        
    }

    public function getCaregiverProfile(){

        try{
            if(!isset($_GET['job_id'])){
                return $this->error('Oops! Something Went Wrong. Failed To Get Profile. ', null, null, 400);
            }else{
                if($_GET['job_id'] == null || $_GET['job_id'] == ''){
                    return $this->error('Oops! Something Went Wrong. Failed To Get Profile. ', null, null, 400);
                }else{
                    
                    $get_job = AcceptJob::where('job_id', $_GET['job_id'])->first();
                    $get_caregiver_profile = User::with('caregiverProfile', 'caregiverCertificates')->where('id', $get_job->user_id)->first();
                    // $get_caregiver_certificates

                    $caregiver_profile = [
                        "name" => $get_caregiver_profile->name,
                        "email" => $get_caregiver_profile->email,
                        "photo" => $get_caregiver_profile->caregiverProfile->photo,
                        "bio" =>  $get_caregiver_profile->caregiverProfile->bio,
                        "phone" =>  $get_caregiver_profile->caregiverProfile->phone,
                        "dob" =>  $get_caregiver_profile->caregiverProfile->dob,
                        "gender" =>  $get_caregiver_profile->caregiverProfile->gender,
                        "experience" =>  $get_caregiver_profile->caregiverProfile->experience,
                        "care_completed" => $get_caregiver_profile->caregiverProfile->care_completed,
                        "state" =>  $get_caregiver_profile->caregiverProfile->state,
                        "zip_code" =>  $get_caregiver_profile->caregiverProfile->zip_code,
                        "country" =>  $get_caregiver_profile->caregiverProfile->country,
                        "certificate" => $get_caregiver_profile->caregiverCertificates
                    ];

                    return $this->success('Great! Profile Fetched Successfully', $caregiver_profile, null, 200);
                }
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.'.$e, null, null, 500);
        }
        
    }
}
