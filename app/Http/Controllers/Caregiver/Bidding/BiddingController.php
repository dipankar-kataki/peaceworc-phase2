<?php

namespace App\Http\Controllers\Caregiver\Bidding;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use App\Models\CaregiverProfileRegistration;
use App\Models\CaregiverStatusInformation;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                $get_profile_status = CaregiverStatusInformation::where('user_id', Auth::user()->id )->first();
                if($get_profile_status == null){
                    return $this->error('Oops! Failed to place bid. Profile not completed.', null, null, 400);
                }else{
                    if( !($get_profile_status->is_basic_info_added == 1 && $get_profile_status->is_documents_uploaded == 1) ){
                        return $this->error('Oops! Failed to place bid. Profile not completed', null, null, 400);
                    }else{
                        $is_bid_already_placed = CaregiverBidding::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->exists();
                        
                        if($is_bid_already_placed){
                            return $this->error('Oops! You have already placed your bid for this job.', null, null, 400);
                        }else{
                            $get_job_details = AgencyPostJob::where('id', $request->job_id)->firstOrFail();

                            if($get_job_details->job_type != JobStatus::Open){
                                return $this->error('Oops! Failed to place bid. Not an open job.', null, null, 400);
                            }else{

                                $requested_job_start_date_time = Carbon::parse($get_job_details->start_date.''.$get_job_details->start_time);

                                $get_bidder_rewards = CaregiverProfileRegistration::where('user_id', Auth::user()->id)->first('rewards_earned');

                                if($get_job_details->bidding_start_time == null && $get_job_details->bidding_end_time == null){

                                    $time_diff_btwn_now_and_job_start_in_hrs = Carbon::now()->diffInHours($requested_job_start_date_time);

                                    $bidding_end_time = null;

                                    if($time_diff_btwn_now_and_job_start_in_hrs > 72){
                                        $bidding_end_time = Carbon::now()->addHours(12);
                                    }else if($time_diff_btwn_now_and_job_start_in_hrs > 5 && $time_diff_btwn_now_and_job_start_in_hrs < 72){
                                        $bidding_end_time = Carbon::now()->addHours(4);
                                    }else{
                                        return $this->error('Oops! Bidding is closed for this job.', null, null, 400);
                                    }

                                    try{

                                        DB::beginTransaction();
        
                                        AgencyPostJob::where('id', $request->job_id)->update([
                                            'status' => JobStatus::BiddingStarted,
                                            'bidding_start_time' => Carbon::now(),
                                            'bidding_end_time' => $bidding_end_time
                                        ]);
        
                                        CaregiverBidding::create([
                                            'user_id' => Auth::user()->id,
                                            'job_id' => $request->job_id,
                                            'status' => JobStatus::BiddingStarted,
                                            'rewards_earned' => $get_bidder_rewards->rewards_earned
                                        ]);
        
                                        DB::commit();
        
                                        return $this->success('Great! You have successfully placed your bid.', null, null, 201);
                                    }catch(\Exception $e){
                                        DB::rollBack();

                                        return $this->error('Oops! Something went wrong. Failed to place the bid.', null, null, 400);
                                    }
                                }else{

                                    CaregiverBidding::create([
                                        'user_id' => Auth::user()->id,
                                        'job_id' => $request->job_id,
                                        'status' => JobStatus::BiddingStarted,
                                        'rewards_earned' => $get_bidder_rewards->rewards_earned
                                    ]);
    
                                    return $this->success('Great! You have successfully placed your bid.', null, null, 201);
                                    
                                }
                                
                            }
                        }
                    }
                }
            }catch(\Exception $e){
                Log::error('Failed to place bid. Error ==> '.$e->getMessage().'. On line number ==> '.$e->getLine());
                return $this->error('Oops! Something went wrong. Failed to place bid.', null, null, 500);
            }
            
        }
    }
}
