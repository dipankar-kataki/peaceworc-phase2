<?php

namespace App\Http\Controllers\TempTesting;

use App\Http\Controllers\Controller;
use App\Traits\FullScreenNotification;
use Illuminate\Http\Request;

class SendNotificationController extends Controller
{
    use FullScreenNotification;

    public function testFullNotification(){
                $data['message'] = 'Hurrah! You have won the bid. Please click the accept button to Accept the job.';
                $data['title'] = 'Bidding results declared.';
                // $data['key'] = [
                //     'job_id' => $get_bidded_jobs->job_id,
                //     'job_title' => $get_bidded_jobs->job->title,
                //     'job_amount' => $get_bidded_jobs->job->amount,
                //     'job_start_time' => $get_bidded_jobs->job->start_time,
                //     'job_end_time' => $get_bidded_jobs->job->end_time,

                // ];
                $data['job_id'] = 1;
                $data['job_title'] = 'Urgent testing full notification';
                $data['job_amount'] = 200;
                $data['job_start_date'] = '11-20-2023';
                $data['job_start_time'] = '10:00:00';
                $data['job_end_date'] = '11-21-2023';
                $data['job_end_time'] = '15:00:00';
                $data['care_type'] = 'Patient care';
                $data['care_items'] = 'medicare';
                $data['notification_type'] = 'fullscreen';
                $token = 'fo596PJvSGOOTs0j-kC0yl:APA91bFTyenroHQjHeZ_MNgxKMFCG_aFsUFonVkL8VPoPCEU1xU34f5LHEKcBrcQLLa53fShl-3clPNGNqhQjSGwiynqLZh9QOXxNxvv_tPwXK-jo2mUCOJTd4HdpNeC6UwZHE_0hym4';
                $this->sendNotification($token, $data);
    }
}
