<?php

namespace App\Console\Commands;

use App\Common\JobStatus;
use App\Models\AcceptJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateBiddingList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GenerateBiddingList:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will generate bidding list for the caregivers who has bidded for a job.';

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
            $get_jobs = AcceptJob::where('status', JobStatus::BiddingEnded)->get();
       }catch(\Exception $e){
            Log::error('Oops! Something went wrong in generating bidding list cron job.');
            var_dump('Error ==>', $e->getMessage());
            Log::info('Generate bidding list cron error. Command exceuted In : '.Carbon::now() );
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
       }
    }
}
