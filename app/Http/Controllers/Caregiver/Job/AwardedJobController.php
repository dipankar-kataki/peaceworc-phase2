<?php

namespace App\Http\Controllers\Caregiver\Job;

use App\Common\AgencyNotificationType;
use App\Common\CaregiverNotificationType;
use App\Common\JobStatus;
use App\Http\Controllers\Controller;
use App\Models\AcceptJob;
use App\Models\AgencyNotification;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverNotification;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AwardedJobController extends Controller
{
    use ApiResponse;

    public function acceptAwardedJob(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{

                $get_job = AgencyPostJob::where('id', $request->job_id)->first();

                if($get_job != null){

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

                        CaregiverBidding::where('job_id', $request->job_id)->update([
                            'is_job_awarded' => 1
                        ]);

                        CaregiverBiddingResultList::where('job_id', $request->job_id)->where('user_id', Auth::user()->id)->update([
                            // 'is_job_accepted' => DB::raw('IF(user_id = ' . Auth::user()->id . ', 1, 0)'),
                            'is_job_accepted' => 1
                        ]);

                        CaregiverNotification::create([
                            'user_id' => Auth::user()->id,
                            'content' => 'Hurray! you have been awarded the job named "'.$get_job->title.'"',
                            'type' => CaregiverNotificationType::Job
                        ]);

                        AgencyNotification::create([
                            'user_id' => $get_job->user_id,
                            'content' => 'Hey there, your posted job named "'.$get_job->title.'" has been accepted by '.Auth::user()->name,
                            'type' => AgencyNotificationType::Job
                        ]);

                        DB::commit();

                        return $this->success('Great! Job accepted successfully.', null, null, 201);
                    }catch(\Exception $e){
                        DB::rollBack();
                
                        return $this->error('Oops! Something went wrong while accepting awarded jobs', null, null, 500);
                    }
                    
                }else{
                    return $this->error('Oops! Failed to accept the job. Job may be expired or deleted by agency.', null, null, 400);
                }

                
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong while accepting awarded jobs', null, null, 500);
            }
        }
    }

    public function rejectAwardedJob(Request $request){
        $validator = Validator::make($request->all(), [
            'job_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->error('Oops! '.$validator->errors()->first(), null, null, 400);
        }else{
            try{
                CaregiverBiddingResultList::where('job_id', $request->job_id)->where('user_id', Auth::user()->id)->where('is_job_rejected', 0)->where('is_notification_sent', 1)->update([
                    'is_job_rejected' => 1
                ]);

                return $this->success('Great! Job rejected successfully', null, null, 200);
            }catch(\Exception $e){
                return $this->error('Oops! Something went wrong while rejecting job.', null, null, 500);
            }
        }
    }
}
