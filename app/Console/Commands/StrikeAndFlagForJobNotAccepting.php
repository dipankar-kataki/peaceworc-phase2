<?php

namespace App\Console\Commands;

use App\Models\CaregiverBiddingResultList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StrikeAndFlagForJobNotAccepting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'strikeAndFlagForJobNotAccepting:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will give strike or flag to those caregivers who has not accepted the job after bidding.';

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

            $get_bidders = CaregiverBiddingResultList::where('is_list_generation_complete', 1)->where('is_job_rejected', 1)->where('is_notification_sent', 1)->get();
            

        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in giving strike and flags for not accepting bidded job cron.');
            Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
            Log::info('Strike and flag cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
