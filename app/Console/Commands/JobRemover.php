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


            $updateJob = AgencyPostJob::where('payment_status', 1)
            ->where('status', JobStatus::QuickCall)
            ->where('end_date', '<', Carbon::now())
            ->update(['status' => JobStatus::JobExpired]);

            if($updateJob){
                Log::info('Job Updated as Expired');
                Log::info('Job Remover Command Exceuted In ===> : '.Carbon::now() );
            }

        }catch(\Exception $e){
            Log::info('Oops! Something went wrong in auto job remover');
            var_dump('Error ==>'. $e->getMessage());
            Log::info('Job Remover Error Command Exceuted In : '.Carbon::now() );
        }
        
    }
}
