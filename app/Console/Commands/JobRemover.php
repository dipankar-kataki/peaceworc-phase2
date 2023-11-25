<?php

namespace App\Console\Commands;

use App\Common\AgencyNotificationType;
use App\Common\JobStatus;
use App\Models\AgencyNotification;
use App\Models\AgencyPostJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        
            /******************************
            1. First we will get those jobs whose status is open and quick call.
            2. Second we will check if the end time is over or not. If over we will mark it as expired.
            
            */
        try{


            $removeExpiredQuickCallJob = AgencyPostJob::where('payment_status', 1)->where('status', JobStatus::QuickCall)->get();
            if(!$removeExpiredQuickCallJob->isEmpty()){
                $current_time = Carbon::now();

                foreach($removeExpiredQuickCallJob as $job){
                   $end_date_time = Carbon::parse($job->end_date.''.$job->end_time);
                   if(!$end_date_time->gt($current_time)){

                        try{
                            DB::beginTransaction();

                            AgencyPostJob::where('id', $job->id)->update([
                                'status' => JobStatus::JobExpired
                            ]);

                            AgencyNotification::create([
                                'user_id' => $job->user_id,
                                'content' => 'Hey There, the Job named "'.$job->title.'" has expired.',
                                'type' => AgencyNotificationType::Job,
                            ]);

                            DB::commit();
    
                            Log::info('Job -->'. $job->id.' Updated as Expired');
                            Log::info('Job Remover Command Exceuted In ===> : '.Carbon::now() );

                        }
                        catch(\Exception $e){
                            DB::rollBack();

                            Log::info('Oops! Something went wrong in auto job remover');
                            var_dump('Error ==>'. $e->getMessage());
                            Log::info('Job Remover Error Command Exceuted In : '.Carbon::now() );
                        }
                        
                   }

                }
            }

        }catch(\Exception $e){
            Log::info('Oops! Something went wrong in auto job remover');
            var_dump('Error ==>'. $e->getMessage());
            Log::info('Job Remover Error Command Exceuted In : '.Carbon::now() );
        }
        
    }
}
