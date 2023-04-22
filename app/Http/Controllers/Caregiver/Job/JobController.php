<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Models\CaregiverBidding;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\JobDistance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    use ApiResponse, JobDistance;

    public function getJobs(Request $request){

        if(!isset($_GET['id'])){
            return $this->error('Oops! Failed To Fetch Job', null, null, 500);
        }else{

            $get_user = User::where('id', Auth::user()->id)->first();
            $lat1 = $get_user->lat;
            $long1 = $get_user->long;

            if($_GET['id'] == 0){
                $get_jobs = AgencyPostJob::where('status', JobStatus::Open)->where('payment_status', 1)->latest()->paginate('5');
                $get_job_details = [];

                foreach($get_jobs as $job){

                    $lat2 = $job->lat;
                    $long2 = $job->long;

                    $miles = $this->jobDistance($lat1, $long1, $lat2, $long2, 'M');

                    $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $job->user_id)->first();
                    $details = [
                        'job_id' => $job->id,
                        'company_name' => ucwords($job_owner->company_name),
                        'company_photo' => $job_owner->photo,
                        'job_title' => $job->title,
                        'care_type' => $job->care_type,
                        'care_items' => $job->care_items,
                        'start_date' => $job->start_date,
                        'start_time' => $job->start_time,
                        'end_date' => $job->end_date,
                        'end_time' => $job->end_time,
                        'amount' => $job->amount,
                        'address' => $job->address,
                        'short_address' => $job->short_address,
                        'lat' => $job->lat,
                        'long' => $job->long,
                        'distance' => $miles,
                        'description' => $job->description,
                        'medical_history' => $job->medical_history,
                        'expertise' => $job->expertise,
                        'other_requirements' => $job->other_requirements,
                        'check_list' => $job->check_list,
                        'status' => $job->status,
                        'created_at' => $job->created_at->diffForHumans(),

                    ];

                    array_push($get_job_details, $details);
                }

                return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
            }else{
                $get_jobs = AgencyPostJob::where('status', JobStatus::Open)->where('id', $_GET['id'])->first();
                $get_job_details = [];

                $lat2 = $get_jobs->lat;
                $long2 = $get_jobs->long;

                $miles = $this->jobDistance($lat1, $long1, $lat2, $long2, 'M');
               
                    $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $get_jobs->user_id)->first();
                    $details = [
                        'job_id' => $get_jobs->id,
                        'company_name' => ucwords($job_owner->company_name),
                        'company_photo' => $job_owner->photo,
                        'job_title' => $get_jobs->title,
                        'care_type' => $get_jobs->care_type,
                        'care_items' => $get_jobs->care_items,
                        'start_date' => $get_jobs->start_date,
                        'start_time' => $get_jobs->start_time,
                        'end_date' => $get_jobs->end_date,
                        'end_time' => $get_jobs->end_time,
                        'amount' => $get_jobs->amount,
                        'address' => $get_jobs->address,
                        'short_address' => $get_jobs->short_address,
                        'lat' => $get_jobs->lat,
                        'long' => $get_jobs->long,
                        'distance' => $miles,
                        'description' => $get_jobs->description,
                        'medical_history' => $get_jobs->medical_history,
                        'expertise' => $get_jobs->expertise,
                        'other_requirements' => $get_jobs->other_requirements,
                        'check_list' => $get_jobs->check_list,
                        'status' => $get_jobs->status,
                        // 'bidding_start_time' => $job->bidding_start_time,
                        // 'bidding_end_time' => $job->bidding_end_time,
                        'created_at' => $get_jobs->created_at->diffForHumans(),

                    ];
                    array_push($get_job_details, $details);

                return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
            }
        }
        
    }

    public function getBiddedJobs(){
        try{
            $get_jobs = AgencyPostJob::where('status', JobStatus::BiddingStarted)->latest()->get();
            $get_job_details = [];



            foreach($get_jobs as $job){
                $get_jobs_bidded_by_single_user = CaregiverBidding::where('user_id', Auth::user()->id)->where('job_id', $job->id)->where('status', JobStatus::BiddingStarted )->first();
                    
                if($get_jobs_bidded_by_single_user == null){


                    $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $job->user_id)->first();

                    $details = [
                        'job_id' => $job->id,
                        'company_name' => ucwords($job_owner->company_name),
                        'company_photo' => $job_owner->photo,
                        'job_title' => $job->title,
                        'care_type' => $job->care_type,
                        'care_items' => $job->care_items,
                        'start_date' => $job->start_date,
                        'start_time' => $job->start_time,
                        'end_date' => $job->end_date,
                        'end_time' => $job->end_time,
                        'amount' => $job->amount,
                        'address' => $job->address,
                        'short_address' => $job->short_address,
                        'lat' => $job->lat,
                        'long' => $job->long,
                        'description' => $job->description,
                        'medical_history' => $job->medical_history,
                        'expertise' => $job->expertise,
                        'other_requirements' => $job->other_requirements,
                        'check_list' => $job->check_list,
                        'status' => $job->status,
                        'bidding_start_time' => $job->bidding_start_time,
                        'bidding_end_time' => $job->bidding_end_time,
                        'created_at' => $job->created_at->diffForHumans(),
    
                    ];
    
                    array_push($get_job_details, $details);
                }
            }

            return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
                
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.', null, null, 500);
        }
        
        

    }

    public function getSingleJobForBidding(){
        if(!isset($_GET['job_id'])){
            return $this->error('Oops! Invalid Params Passed', null, null, 400);
        }else{
            $get_job_for_bidding = AgencyPostJob::where('id', $_GET['job_id'])->where('status', JobStatus::BiddingStarted)->first();
            if($get_job_for_bidding == null){
                return $this->error('Oops! Invalid Request', null, null, 500);
            }else{

                $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $get_job_for_bidding->user_id)->first();
                $details = [
                    'job_id' => $get_job_for_bidding->id,
                    'company_name' => ucwords($job_owner->company_name),
                    'company_photo' => $job_owner->photo,
                    'job_title' => $get_job_for_bidding->title,
                    'care_type' => $get_job_for_bidding->care_type,
                    'care_items' => $get_job_for_bidding->care_items,
                    'start_date' => $get_job_for_bidding->start_date,
                    'start_time' => $get_job_for_bidding->start_time,
                    'end_date' => $get_job_for_bidding->end_date,
                    'end_time' => $get_job_for_bidding->end_time,
                    'amount' => $get_job_for_bidding->amount,
                    'address' => $get_job_for_bidding->address,
                    'short_address' => $get_job_for_bidding->short_address,
                    'lat' => $get_job_for_bidding->lat,
                    'long' => $get_job_for_bidding->long,
                    'description' => $get_job_for_bidding->description,
                    'medical_history' => $get_job_for_bidding->medical_history,
                    'expertise' => $get_job_for_bidding->expertise,
                    'other_requirements' => $get_job_for_bidding->other_requirements,
                    'check_list' => $get_job_for_bidding->check_list,
                    'status' => $get_job_for_bidding->status,
                    'bidding_start_time' => $get_job_for_bidding->bidding_start_time,
                    'bidding_end_time' => $get_job_for_bidding->bidding_end_time,
                    'created_at' => $get_job_for_bidding->created_at->diffForHumans(),
    
                ];
    
                return $this->success('Great! Job Fetched Successfully', $details, null, 200);
            }
            
        }
    }

    public function getAllMyBiddedJobs(){
        if(!isset($_GET['id'])){
            return $this->error('Oops! Failed To Fetch Job', null, null, 500);
        }else{
            $get_job_details = [];

            if($_GET['id'] == 0){

                $get_my_bidded_jobs = CaregiverBidding::where('status', JobStatus::BiddingStarted)->where('user_id', Auth::user()->id)->get();
                foreach($get_my_bidded_jobs as $key => $my_jobs){
                    $get_agency_jobs = AgencyPostJob::where('status', JobStatus::BiddingStarted)->where('id', $my_jobs->job_id)->get();
                    foreach($get_agency_jobs as $job){
                        $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $job->user_id)->first();
                        $details = [
                            'job_id' => $job->id,
                            'company_name' => ucwords($job_owner->company_name),
                            'company_photo' => $job_owner->photo,
                            'job_title' => $job->title,
                            'care_type' => $job->care_type,
                            'care_items' => $job->care_items,
                            'start_date' => $job->start_date,
                            'start_time' => $job->start_time,
                            'end_date' => $job->end_date,
                            'end_time' => $job->end_time,
                            'amount' => $job->amount,
                            'address' => $job->address,
                            'short_address' => $job->short_address,
                            'lat' => $job->lat,
                            'long' => $job->long,
                            'description' => $job->description,
                            'medical_history' => $job->medical_history,
                            'expertise' => $job->expertise,
                            'other_requirements' => $job->other_requirements,
                            'check_list' => $job->check_list,
                            'status' => $job->status,
                            'bidding_start_time' => $job->bidding_start_time,
                            'bidding_end_time' => $job->bidding_end_time,
                            'created_at' => $job->created_at->diffForHumans(),
    
                        ];
    
                        array_push($get_job_details, $details);
                    }

                    
                }

                return $this->success('Great! Job Fetched Successfully', array_reverse($get_job_details), null, 200);
            }else{
                
                $get_jobs = AgencyPostJob::where('status', JobStatus::BiddingStarted)->where('id', $_GET['id'])->first();
                $get_job_details = [];
               
                    $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $get_jobs->user_id)->first();
                    $details = [
                        'job_id' => $get_jobs->id,
                        'company_name' => ucwords($job_owner->company_name),
                        'company_photo' => $job_owner->photo,
                        'job_title' => $get_jobs->title,
                        'care_type' => $get_jobs->care_type,
                        'care_items' => $get_jobs->care_items,
                        'start_date' => $get_jobs->start_date,
                        'start_time' => $get_jobs->start_time,
                        'end_date' => $get_jobs->end_date,
                        'end_time' => $get_jobs->end_time,
                        'amount' => $get_jobs->amount,
                        'address' => $get_jobs->address,
                        'short_address' => $get_jobs->short_address,
                        'lat' => $get_jobs->lat,
                        'long' => $get_jobs->long,
                        'description' => $get_jobs->description,
                        'medical_history' => $get_jobs->medical_history,
                        'expertise' => $get_jobs->expertise,
                        'other_requirements' => $get_jobs->other_requirements,
                        'check_list' => $get_jobs->check_list,
                        'status' => $get_jobs->status,
                        'bidding_start_time' => $get_jobs->bidding_start_time,
                        'bidding_end_time' => $get_jobs->bidding_end_time,
                        'created_at' => $get_jobs->created_at->diffForHumans(),

                    ];

                    array_push($get_job_details, $details);

                return $this->success('Great! Job Fetched Successfully', $get_job_details, null, 200);
            }
        }
    }

    public function getAgencyProfile(){
        try{
            if(!isset($_GET['job_id'])){
                return $this->error('Oops! Something Went Wrong. Failed To Get Profile. ', null, null, 400);
            }else{
                if($_GET['job_id'] == null || $_GET['job_id'] == ''){
                    return $this->error('Oops! Something Went Wrong. Failed To Get Profile. ', null, null, 400);
                }else{
                    
                    $get_job = AgencyPostJob::where('id', $_GET['job_id'])->first();
                    $get_agency_profile = AgencyProfileRegistration::where('user_id', $get_job->user_id)->first();

                    $agency_profile = [
                        "company_name" => $get_agency_profile->company_name,
                        "photo" => $get_agency_profile->photo,
                        "about_company" => $get_agency_profile->about_company,
                        'email' => $get_agency_profile->email,
                        "phone" =>  $get_agency_profile->phone,
                        "legal_structure" =>  $get_agency_profile->legal_structure,
                        "organization_type" =>  $get_agency_profile->organization_type,
                        "years_in_business" =>  $get_agency_profile->years_in_business,
                        "state" =>  $get_agency_profile->state,
                        "zip_code" =>  $get_agency_profile->zip_code,
                        "country" =>  $get_agency_profile->country,
                    ];

                    return $this->success('Great! Profile Fetched Successfully', $agency_profile, null, 200);
                }
            }
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong.'.$e, null, null, 500);
        }
    }
}
