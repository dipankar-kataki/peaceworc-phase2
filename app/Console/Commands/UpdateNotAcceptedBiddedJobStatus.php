<?php

namespace App\Console\Commands;

use App\Common\AgencyNotificationType;
use App\Common\JobStatus;
use App\Models\AcceptJob;
use App\Models\AgencyNotification;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBiddingResultList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateNotAcceptedBiddedJobStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateNotAcceptedBiddedJobStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will re-post the job only when bidding has ended and list is generated for auto job accept but no one has accepted the job.';

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
            $get_bid_ended_jobs = AgencyPostJob::where('status', JobStatus::BiddingEnded)->get();
            if(!$get_bid_ended_jobs->isEmpty()){
                foreach($get_bid_ended_jobs as $job){
                    $is_bid_list_generated = CaregiverBiddingResultList::where('job_id', $job->id)->where('is_list_generation_complete', 1)->where('is_notification_sent', 1)->exists();
                    if($is_bid_list_generated){
                        $is_job_accepted = AcceptJob::where('job_id', $job->id)->where('status', JobStatus::JobAccepted)->exists();
                        if(!$is_job_accepted){
                            $get_job_start_date_time = Carbon::parse($job->start_date.''.$job->start_time);
                            $current_time = Carbon::now();

                            $time_diff_in_hours = $get_job_start_date_time->diffInHours($current_time);

                            if($time_diff_in_hours > 12){
                                try{
                                    AgencyPostJob::where('id', $job->id)->update([
                                        'status' => JobStatus::Open,
                                        'job_type' => JobStatus::Open,
                                        'bidding_start_time' => null,
                                        'bidding_end_time' => null
                                    ]);
    
                                    AgencyNotification::create([
                                        'user_id' => $job->user_id,
                                        'content' => 'Hey there, your earlier posted job named '.$job->title.' has been reposted as open job since no one has accepted the job after bidding.',
                                        'type' => AgencyNotificationType::Job
                                    ]);

                                }catch(\Exception $e){
                                    Log::info('Oops! Something went wrong in updating not accepted bidding ended jobs transaction');
                                    Log::error('Error ==> '.$e->getMessage().'. On line number ==> '.$e->getLine() );
                                    Log::info('xxxxxxxxxxxxxxxxx----------------------------xxxxxxxxxxxxxxxxxx');
                                }
                                
                            }else if( $time_diff_in_hours <= 5){
                                try{
                                    AgencyPostJob::where('id', $job->id)->update([
                                        'status' => JobStatus::QuickCall,
                                        'job_type' => JobStatus::QuickCall,
                                        'bidding_start_time' => null,
                                        'bidding_end_time' => null
                                    ]);
    
                                    AgencyNotification::create([
                                        'user_id' => $job->user_id,
                                        'content' => 'Hey there, your earlier posted job named '.$job->title.' has been reposted as quick call job since no one has accepted the job after bidding.',
                                        'type' => AgencyNotificationType::Job
                                    ]);

                                }catch(\Exception $e){
                                    Log::info('Oops! Something went wrong in updating not accepted bidding ended jobs transaction');
                                    Log::error('Error ==> '.$e->getMessage().'. On line number ==> '.$e->getLine() );
                                    Log::info('xxxxxxxxxxxxxxxxx----------------------------xxxxxxxxxxxxxxxxxx');
                                }
                            }
                        }
                    }
                    
                }
            }
        }catch(\Exception $e){
            Log::info('Oops! Something went wrong in updating not accepted bidding ended jobs');
            Log::error('Error ==> '.$e->getMessage().'. On line number ==> '.$e->getLine() );
            Log::info('xxxxxxxxxxxxxxxxx----------------------------xxxxxxxxxxxxxxxxxx');
        }
    }
}
