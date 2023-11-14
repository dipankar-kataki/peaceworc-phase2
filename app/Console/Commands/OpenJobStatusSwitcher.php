<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Models\AgencyPostJob;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OpenJobStatusSwitcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openJobStatusSwitcher:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This comman will switch the job status from open to quick call automatically depending upon the job start time.';

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

            $jobs = AgencyPostJob::where('payment_status', 1)->where('status', JobStatus::Open)->get();

            if(!$jobs->isEmpty()){
                foreach($jobs as $item){
    
                    $requested_start_date_time_for_the_job = Carbon::parse($item->start_date.''.$item->start_time);
    
                    $current_time = Carbon::now();
    
                    $to = Carbon::createFromFormat('Y-m-d H:s:i', $current_time);
                    $from_start_date = Carbon::createFromFormat('Y-m-d H:s:i', $requested_start_date_time_for_the_job);
    
                    $diff_in_hours = $to->diffInHours($from_start_date);
    
                    if( $diff_in_hours <= 5){
    
                        AgencyPostJob::where('id', $item->id)->update([
                            'status' => JobStatus::QuickCall
                        ]);

                        Log::info('Great! Job updated as Quick Call.');

                        Log::info('Open Job Switcher Command Exceuted In : '.Carbon::now() );
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
    
                    }
                }
            }
            
        }catch(\Exception $e){
            Log::error("Oops! Something went wrong in auto open job switcher.");
            var_dump('Error ==>', $e->getMessage());
            Log::info('Open Job Switcher Error Command Exceuted In : '.Carbon::now() );
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");         
        }
    }
}
