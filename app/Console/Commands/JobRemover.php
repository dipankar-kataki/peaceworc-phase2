<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class JobRemover extends Command
{
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
        try{
            $get_jobs = AgencyPostJob::where('payment_status', 1)->get();

            if(!$get_jobs->isEmpty() ){
                
                foreach($get_jobs as $job){

                    $current_time = Carbon::now();

                    $job_start_time = new Carbon($job->start_date.' '.$job->start_time);
                    $job_end_time = new Carbon($job->end_date.' '.$job->end_time);

                    $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->where('status','!=',JobStatus::JobExpired)->exists();

                    if($check_if_job_is_accepted){

                        if($job_end_time->gt($current_time) == false){

                            AgencyPostJob::where('id', $job->id)->update([
                                'status' => JobStatus::JobExpired,
                            ]);
    
                            $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->exists();
                            if($check_if_job_is_accepted){
                                AcceptJob::where('job_id', $job->id)->update([
                                    'status' => JobStatus::JobExpired,
                                ]);
                            }
    
                            Log::info('Database Updated. Some of the jobs are expired.');
                        }
                        
                    }else{
                        if( $job_start_time->gt($current_time) == false){
                            AgencyPostJob::where('id', $job->id)->update([
                                'status' => JobStatus::JobCancelled,
                            ]);
    
                            $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->exists();
                            if($check_if_job_is_accepted){
                                AcceptJob::where('job_id', $job->id)->update([
                                    'status' => JobStatus::JobCancelled,
                                ]);
                            }
    
                            Log::info('Database Updated. Some of the jobs are removed.');
                        }
                    }

                    
                }
            }
            
        }catch(\Exception $e){
            Log::info( 'Something Went Wrong ===> ', $e->getMessage() ); 
        }
    }
}
