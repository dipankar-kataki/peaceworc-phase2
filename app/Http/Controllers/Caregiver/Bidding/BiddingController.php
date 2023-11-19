<?php

namespace App\Http\Controllers\Caregiver\Bidding;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use App\Models\CaregiverStatusInformation;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BiddingController extends Controller
{   
    use ApiResponse;

    public function submitBid(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! Validation Failed. '.$validator->errors()->first(), null, null, 400);
        }else{

            try{

                $get_profile = CaregiverStatusInformation::where('user_id', Auth::user()->id)->first();
                if($get_profile != null){
                    if($get_profile->is_basic_info_added == 1 && $get_profile->is_documents_uploaded == 1){

                        $get_requested_job = AgencyPostJob::where('id', $request->job_id)->first();

                        if($get_requested_job == null){
                            return $this->error('Oops! Failed to place the bid. Job may be expired or deleted by the agency.', null, null, 400);
                        }else{
                            
                            if($get_requested_job->bidding_start_time != null && $get_requested_job->bidding_end_time != null){
                                $check_if_user_has_already_bidded = CaregiverBidding::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->exists();
    
                                if( $check_if_user_has_already_bidded ){
                                    return $this->error('Oops! You have already placed your bid.', null, null, 400);
                                }else{
                                    CaregiverBidding::create([
                                        'user_id' => Auth::user()->id,
                                        'job_id' => $request->job_id,
                                        'status' => JobStatus::BiddingStarted
                                    ]);
    
                                    return $this->success('Great! You have successfully placed your bid.', null, null, 201);
    
                                }
                            }else{
    
                                $current_time = Carbon::now();
                                $requested_job_start_date_time = Carbon::parse($get_requested_job->start_date.''.$get_requested_job->start_time);
    
                                $time_diff = $requested_job_start_date_time->diff($current_time);
    
                                $time_diff_in_days =  $time_diff->format('%d');
                                $time_diff_in_hours =  null;
    
                                $bidding_start_time = null;
                                $bidding_end_time = null;
    
                                if($time_diff_in_days != 0){
                                    $time_diff_in_hours = ( $time_diff_in_days * 24);
                                }else{
                                    $time_diff_in_hours =  $time_diff->format('%h');
                                }
    
                            
    
                                if($time_diff_in_hours > 72){
                                    $bidding_start_time = $current_time;
                                    $bidding_end_time = $current_time->copy()->addHours(12);
    
                                    try{
                                        DB::beginTransaction();
        
                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::BiddingStarted,
                                            'bidding_start_time' => $bidding_start_time,
                                            'bidding_end_time' => $bidding_end_time
                                        ]);
        
                                        CaregiverBidding::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::BiddingStarted,
                                        ]);
        
                                        DB::commit();
        
                                        return $this->success('Great! You have successfully placed your bid.', null, null, 201);
        
                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        return $this->error('Oops! Something went wrong. Failed to place bid.', null, null, 500);
                                    }
    
                                }else if( $time_diff_in_hours > 7 && $time_diff_in_hours < 72 ){
                                    $bidding_start_time = $current_time;
                                    $bidding_end_time = $current_time->copy()->addHours(3);
    
                                    try{
                                        DB::beginTransaction();
        
                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::BiddingStarted,
                                            'bidding_start_time' => $bidding_start_time,
                                            'bidding_end_time' => $bidding_end_time
                                        ]);
        
                                        CaregiverBidding::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::BiddingStarted,
                                        ]);
        
                                        DB::commit();
        
                                        return $this->success('Great! You have successfully placed your bid.', null, null, 201);
        
                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        return $this->error('Oops! Something went wrong. Failed to place bid.', null, null, 500);
                                    }
    
                                }else{
                                    try{
    
                                        DB::beginTransaction();
    
                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::JobAccepted
                                        ]);
    
                                        AcceptJob::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::JobAccepted,
                                            'job_accepted_time' => Carbon::now()
                                        ]);
    
                                        DB::commit();
    
                                        return $this->success('Great! You have been awarded the job. Please start the job at appropriate time.', null, null, 201);
    
                                    }catch(\Exception $e){
                                        DB::rollBack();
                                        return $this->error('Oops! Something went wrong. Not able to place bid.', null, null, 400);
                                    }
                                }
    
                                
                            }
                        }
                        
                    }else{
                        return $this->error('Oops! Please complete your profile to start bidding.', null, null, 400);
                    }
                }else{
                    return $this->error('Oops! Please complete your profile to start bidding.', null, null, 400);
                }
                
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong.', null, null, 500);
            }
            
        }
    }
}
