<?php

namespace App\Console\Commands;

use App\Common\AgencyNotificationType;
use App\Common\CaregiverNotificationType;
use App\Common\FlagReason;
use App\Common\JobStatus;
use App\Common\StrikeReason;
use App\Models\AcceptJob;
use App\Models\AgencyNotification;
use App\Models\AgencyPostJob;
use App\Models\AppDeviceToken;
use App\Models\CaregiverFlag;
use App\Models\CaregiverNotification;
use App\Models\CaregiverProfileRegistration;
use App\Models\CaregiverStatusInformation;
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
                    // $job_end_date = Carbon::parse($upcoming->job->end_date.''.$upcoming->job->end_time);

                    $get_rewards = Reward::where('user_id', $upcoming->user_id)->sum('total_rewards');
                    $get_final_rewards_earned = CaregiverProfileRegistration::where('user_id', $upcoming->user_id)->first();
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

                    
                    
                    if($get_flags > 0 && $get_flags < 3){

                        $get_users_last_flag = CaregiverFlag::where('user_id', $upcoming->user_id)->where('status', 1)->latest()->first(); 
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
                
                    if($get_strikes > 0 && $get_strikes < 3){

                        $get_users_last_strike = Strike::where('user_id', $upcoming->user_id)->where('status', 1)->latest()->first(); 
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

                    $difference_between_old_start_date_and_end_date = 0;

                    //Checking if job start date is a future date or not. Means grater than current date or not.
                    if(!$job_start_date->gt($current_time)){ 

                        $diff_between_start_date_and_current_date_in_minutes = $current_time->diffInMinutes($job_start_date);
                        // $diff_between_start_date_and_current_date_in_hour = $current_time->diffInHours($job_start_date);

                        // $difference_between_old_start_date_and_end_date = $job_end_date->diffInHours($job_start_date);

                        $get_caregiver_device_token  = AppDeviceToken::where('user_id', $upcoming->user_id)->first();

                        if($diff_between_start_date_and_current_date_in_minutes <= 30){

                            $message= 'Hey there, you have one job named "'.$upcoming->job->title.'" which is not started yet. Please start the job right now.';
                            $token = $get_caregiver_device_token->fcm_token;
                    
                            $this->sendWelcomeNotification($token, $message);

                            CaregiverNotification::create([
                                'user_id' => $upcoming->user_id,
                                'content' => 'Hey there, you have one job named "'.$upcoming->job->title.'" which is not started yet. Please start the job right now.',
                                'type' => CaregiverNotificationType::Job
                            ]);

                            Log::info('Great! Not started upcoming job notification sent.');

                            Log::info('Not started  job switcher command exceuted In : '.Carbon::now() );
                            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");

                        }else{
                            //Here we are updating the start date and time with new date and time after adding 5 hours to it.

                            // $new_start_date = $job_start_date->addHours(5);
                            // $new_start_time = $new_start_date->toTimeString();

                            if($get_strikes == 3){
                                try{
                                    CaregiverStatusInformation::where('user_id', $upcoming->user_id)->update([
                                        'is_profile_approved' => 0
                                    ]);
        
                                    Log::info('Profile of user id ===> '.$upcoming->user_id.' deactivated because of 3 strikes.');
                                }catch(\Exception $e){
                                    Log::error('Oops! Something went wrong in deactivating profile from strike and flag not accepting cron.');
                                    Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
                                    Log::info('Error. Command exceuted In : ' . Carbon::now());
                                    Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                                }
                            }else{
                                try{
                                    DB::beginTransaction(); 
    
                                    AcceptJob::where('job_id', $upcoming->job_id)->update([
                                        'status' => JobStatus::JobNotStarted
                                    ]);
        
                                    AgencyPostJob::where('id', $upcoming->job_id)->update([
                                        'status' => JobStatus::JobNotStarted,
                                        // 'start_date' => $new_start_date->toDateString(),
                                        // 'start_time' => $new_start_time
                                    ]);
    
                                    if( $get_strikes < 3 && $get_flags == 3){
    
                                        Strike::create([
                                            'user_id' => $upcoming->user_id,
                                            'job_id' => $upcoming->job_id,
                                            'strike_reason' => StrikeReason::JobNotStarted,
                                            'start_date_time' => Carbon::now(),
                                            'end_date_time' => $banned_from_quick_call,
                                            'banned_from_bidding' => $banned_from_bidding->diff(Carbon::now())->format('%D:%H:%I:%S'),,
                                            'banned_from_quick_call' => $banned_from_quick_call->diff(Carbon::now())->format('%D:%H:%I:%S'),
                                            'rewards_loose' => $loss_of_rewards,
                                            'strike_number' => $strike_number
                                        ]);
    
                                        
                                        CaregiverNotification::create([
                                            'user_id' => $upcoming->user_id,
                                            'content' => 'Hey there, you received a STRIKE for not starting the job named "'.$upcoming->job->title.'" on time.',
                                            'type' => CaregiverNotificationType::Strike
                                        ]);
    
                                    }else if($get_strikes == 0 && $get_flags < 3  ){
                                        CaregiverFlag::create([
                                            'user_id' => $upcoming->user_id,
                                            'job_id' => $upcoming->job_id,
                                            'flag_reason' => FlagReason::JobNotStarted,
                                            'start_date_time' => Carbon::now(),
                                            'end_date_time' => $banned_from_quick_call,
                                            'banned_from_bidding' => $banned_from_bidding->diff(Carbon::now())->format('%D:%H:%I:%S'),,
                                            'banned_from_quick_call' => $banned_from_quick_call->diff(Carbon::now())->format('%D:%H:%I:%S'),
                                            'rewards_loose' => $loss_of_rewards,
                                            'flag_number' => $flag_number
                                        ]);
    
                                        CaregiverNotification::create([
                                            'user_id' => $upcoming->user_id,
                                            'content' => 'Hey there, you received a FLAG for not starting the job named "'.$upcoming->job->title.'" on time.',
                                            'type' => CaregiverNotificationType::Flag
                                        ]);
    
                                    }
    
                                    CaregiverProfileRegistration::where('user_id', $upcoming->user_id)->update([
                                        'rewards_earned' => $get_final_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_final_rewards_earned->rewards_earned - $loss_of_rewards) )
                                    ]);
    
                                    Reward::create([
                                        'user_id' => $upcoming->user_id,
                                        'job_id' => $upcoming->job_id,
                                        'total_rewards' => $get_final_rewards_earned->rewards_earned == 0 ? 0 : abs(round($get_final_rewards_earned->rewards_earned - $loss_of_rewards) )
                                    ]);
    
    
    
                                    
                                    AgencyNotification::create([
                                        'user_id' => $upcoming->job->user_id,
                                        'content' => 'Hey there, your posted job named "'.$upcoming->job->title.'" has been moved to Quick Call due to a delay in starting.',
                                        'type' => AgencyNotificationType::Job
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
