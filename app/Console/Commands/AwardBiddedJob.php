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
            $get_bidding_result = CaregiverBiddingResultList::with('job')->where('is_list_generation_complete', 1)->where('is_bidded_job_awarded', 0)->where('is_job_rejected', 0)->orderBy('caregiver_bid_win_position')->get();
            foreach($get_bidding_result as $bid_result){

                $get_app_device_token = AppDeviceToken::where('user_id', $bid_result->user_id)->first();
                $get_rewards_earned = CaregiverProfileRegistration::where('user_id', $bid_result->user_id)->first();

                $job_start_date_time = Carbon::parse($bid_result->job->start_date.''.$bid_result->job->start_time);

                $current_time = Carbon::now();

                $time_diff_in_hours = $current_time->diffInHours($job_start_date_time);

                if($time_diff_in_hours > 5){
                    if($bid_result->is_notification_sent == 0){

                        $data = [
                            'message' => 'Hurray! You won the bid. Please click the accept button to confirm the job.',
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
                }else{

                    $get_rewards = Reward::where('user_id', $bid_result->user_id)->sum('total_rewards');
                    $get_final_rewards_earned = CaregiverProfileRegistration::where('user_id', $bid_result->user_id)->first();
                    $my_rewards = 0;
                    if($get_rewards != null){
                        $my_rewards = $get_rewards;
                    }

                    $get_flags = CaregiverFlag::where('user_id', $bid_result->user_id)->count();
                    $get_strikes = Strike::where('user_id', $bid_result->user_id)->count();
                    $banned_from_bidding = null;
                    $banned_from_quick_call = null;
                    $loss_of_rewards = 0;
                    $flag_number = 0;
                    $strike_number = 0;

                    if($get_flags > 0 && $get_flags < 4){

                        $get_users_last_flag = CaregiverFlag::where('user_id', $bid_result->user_id)->where('status', 1)->latest()->first(); 
                        if($get_users_last_flag->flag_number == 1){
                            $banned_from_bidding = Carbon::now()->addHours(48);
                            $banned_from_quick_call = Carbon::now()->addDays(15);
                            $loss_of_rewards = round((1/3)*$my_rewards);
                            $flag_number = 2;


                        }else if($get_users_last_flag->flag_number == 2){
                            $banned_from_bidding = Carbon::now()->addHours(72);
                            $banned_from_quick_call = Carbon::now()->addDays(21);
                            $loss_of_rewards = round((1/3)*$my_rewards);
                            $flag_number = 3;

                        }
                    }else{
                        $banned_from_bidding = Carbon::now()->addHours(24);
                        $banned_from_quick_call = Carbon::now()->addDays(7);
                        $loss_of_rewards = 0;
                        $flag_number = 1;
                    }
                
                    if($get_strikes > 0 && $get_strikes < 4){

                        $get_users_last_strike = Strike::where('user_id', $bid_result->user_id)->where('status', 1)->latest()->first(); 
                        if($get_users_last_strike->strike_number == 1){
                            $banned_from_bidding = Carbon::now()->addWeek();
                            $banned_from_quick_call = Carbon::now()->addDays(45);
                            $loss_of_rewards = round((1/3)*$my_rewards);
                            $strike_number = 2;


                        }else if($get_users_last_strike->strike_number == 2){
                            $banned_from_bidding = Carbon::now()->addWeeks(2);
                            $banned_from_quick_call = Carbon::now()->addDays(60);
                            $loss_of_rewards = round((1/3)*$my_rewards);
                            $strike_number = 3;

                        }
                    }else{
                        $banned_from_bidding = Carbon::now()->addHours(96);
                        $banned_from_quick_call = Carbon::now()->addDays(30);
                        $loss_of_rewards = 0;
                        $strike_number = 1;
                    }

                    try{
                        DB::beginTransaction();

                        AgencyPostJob::where('id', $bid_result->job_id)->update([
                            'status' => JobStatus::QuickCall
                        ]);

                        CaregiverBidding::where('job_id', $bid_result->job_id)->update([
                            'status' => JobStatus::JobNotAccepted
                        ]);

                        CaregiverBiddingResultList::where('job_id', $bid_result->job_id)->update([
                            'is_job_rejected' => 1
                        ]);

                        if($get_strikes < 4 && $get_flags >= 3){

                            Strike::create([
                                'user_id' => $bid_result->user_id,
                                'job_id' => $bid_result->job_id,
                                'strike_reason' => StrikeReason::JobNotAccepted,
                                'start_date_time' => Carbon::now(),
                                'end_date_time' => $banned_from_quick_call,
                                'banned_from_bidding' => $banned_from_bidding,
                                'banned_from_quick_call' => $banned_from_quick_call,
                                'rewards_loose' => $loss_of_rewards,
                                'strike_number' => $strike_number
                            ]);

                            CaregiverNotification::create([
                                'user_id' => $bid_result->user_id,
                                'content' => 'Hey there, you received a STRIKE for not accepting the job named "'.$bid_result->job->title.'" on time.',
                                'type' => CaregiverNotificationType::Strike
                            ]);


                        }else if($get_strikes == 0 && ( $get_flags > 0 && $get_flags < 4 )){
                            CaregiverFlag::create([
                                'user_id' => $bid_result->user_id,
                                'job_id' => $bid_result->job_id,
                                'flag_reason' => FlagReason::JobNotAccepted,
                                'start_date_time' => Carbon::now(),
                                'end_date_time' => $banned_from_quick_call,
                                'banned_from_bidding' => $banned_from_bidding,
                                'banned_from_quick_call' => $banned_from_quick_call,
                                'rewards_loose' => $loss_of_rewards,
                                'flag_number' => $flag_number
                            ]);

                            CaregiverNotification::create([
                                'user_id' => $bid_result->user_id,
                                'content' => 'Hey there, you received a FLAG for not accepting the job named "'.$bid_result->job->title.'" on time.',
                                'type' => CaregiverNotificationType::Flag
                            ]);

                        }else if($get_strikes == 0 && $get_flags == 0){
                            CaregiverFlag::create([
                                'user_id' => $bid_result->user_id,
                                'job_id' => $bid_result->job_id,
                                'flag_reason' => FlagReason::JobNotAccepted,
                                'start_date_time' => Carbon::now(),
                                'end_date_time' => $banned_from_quick_call,
                                'banned_from_bidding' => $banned_from_bidding,
                                'banned_from_quick_call' => $banned_from_quick_call,
                                'rewards_loose' => $loss_of_rewards,
                                'flag_number' => $flag_number
                            ]);

                            CaregiverNotification::create([
                                'user_id' => $bid_result->user_id,
                                'content' => 'Hey there, you received a FLAG for not accepting the job named "'.$bid_result->job->title.'" on time.',
                                'type' => CaregiverNotificationType::Flag
                            ]);
                        }

                        CaregiverProfileRegistration::where('user_id', $bid_result->user_id)->update([
                            'rewards_earned' => $get_final_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_final_rewards_earned->rewards_earned - $loss_of_rewards) )
                        ]);

                        Reward::create([
                            'user_id' => $bid_result->user_id,
                            'job_id' => $bid_result->job_id,
                            'total_rewards' => $get_final_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_final_rewards_earned->rewards_earned - $loss_of_rewards) )
                        ]);



                        DB::commit();


                    }catch(\Exception $e){

                        DB::rollBack();

                        Log::error('Oops! Something went wrong in moving awarded job to quick call cron job.');
                        var_dump('Error ==>', $e->getMessage());
                        Log::info('Moving awarded job to quick call cron error. Command exceuted In : ' . Carbon::now());
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
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
