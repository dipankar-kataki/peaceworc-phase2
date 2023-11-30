<?php

namespace App\Console\Commands;

use App\Common\CaregiverNotificationType;
use App\Common\FlagReason;
use App\Common\JobStatus;
use App\Common\StrikeReason;
use App\Models\CaregiverBidding;
use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverFlag;
use App\Models\CaregiverNotification;
use App\Models\CaregiverProfileRegistration;
use App\Models\Reward;
use App\Models\Strike;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StrikeAndFlagForJobNotAccepting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strikeAndFlagForJobNotAccepting:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will give strike or flag to those caregivers who has not accepted the job after bidding.';

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

            $get_bidders = CaregiverBiddingResultList::with('job')->where('is_list_generation_complete', 1)->where('is_job_rejected', 1)->where('is_notification_sent', 1)->get();

            $banned_from_bidding = null;
            $banned_from_quick_call = null;
            $loss_of_rewards = 0;
            $flag_number = 0;
            $strike_number = 0;

            foreach($get_bidders as $bidder){
                
                $get_bidder_total_rewards_earned = CaregiverProfileRegistration::where('user_id', $bidder->user_id)->first('rewards_earned');
                $get_bidder_total_strikes = Strike::where('user_id', $bidder->user_id)->count();
                $get_bidder_total_flags = CaregiverFlag::where('user_id', $bidder->user_id)->count();

                if($get_bidder_total_flags < 3){

                    $get_users_last_flag = CaregiverFlag::where('user_id', $bidder->user_id)->where('status', 1)->latest()->first(); 

                    if($get_users_last_flag == null){
                        $banned_from_bidding = Carbon::now()->addHours(24);
                        $banned_from_quick_call = Carbon::now()->addDays(7);
                        $loss_of_rewards = 0;
                        $flag_number = 1;
                    }else{
                        if($get_users_last_flag->flag_number == 1){
                            $banned_from_bidding = Carbon::now()->addHours(48);
                            $banned_from_quick_call = Carbon::now()->addDays(15);
                            $loss_of_rewards = round((1/3)*$get_bidder_total_rewards_earned->rewards_earned);
                            $flag_number = 2;
                        }else if($get_users_last_flag->flag_number == 2){
                            $banned_from_bidding = Carbon::now()->addHours(72);
                            $banned_from_quick_call = Carbon::now()->addDays(21);
                            $loss_of_rewards = round((1/3)*$get_bidder_total_rewards_earned->rewards_earned);
                            $flag_number = 3;
                        }
                    }
                }else{
                    if($get_bidder_total_strikes < 3){

                        $get_users_last_strike = Strike::where('user_id', $bidder->user_id)->where('status', 1)->latest()->first(); 

                        if($get_users_last_strike == null){
                            $banned_from_bidding = Carbon::now()->addHours(96);
                            $banned_from_quick_call = Carbon::now()->addDays(30);
                            $loss_of_rewards = 0;
                            $strike_number = 1;
                        }else{
                            if($get_users_last_strike->strike_number == 1){
                                $banned_from_bidding = Carbon::now()->addWeek();
                                $banned_from_quick_call = Carbon::now()->addDays(45);
                                $loss_of_rewards = round((1/3)*$get_bidder_total_rewards_earned->rewards_earned);
                                $strike_number = 2;
    
    
                            }else if($get_users_last_strike->strike_number == 2){
                                $banned_from_bidding = Carbon::now()->addWeeks(2);
                                $banned_from_quick_call = Carbon::now()->addDays(60);
                                $loss_of_rewards = round((1/3)*$get_bidder_total_rewards_earned->rewards_earned);
                                $strike_number = 3;
    
                            }
                        }

                    }
                    
                }

                try{

                    DB::beginTransaction();

                    CaregiverBidding::where('job_id', $bidder->job_id)->update([
                        'status' => JobStatus::JobNotAccepted
                    ]);

                    if($get_bidder_total_strikes < 4 && $get_bidder_total_flags >= 3){

                        Strike::create([
                            'user_id' => $bidder->user_id,
                            'job_id' => $bidder->job_id,
                            'strike_reason' => StrikeReason::JobNotAccepted,
                            'start_date_time' => Carbon::now(),
                            'end_date_time' => $banned_from_quick_call,
                            'banned_from_bidding' => $banned_from_bidding,
                            'banned_from_quick_call' => $banned_from_quick_call,
                            'rewards_loose' => $loss_of_rewards,
                            'strike_number' => $strike_number
                        ]);

                        CaregiverNotification::create([
                            'user_id' => $bidder->user_id,
                            'content' => 'Hey there, you received a STRIKE for not accepting the job named "'.$bidder->job->title.'" on time.',
                            'type' => CaregiverNotificationType::Strike
                        ]);


                    }else if($get_bidder_total_strikes == 0 && $get_bidder_total_flags < 4 ){
                        CaregiverFlag::create([
                            'user_id' => $bidder->user_id,
                            'job_id' => $bidder->job_id,
                            'flag_reason' => FlagReason::JobNotAccepted,
                            'start_date_time' => Carbon::now(),
                            'end_date_time' => $banned_from_quick_call,
                            'banned_from_bidding' => $banned_from_bidding,
                            'banned_from_quick_call' => $banned_from_quick_call,
                            'rewards_loose' => $loss_of_rewards,
                            'flag_number' => $flag_number
                        ]);

                        CaregiverNotification::create([
                            'user_id' => $bidder->user_id,
                            'content' => 'Hey there, you received a FLAG for not accepting the job named "'.$bidder->job->title.'" on time.',
                            'type' => CaregiverNotificationType::Flag
                        ]);
                    }

                    CaregiverProfileRegistration::where('user_id', $bidder->user_id)->update([
                        'rewards_earned' => $get_bidder_total_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_bidder_total_rewards_earned->rewards_earned - $loss_of_rewards) )
                    ]);

                    Reward::create([
                        'user_id' => $bidder->user_id,
                        'job_id' => $bidder->job_id,
                        'total_rewards' => $get_bidder_total_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_bidder_total_rewards_earned->rewards_earned - $loss_of_rewards) )
                    ]);

                    DB::commit();

                }catch(\Exception $e){

                    DB::rollBack();

                    Log::error('Oops! Something went wrong in giving strike and flags for not accepting bidded job cron.');
                    Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
                    Log::info('Strike and flag cron error. Command exceuted In : ' . Carbon::now());
                    Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                }
            }
        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in giving strike and flags for not accepting bidded job cron.');
            Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
            Log::info('Strike and flag cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
