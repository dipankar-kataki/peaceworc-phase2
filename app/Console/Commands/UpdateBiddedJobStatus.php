<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBiddedJobStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateBiddedJobStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will update bidded job status from bidding started to bidding ended.';

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
            $get_bidded_jobs = AgencyPostJob::where('status', JobStatus::BiddingStarted)->get();
            foreach( $get_bidded_jobs as $job){

                if(!Carbon::parse($job->bidding_end_time)->gt(Carbon::now())){
                    try{
                        DB::beginTransaction();

                        AgencyPostJob::where('id', $job->id)->update([
                            'status' => JobStatus::BiddingEnded
                        ]);

                        CaregiverBidding::where('job_id', $job->id)->update([
                            'status' => JobStatus::BiddingEnded
                        ]);

                        DB::commit();

                    }catch(\Exception $e){
                        DB::rollBack();
                        
                        Log::error('Oops! Something went wrong in updating bidding job status');
                        var_dump('Error ==>', $e->getMessage());
                        Log::info('This command executed on ===>'. Carbon::now());
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                    }
                    
                }

            }

        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in updating bidding status cron job.');
            var_dump('Error ==>', $e->getMessage());
            Log::info('Updating bidding status cron error. Command exceuted on : '.Carbon::now() );
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
