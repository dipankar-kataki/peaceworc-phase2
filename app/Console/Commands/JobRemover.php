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
                    $job_end_time = $job->end_date.''.$job->end_time;

                    $time_diff_in_minutes = $current_time->diffInMinutes($job_end_time);
                    

                    if($time_diff_in_minutes  === 0){
                        AgencyPostJob::where('id', $job->id)->update([
                            'status' => JobStatus::JobExpired,
                        ]);

                        $check_if_job_is_accepted = AcceptJob::where('job_id', $job->id)->exists();
                        if($check_if_job_is_accepted){
                            AcceptJob::where('job_id', $job->id)->update([
                                'status' => JobStatus::JobExpired,
                            ]);
                        }

                        Log::info('Database Updated');
                    }else{
                        Log::info('No Job Found To Updated');
                    }

                }

                
            }
            
        }catch(\Exception $e){
            Log::info( 'Something Went Wrong ===> ', $e->getMessage() ); 
        }
    }
}
