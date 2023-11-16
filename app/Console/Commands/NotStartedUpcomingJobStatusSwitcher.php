<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Common\StrikeReason;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\AppDeviceToken;
use App\Models\CaregiverFlag;
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

                    $get_flags = CaregiverFlag::where('user_id', $upcoming->user_id)->where('status', 1)->count();

                    

                    if($get_flags > 0){

                        $get_users_last_flag = CaregiverFlag::where('user_id', $upcoming->user_id)->where('status', 1)->last(); 
                        // $banned

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

                                Strike::create([
                                    'user_id' => $upcoming->user_id,
                                    'job_id' => $upcoming->job_id,
                                    'strike_reason' => StrikeReason::JobNotStarted,
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
