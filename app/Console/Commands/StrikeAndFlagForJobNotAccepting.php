<?php

namespace App\Console\Commands;

use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverFlag;
use App\Models\CaregiverProfileRegistration;
use App\Models\Strike;
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

            $get_bidders = CaregiverBiddingResultList::with('job')->where('is_list_generation_complete', 1)->where('is_job_rejected', 1)->where('is_notification_sent', 1)->get();
            foreach($get_bidders as $bidder){
                
                $get_bidder_total_rewards = CaregiverProfileRegistration::where('user_id', $bidder->user_id)->first('rewards_earned');
                $get_bidder_total_strikes = Strike::where('user_id', $bidder->user_id)->count();
                $get_bidder_total_flags = CaregiverFlag::where('user_id', $bidder->user_id)->count();

                Log::info('Bidder Rewards ==> '.$get_bidder_total_rewards);
                Log::info('Bidder Strike ===> '. $get_bidder_total_strikes);
                Log::info('Bidder Flags ==> '. $get_bidder_total_flags);

            }




        }catch(\Exception $e){
            Log::error('Oops! Something went wrong in giving strike and flags for not accepting bidded job cron.');
            Log::error( 'Error message ==> '.$e->getMessage().' on line number ==> '.$e->getLine() );
            Log::info('Strike and flag cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
