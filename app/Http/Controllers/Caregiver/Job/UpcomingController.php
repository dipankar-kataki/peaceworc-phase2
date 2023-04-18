<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyProfileRegistration;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpcomingController extends Controller
{
    use ApiResponse;
    public function getUpcomingJob(){

        if(!isset($_GET['job_id'])){
            return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
        }else{
            if($_GET['job_id'] == null || $_GET['job_id'] == ''){
                return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
            }else{
                if($_GET['job_id'] == 0){
                    try{
                        $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('status', JobStatus::JobAccepted)->get();
                        
                        $all_details = [];
                        foreach($get_job as $job){
                            $agency = AgencyProfileRegistration::where('user_id',$job->job->user_id)->select('company_name','photo','floor_no','appartment_or_unit','street','city_or_district','state','zip_code','country')->first();
            
            
                
                            $full_job_date_time = Carbon::parse($job->job->date.''.$job->job->start_time);
                            $current_time = Carbon::now();
            
                            $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                            $from = Carbon::createFromFormat('Y-m-d H:s:i', $full_job_date_time);
            
                            $diff_in_hours = $to->diffInRealHours($from);
            
                           
            
                            if( $from < $to ){
                                $diff_in_hours = 0;
                            }
            
                            $details = [
                                'job_id' => $job->job_id,
                                'agency_name' => ucwords($agency->company_name),
                                'agency_photo' => $agency->photo,
                                'agency_address' => $agency->floor_no ?? '' .', '.$agency->appartment_or_unit ?? '' .', '.$agency->street.', '.$agency->city_or_district.', '.$agency->state.', '.$agency->zip_code.', '.$agency->country,
                                'title' => $job->job->title,
                                'care_type' => $job->job->care_type,
                                'care_items' => $job->job->care_items,
                                'start_date' => Carbon::parse($job->job->start_date)->format('m-d-Y'),
                                'start_time' => $job->job->start_time,
                                'end_date' => Carbon::parse($job->job->end_date)->format('m-d-Y'),
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
                    try{
                        $get_job = AcceptJob::with('job')->where('user_id', Auth::user()->id)->where('job_id', $_GET['job_id'])->where('status', JobStatus::JobAccepted)->first();
                        
                        $all_details = [];
                            $agency = AgencyProfileRegistration::where('user_id',$get_job->job->user_id)->select('company_name','photo','floor_no','appartment_or_unit','street','city_or_district','state','zip_code','country')->first();
            
            
                
                            $full_job_date_time = Carbon::parse($get_job->job->start_date.''.$get_job->job->start_time);
                            $current_time = Carbon::now();
            
                            $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                            $from = Carbon::createFromFormat('Y-m-d H:s:i', $full_job_date_time);
            
                            $diff_in_hours = $to->diffInRealHours($from);
            
                           
            
                            if( $from < $to ){
                                $diff_in_hours = 0;
                            }
            
                            $details = [
                                'job_id' => $get_job->job_id,
                                'agency_name' => ucwords($agency->company_name),
                                'agency_photo' => $agency->photo,
                                'agency_address' => $agency->floor_no ?? '' .', '.$agency->appartment_or_unit ?? '' .', '.$agency->street.', '.$agency->city_or_district.', '.$agency->state.', '.$agency->zip_code.', '.$agency->country,
                                'title' => $get_job->job->title,
                                'care_type' => $get_job->job->care_type,
                                'care_items' => $get_job->job->care_items,
                                'start_date' => Carbon::parse($get_job->job->start_date)->format('m-d-Y'),
                                'start_time' => $get_job->job->start_time,
                                'end_date' => Carbon::parse($get_job->job->end_date)->format('m-d-Y'),
                                'end_time' => $get_job->job->end_time,
                                'amount' => $get_job->job->amount,
                                'address' => $get_job->job->address,
                                'short_address' => $get_job->job->short_address,
                                'description' => $get_job->job->description,
                                'medical_history' => $get_job->job->medical_history,
                                'expertise' => $get_job->job->expertise,
                                'other_requirements' => $get_job->job->other_requirements,
                                'check_list' => $get_job->job->check_list,
                                'lat' => $get_job->job->lat,
                                'long' => $get_job->job->long,
                                'status' => $get_job->job->status,
                                'job_starts_in' => $diff_in_hours
                            ];

                            array_push($all_details, $details);

                        return $this->success('Great! Job Fetched Successfully', $all_details, null, 200);
                    }catch(\Exception $e){
                        return $this->error('Oops! Something Went Wrong. Failed To Fetch Job ', null, null, 500);
                    }
                }
            }
        }
        
    }
}
