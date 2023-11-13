<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\AppDeviceToken;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\JobNotCompleteNotification;
use App\Traits\WelcomeNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class JobRemover extends Command
{

    use JobNotCompleteNotification, WelcomeNotification;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobRemover:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will pull jobs from the jobs table and check if the jobs end time is over. If over it will mark the job as expired.';

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
        
            /******************************
            1. First we will get those jobs whose status is open and quick call.
            2. Second we will check if the end time is over or not. If over we will mark it as expired.
            
            */
        try{


            // $data = AgencyPostJob::where('payment_status', 1)->where('status', JobStatus::QuickCall)->get();

            // if(!$get_jobs->isEmpty()){

            //     foreach($get_jobs as $job){

            //         $current_date_time = Carbon::now();
            //         $end_date_time = Carbon::parse($job->end_date.' '.$job->end_time);

            //         if($current_date_time->gt($end_date_time) ){
            //             AgencyPostJob::where('id', $job->id)->update([
            //                 'status' => JobStatus::JobExpired
            //             ]);

            //             // echo json_encode('Job id =>'. $job->id.' removed');
            //         }
            //     }
            // }

            AgencyPostJob::where('payment_status', 1)
            ->where('status', JobStatus::QuickCall)
            ->where('end_date', '<', Carbon::now())
            ->update(['status' => JobStatus::JobExpired]);
            
            Log::info('Command Exceuted In : '.Carbon::now() );
            
            

        }catch(\Exception $e){
            Log::info('Oops! Something went wrong in auto job remover');
            var_dump('Error ==>'. $e->getMessage());
            Log::info('Command Exceuted In : '.Carbon::now() );
        }
        

        // try{
        //     $get_jobs = AgencyPostJob::where('payment_status', 1)->get();

        //     if(!$get_jobs->isEmpty() ){
                
        //         foreach($get_jobs as $job){

        //             $current_time = Carbon::now();

        //             $job_start_time = new Carbon($job->start_date.' '.$job->start_time);
        //             $job_end_time = new Carbon($job->end_date.' '.$job->end_time);

        //             $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->where('status','!=',JobStatus::JobExpired)->exists();

        //             if($check_if_job_is_accepted){

        //                 if($current_time->gt($job_end_time)){

        //                     $check_if_job_completed_or_closed =  AcceptJob::where('job_id', $job->id)->whereIn('status',[ JobStatus::Completed,JobStatus::Closed])->exists();

        //                     if(!$check_if_job_completed_or_closed){

        //                         Log::info('Oops! Job Is Not Completed Yet');

        //                         $diff_in_minutes = $job_end_time->diffInMinutes($current_time);

        //                         // if( $diff_in_minutes <= 10){
        //                             $job_accepted_by = AcceptJob::where('job_id', $job->id)->first();

        //                             $get_caregiver_details = User::where('id', $job_accepted_by->user_id)->first();
        //                             $device_token = AppDeviceToken::where('user_id', $job->user_id )->get();
    
        //                             foreach($device_token as $key => $token){
    
        //                                 $data['job_title'] = $job->title;
        //                                 $data['job_amount'] = $job->amount;
        //                                 $data['job_start_date'] = Carbon::parse($job->start_date)->format('M-d, Y');
        //                                 $data['job_start_time'] = $job->start_timr;
        //                                 $data['job_end_date'] = Carbon::parse($job->end_date)->format('M-d, Y');
        //                                 $data['job_end_time'] = $job->end_time;
        //                                 $data['job_accepted_by'] = $get_caregiver_details->name;
        //                                 $data['message'] = 'Job end time is over but not closed yet.';
        //                                 $data['minutes_passed'] = $diff_in_minutes;
        //                                 $data['notification_type'] = 'fullscreen';
        //                                 // $token = [];
        //                                 $token = $token->fcm_token;
        //                                 // $message = 'Job end time is over but not closed yet.';
        //                                 // $token = $device_token->fcm_token;
        
        //                                 $this->sendJobNotCompleteNotification($token, $data);
        
        //                                 // $this->sendWelcomeNotification($token, $message);

        //                                 Log::info('Notification Sent');
        //                             }
        //                         // }

        //                     }else{
        //                         Log::info('Job Is Completed');
        //                     }
        //                 }else{
        //                     Log::info('Current Time is Smaller');
        //                 }
                        
        //             }else{
        //                 if( !$job_start_time->gt($current_time)){
        //                     AgencyPostJob::where('id', $job->id)->update([
        //                         'status' => JobStatus::JobExpired,
        //                     ]);
    
        //                     $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->exists();

        //                     if($check_if_job_is_accepted){
        //                         AcceptJob::where('job_id', $job->id)->update([
        //                             'status' => JobStatus::JobExpired,
        //                         ]);
        //                     }
    
        //                     Log::info('Database Updated. Some of the jobs are removed.');
        //                 }
        //             }
        //         }
        //     }
            
        // }catch(\Exception $e){

        //     $details = [
        //         'Error' => $e->getMessage(),
        //         'Line' => $e->getLine()

        //     ];

        //     Log::error("Oops! Something went wrong in Job Remover.");
        //     var_dump('Error ===>', $details);
        //     Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        // }
    }
}
