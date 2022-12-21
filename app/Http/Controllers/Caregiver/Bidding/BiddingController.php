<?php

namespace App\Http\Controllers\Caregiver\Bidding;

use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                $check_if_bidded = CaregiverBidding::where('user_id', Auth::user()->id)->where('job_id', $request->job_id)->exists();
                if($check_if_bidded){
                    return $this->error('Oops! Bid Already Recorded For This Job. ', null, null, 400);
                }else{
                    try{
                        $get_job = AgencyPostJob::where('id', $request->job_id)->first();

                        if($get_job->bidding_start_time != null && $get_job->bidding_end_time != null){
                            $create = CaregiverBidding::create([
                                'user_id' => Auth::user()->id,
                                'job_id' => $request->job_id
                            ]);
                        }else{
                            
                            $full_job_date_time = Carbon::parse($get_job->date.''.$get_job->start_time);
                            $current_time = Carbon::now();
    
                            $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                            $from = Carbon::createFromFormat('Y-m-d H:s:i', $full_job_date_time);
    
                            $diff_in_hours = $to->diffInHours($from);
                            
                            $bidding_start_time = 0;
                            $bidding_end_time = 0;
    
                            if( $diff_in_hours > 72){
                                $bidding_start_time = $current_time;
                                $bidding_end_time = Carbon::now()->addHours(12);
                            }else if($diff_in_hours <= 72 && $diff_in_hours > 5){
                                $bidding_start_time = $current_time;
                                $bidding_end_time = Carbon::now()->addHours(3);
                            }
    
                            $create = CaregiverBidding::create([
                                'user_id' => Auth::user()->id,
                                'job_id' => $request->job_id
                            ]);
    
                            if($create){
    
                                AgencyPostJob::where('id', $request->job_id)->update([
                                    'status' => JobStatus::BiddingStarted,
                                    'bidding_start_time' => $bidding_start_time,
                                    'bidding_end_time' => $bidding_end_time
                                ]);
                            }
                        }

                        return $this->success('Great! Bid Successfully Recorded. ', null, null, 200);
                    }catch(\Exception $e){
                        return $this->error('Oops! Something Went Wrong. Server Error. '.$e, null, null, 500);
                    }
                }
            }catch(\Exception $e){
                return $this->error('Oops! Something Went Wrong. Server Error. ', null, null, 500);
            }
            
        }
    }
}
