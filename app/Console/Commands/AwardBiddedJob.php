<?php

namespace App\Console\Commands;

use App\Common\CaregiverNotificationType;
use App\Common\FlagReason;
use App\Common\JobStatus;
use App\Common\StrikeReason;
use App\Models\AgencyPostJob;
use App\Models\AppDeviceToken;
use App\Models\CaregiverBidding;
use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverFlag;
use App\Models\CaregiverNotification;
use App\Models\CaregiverProfileRegistration;
use App\Models\Reward;
use App\Models\Strike;
use App\Traits\BidWonNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AwardBiddedJob extends Command
{
    use BidWonNotification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awardBiddedJob:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will award the job to those caregivers who has bidded for the job by sending push notification.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{

            $get_bidders = CaregiverBiddingResultList::with('job')->where('is_list_generation_complete', 1)->where('is_job_rejected', 0)->where('is_notification_sent', 0)->get();
            foreach($get_bidders as $bidder){

                $get_time_for_notification = $bidder->time_for_notification;
                $get_rewards_earned = CaregiverProfileRegistration::where('user_id', $bidder->user_id)->first('rewards_earned');
                $get_app_device_token = AppDeviceToken::where('user_id', $bidder->user_id)->first('fcm_token');

                if(! Carbon::parse($get_time_for_notification)->gt(Carbon::now())){
                    $data = [

                        'message' => 'Hurray! You won the bid. Please click the accept button to confirm the job.',
                        'title' => 'Bidding Results Declared',
    
                        'job_id' => $bidder->job->id,
                        'job_title' => $bidder->job->job_title,
                        'job_amount' => $bidder->job->job_amount,
                        'job_start_date' => $bidder->job->job_start_date,
                        'job_start_time' => $bidder->job->job_start_time,
                        'job_end_date' => $bidder->job->job_end_date,
                        'job_end_time' =>  $bidder->job->job_end_time, 
                        'care_type' =>  $bidder->job->care_type,
                        'care_items' =>  $bidder->job->care_items,
                        'address' => $bidder->job->short_address,
                        'rewards' => $get_rewards_earned->rewards_earned,
                        'notification_type' => 'fullscreen'
                    ];

                    try{
                        DB::beginTransaction();
                        
                        $this->sendBidWonNotification($get_app_device_token->fcm_token, $data);

                        CaregiverBiddingResultList::where('user_id', $bidder->user_id)->where('job_id', $bidder->job_id)->update([
                            'is_notification_sent' => 1
                        ]);

                        DB::commit();

                    }catch(\Exception $e){
                        DB::rollBack();
                        
                        Log::error('Oops! Something went wrong in sending notification for award bidding job cron.');
                        Log::error('Error ==>'.$e->getMessage().' on line number'.$e->getLine());
                        Log::info('Notification sent cron error. Command exceuted In : ' . Carbon::now());
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                    }
                }
            }
        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in awarding bidded cron job.');
            Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
            Log::info('Awarding bidded job cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
