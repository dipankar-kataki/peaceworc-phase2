<?php

namespace App\Console\Commands;

use App\Models\AppDeviceToken;
use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverFlag;
use App\Models\CaregiverProfileRegistration;
use App\Models\Reward;
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
            $get_bidding_result = CaregiverBiddingResultList::with('job')->where('is_list_generation_complete', 1)->where('is_bidded_job_awarded', 0)->orderBy('caregiver_bid_win_position')->get();
            foreach($get_bidding_result as $bid_result){

                $get_app_device_token = AppDeviceToken::where('user_id', $bid_result->user_id)->first();
                $get_rewards_earned = CaregiverProfileRegistration::where('user_id', $bid_result->user_id)->first();

                if($bid_result->is_notification_sent == 0){

                    $data = [
                        'message' => 'Hurray! You have won the bid. Please click accept button to Accept the job',
                        'title' => 'Bidding Results Declared',
    
                        'job_id' => $bid_result->job->id,
                        'job_title' => $bid_result->job->job_title,
                        'job_amount' => $bid_result->job->job_amount,
                        'job_start_date' => $bid_result->job->job_start_date,
                        'job_start_time' => $bid_result->job->job_start_time,
                        'job_end_date' => $bid_result->job->job_end_date,
                        'job_end_time' =>  $bid_result->job->job_end_time, 
                        'care_type' =>  $bid_result->job->care_type,
                        'care_items' =>  $bid_result->job->care_items,
                        'address' => $bid_result->job->short_address,
                        'rewards' => $get_rewards_earned->rewards_earned,
                        'notification_type' => 'fullscreen'
                    ];
    
                    if( !Carbon::parse($bid_result->time_for_notification)->gt( Carbon::now() ) ){

                        try{
                            DB::beginTransaction();
                            
                            $this->sendBidWonNotification($get_app_device_token, $data);

                            CaregiverBiddingResultList::where('user_id', $bid_result->user_id)->where('job_id', $bid_result->job_id)->update([
                                'is_notification_sent' => 1
                            ]);

                            DB::commit();

                        }catch(\Exception $e){
                            DB::rollBack();
                            
                            Log::error('Oops! Something went wrong in sending notification for award bidding job cron.');
                            var_dump('Error ==>', $e->getMessage());
                            Log::info('Notification sent cron error. Command exceuted In : ' . Carbon::now());
                            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                        }
                        


                    }

                }
            }
        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in awarding bidded cron job.');
            var_dump('Error ==>', $e->getMessage());
            Log::info('Awarding bidded job cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
