<?php

namespace App\Console\Commands;

use App\Common\FlagReason;
use App\Common\JobStatus;
use App\Common\StrikeReason;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\AppDeviceToken;
use App\Models\CaregiverFlag;
use App\Models\Reward;
use App\Models\Strike;
use App\Traits\WelcomeNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotStartedUpcomingJobStatusSwitcher extends Command
{
    use WelcomeNotification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notStartedUpcomingJobStatusSwitcher:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This comand will change the status of the not started upcoming job from accepted to quick call';

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
            $get_upcoming_jobs = AcceptJob::with('job')->where('status', JobStatus::JobAccepted)->get();
            $current_time = Carbon::now();

            if(!$get_upcoming_jobs->isEmpty()){

                foreach($get_upcoming_jobs as $upcoming){

                    $job_start_date = Carbon::parse($upcoming->job->start_date.''.$upcoming->job->start_time);
                    $get_rewards = Reward::where('user_id', $upcoming->user_id)->sum('total_rewards');
                    $my_rewards = 0;
                    if($get_rewards != null){
                        $my_rewards = $get_rewards;
                    }

                    $get_flags = CaregiverFlag::where('user_id', $upcoming->user_id)->count();
                    $get_strikes = Strike::where('user_id', $upcoming->user_id)->count();
                    $banned_from_bidding = null;
                    $banned_from_quick_call = null;
                    $loss_of_rewards = 0;
                    $flag_number = 0;
                    $strike_number = 0;

                    
                    
                        if($get_flags > 0){

                            $get_users_last_flag = CaregiverFlag::where('user_id', $upcoming->user_id)->where('status', 1)->last(); 
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
                    
                        if($get_strikes > 0){

                            $get_users_last_strike = Strike::where('user_id', $upcoming->user_id)->where('status', 1)->last(); 
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
                    
                    

                    $diff_between_start_date_and_current_date_in_minutes = 0;
                    $diff_between_start_date_and_current_date_in_hour = 0;

                    //Checking if job start date is a future date or not. Means grater than current date or not.
                    if(!$job_start_date->gt($current_time)){ 

                        $diff_between_start_date_and_current_date_in_minutes = $current_time->diffInMinutes($job_start_date);
                        $diff_between_start_date_and_current_date_in_hour = $current_time->diffInHours($job_start_date);

                        $get_caregiver_device_token  = AppDeviceToken::where('user_id', $upcoming->user_id)->first();

                        if($diff_between_start_date_and_current_date_in_minutes <= 30){

                            $message= "Hey there you have one job which is not started yet. Please start your job.";
                            $token = $get_caregiver_device_token->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $message);

                            Log::info('Great! Not started upcoming job notification sent.');

                            Log::info('Not started  job switcher command exceuted In : '.Carbon::now() );
                            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");

                        }else{
                            //Here we are updating the start date and time with new date and time after adding 5 hours to it.

                            $new_start_date = $job_start_date->addHours(5);
                            $new_start_time = $new_start_date->toTimeString();

                            try{
                                DB::beginTransaction(); 

                                AcceptJob::where('job_id', $upcoming->job_id)->update([
                                    'status' => JobStatus::QuickCall
                                ]);
    
                                AgencyPostJob::where('id', $upcoming->job_id)->update([
                                    'status' => JobStatus::QuickCall,
                                    'start_date' => $new_start_date->toDateString(),
                                    'start_time' => $new_start_time
                                ]);

                                if($get_strikes != 0 && $get_flags == 0){
                                    Strike::create([
                                        'user_id' => $upcoming->user_id,
                                        'job_id' => $upcoming->job_id,
                                        'strike_reason' => StrikeReason::JobNotStarted,
                                        'start_date_time' => Carbon::now(),
                                        'end_date_time' => $banned_from_quick_call,
                                        'banned_from_bidding' => $banned_from_bidding,
                                        'banned_from_quick_call' => $banned_from_quick_call,
                                        'rewards_loose' => $loss_of_rewards,
                                        'strike_number' => $strike_number
                                    ]);
                                }else if($get_strikes == 0 && $get_flags != 0){
                                    CaregiverFlag::create([
                                        'user_id' => $upcoming->user_id,
                                        'job_id' => $upcoming->job_id,
                                        'flag_reason' => FlagReason::JobNotStarted,
                                        'start_date_time' => Carbon::now(),
                                        'end_date_time' => $banned_from_quick_call,
                                        'banned_from_bidding' => $banned_from_bidding,
                                        'banned_from_quick_call' => $banned_from_quick_call,
                                        'rewards_loose' => $loss_of_rewards,
                                        'flag_number' => $flag_number
                                    ]);
                                }else if($get_strikes == 0 && $get_flags == 0){
                                    CaregiverFlag::create([
                                        'user_id' => $upcoming->user_id,
                                        'job_id' => $upcoming->job_id,
                                        'flag_reason' => FlagReason::JobNotStarted,
                                        'start_date_time' => Carbon::now(),
                                        'end_date_time' => $banned_from_quick_call,
                                        'banned_from_bidding' => $banned_from_bidding,
                                        'banned_from_quick_call' => $banned_from_quick_call,
                                        'rewards_loose' => $loss_of_rewards,
                                        'flag_number' => $flag_number
                                    ]);
                                }

                                Reward::create([
                                    'user_id' => $upcoming->user_id,
                                    'job_id' => $upcoming->job_id,
                                    'total_rewards' => 0
                                ]);

                                



                                DB::commit();
    
                                Log::info('Great! Not started upcoming job status changed to quick call and a new start date-time added.');
    
                                Log::info('Not started upcoming job switcher command exceuted In : '.Carbon::now() );
                                Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                            }catch(\Exception $e){
                                DB::rollBack();

                                Log::error("Oops! Something went wrong in not started upcoming job switcher.");
                                var_dump('Error ==>', $e->getMessage());
                                Log::info('Not started upcoming job switcher error command exceuted In : '.Carbon::now() );
                                Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                            }
                            
                        }
    
                        // echo 'Diff in minutes --->'. $diff_between_start_date_and_current_date_in_minutes.'---'.'Diff in Hours --->'. $diff_between_start_date_and_current_date_in_hour."\n";
                    }
                }
            }
        }catch(\Exception $e){
            Log::error("Oops! Something went wrong in not started upcoming job switcher.");
            var_dump('Error ==>', $e->getMessage());
            Log::info('Not started upcoming job switcher error command exceuted In : '.Carbon::now() );
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
