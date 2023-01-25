<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Http\Controllers\Controller;
use App\Models\CaregiverBidding;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\FullScreenNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetBiddingResultsController extends Controller
{
    use ApiResponse, FullScreenNotification;

    public function getBiddingResult(){
        $get_bidded_jobs = CaregiverBidding::with('job')->where('user_id', Auth::user()->id)->first();
        if($get_bidded_jobs != null){

            
            $user_details = User::where('id', Auth::user()->id)->first();
            if($user_details->fcm_token != null){
                $data=[];
                $data['message'] = 'PeaceWorc';
                // $data['key'] = [
                //     'job_id' => $get_bidded_jobs->job_id,
                //     'job_title' => $get_bidded_jobs->job->title,
                //     'job_amount' => $get_bidded_jobs->job->amount,
                //     'job_start_time' => $get_bidded_jobs->job->start_time,
                //     'job_end_time' => $get_bidded_jobs->job->end_time,

                // ];
                $data['job_id'] = $get_bidded_jobs->job_id;
                $data['job_title'] = $get_bidded_jobs->job->title;
                $data['job_amount'] = $get_bidded_jobs->job->amount;
                $data['job_date'] = Carbon::parse($get_bidded_jobs->job->date)->format('M-d, Y');
                $data['job_start_time'] = $get_bidded_jobs->job->amount;
                $data['job_end_time'] = $get_bidded_jobs->job->end_time;
                $data['care_type'] = $get_bidded_jobs->job->care_type;
                $data['care_items'] = $get_bidded_jobs->job->care_items;
                $data['notification_type'] = 'fullscreen';
                $token = [];
                $token[] = $user_details->fcm_token;
                $this->sendNotification($token, $data);
            }

            return $this->success('Bidding Results fetched', null, null, 200);
        }
    }
}
