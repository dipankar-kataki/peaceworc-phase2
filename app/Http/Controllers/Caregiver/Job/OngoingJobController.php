<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyProfileRegistration;
use App\Models\User;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OngoingJobController extends Controller
{
    use ApiResponse;
    public function ongoingJob(){
        if(!isset($_GET['type'])){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job', null, null, 500);
        }else{
            if($_GET['type'] == 'ongoing'){
                try{
                    $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::OnGoing)->get();
                    $all_details = [];
                    foreach($get_job as $job){
                        $agency = AgencyProfileRegistration::where('user_id',$job->job->user_id)->select('company_name','photo')->first();
        
        
                        $details = [
                            'agency_name' => $agency->company_name,
                            'agency_photo' => $agency->photo,
                            'title' => $job->job->title,
                            'care_type' => $job->job->care_type,
                            'care_items' => $job->job->care_items,
                            'date' => $job->job->date,
                            'start_time' => $job->job->start_time,
                            'end_time' => $job->job->end_time,
                            'amount' => $job->job->amount,
                            'lat' => $job->job->lat,
                            'long' => $job->job->long,
                            'status' => $job->job->status,
                        ];
                           array_push($all_details, $details);
                    }
        
                    
                    return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);
                }catch(\Exception $e){
                    return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
                }
            }else if($_GET['type'] == 'upcoming'){
                try{
                    $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->get();
                    
                    $all_details = [];
                    foreach($get_job as $job){
                        $agency = AgencyProfileRegistration::where('user_id',$job->job->user_id)->select('company_name','photo')->first();
        
                        // $job_date = DateTime::createFromFormat("Y-m-d" , $job->job->date);
            
                        $full_job_date_time = Carbon::parse($job->job->date.''.$job->job->start_time);
                        $current_time = Carbon::now();
        
                        $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                        $from = Carbon::createFromFormat('Y-m-d H:s:i', $full_job_date_time);
        
                        $diff_in_hours = $to->diffInRealHours($from);
        
                        // $status = 0;
        
                        if( $from < $to ){
                            $diff_in_hours = 0;
                        }
        
                        $details = [
                            'agency_name' => $agency->company_name,
                            'agency_photo' => $agency->photo,
                            'title' => $job->job->title,
                            'care_type' => $job->job->care_type,
                            'care_items' => $job->job->care_items,
                            'date' => $job->job->date,
                            'start_time' => $job->job->start_time,
                            'end_time' => $job->job->end_time,
                            'amount' => $job->job->amount,
                            'lat' => $job->job->lat,
                            'long' => $job->job->long,
                            'status' => $job->job->status,
                            'job_starts_in' => $diff_in_hours
                        ];
                        array_push($all_details, $details);
                    }
        
                   
                    return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);
                }catch(\Exception $e){
                    return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
                }
            }else{
                return $this->error('Oops! Something Went Wrong. Failed To Fetch Job Else==>', null, null, 500);
            }
        }
        
        

    }
}
