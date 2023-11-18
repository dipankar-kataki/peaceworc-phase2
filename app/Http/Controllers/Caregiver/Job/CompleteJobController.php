<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\AgencyProfileRegistration;
use App\Models\CaregiverProfileRegistration;
use App\Models\Reward;
use App\Traits\ApiResponse;
use App\Traits\JobDistance;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CompleteJobController extends Controller
{   
    use ApiResponse, JobDistance;

    public function getCompleteJob(){
        try{
            $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::Completed)->latest()->paginate('5');

            $all_details = [];
            
            foreach($get_job as $job){
                $agency = AgencyProfileRegistration::where('user_id', $job->job->user_id)->select('user_id','company_name','photo')->first();


                $details = [
                    'job_id' => $job->job_id,
                    'agency_id' => $agency->user_id,
                    'agency_name' => ucwords($agency->company_name),
                    'agency_photo' => $agency->photo,
                    'agency_address' => $job->job->short_address,
                    'title' => $job->job->title,
                    'care_type' => $job->job->care_type,
                    'care_items' => $job->job->care_items,
                    'start_date' => Carbon::parse($job->job->start_date)->format('m-d-Y') ,
                    'start_time' => $job->job->start_time,
                    'end_date' => Carbon::parse($job->job->end_date)->format('m-d-Y') ,
                    'end_time' => $job->job->end_time,
                    'amount' => $job->job->amount,
                    'address' => ($job->job->floor_no != null ?  $job->job->floor_no.', ' : '').''.( $job->job->appartment_or_unit != null ?  $job->job->appartment_or_unit.', ' : '').''.( $job->job->address),
                    'short_address' => $job->job->short_address,
                    'description' => $job->job->description,
                    'medical_history' => $job->job->medical_history,
                    'expertise' => $job->job->expertise,
                    'other_requirements' => $job->job->other_requirements,
                    'check_list' => $job->job->check_list,
                    'lat' => $job->job->lat,
                    'long' => $job->job->long,
                    'status' => $job->job->status,
                ];
                array_push($all_details, $details);
                
            }
                
                
            return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job '. $e, null, null, 500);
        }
       
    }

    public function completeJob(Request $request){
        $validator = Validator::make($request->all(),[
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                $get_job = AcceptJob::with('job')->where('job_id', $request->job_id)->first();
                $job_start_date_time = Carbon::parse($get_job->job_start_date.''.$get_job->start_time);
                $get_total_rewards_earned = CaregiverProfileRegistration::where('user_id', $get_job->user_id)->first();
                
                

                $time_diff_in_hours = $get_job->job_accepted_time->diffInHours($job_start_date_time);

                $earned_rewards = 1;


                if( $time_diff_in_hours <= 3 && $time_diff_in_hours > 2){
                    $earned_rewards = 3;
                }else if( $time_diff_in_hours <= 2  && $time_diff_in_hours > 1){
                    $earned_rewards = 5;
                }else if( $time_diff_in_hours <= 1){
                    $earned_rewards = 15;
                }
                

                try{
                

                    DB::beginTransaction();
    
                    AgencyPostJob::where('id', $request->job_id)->update([
                        'status' => JobStatus::Completed
                    ]);
    
                    AcceptJob::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->update([
                        'status' => JobStatus::Completed
                    ]);
    
                    Reward::create([
                        'user_id' => Auth::user()->id,
                        'job_id' => $request->job_id,
                        'total_rewards' => $get_total_rewards_earned->rewards_earned + $earned_rewards
                    ]);

                    CaregiverProfileRegistration::where('user_id', $get_job->user_id)->update([
                        'rewards_earned' => $get_total_rewards_earned->rewards_earned + $earned_rewards
                    ]);


    
                    DB::commit();
    
                    return $this->success('Great! Job Completed Successfully.', null, null, 201);
                }catch(\Exception $e){
                    
                    DB::rollBack();
                    Log::error('Oops! Failed to complete job. Error in complete job.');
                    return $this->error('Oops! Something Went Wrong.', null, null, 500);
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong.', null, null, 500);
            }
            
        }
    }

    public function getCompleteJobDetails(Request $request){
        try{
            if(!isset($_GET['id'])){
                return $this->error('Oops! Failed To Fetch Job', null, null, 500);
            }else{
                $get_user = User::where('id', Auth::user()->id)->first();
                $lat1 = $get_user->lat;
                $long1 = $get_user->long;
    
                $get_jobs = AgencyPostJob::where('status', JobStatus::Completed)->where('id', $_GET['id'])->first();
                $get_job_details = [];
    
                $lat2 = $get_jobs->lat;
                $long2 = $get_jobs->long;
    
                $miles = $this->jobDistance($lat1, $long1, $lat2, $long2, 'M');
                
                $job_owner = AgencyProfileRegistration::with('user')->where('user_id', $get_jobs->user_id)->first();
                $details = [
                    'job_id' => $get_jobs->id,
                    'agency_id' => $get_jobs->user_id,
                    'owner_name' =>  $job_owner->user->name,
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
                    'address' => ($get_jobs->floor_no != null ? $get_jobs->floor_no.', ' : '').''.($get_jobs->appartment_or_unit != null ? $get_jobs->appartment_or_unit.', ' : '').''.($get_jobs->address),
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
        }catch(\Exception $e){
            return $this->error('Oops! Something Went Wrong', null, null, 500);
        }
        
    }
}
