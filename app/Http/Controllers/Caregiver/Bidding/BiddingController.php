<?php

namespace App\Http\Controllers\Caregiver\Bidding;

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
                        $job_start_time = $get_job->start_time;
                        // $full_job_date_time = Carbon::parse($get_job->date.''.$job_start_time);
                        $current_time = Carbon::now();

                        // $time_diff_btwn_job_time_and_current_time = $current_time - $full_job_date_time ;

                        return $this->success('Great! Bid Successfully Recorded. ', $current_time, null, 200);
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
