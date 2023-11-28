<?php

namespace App\Console\Commands;

use App\Common\CaregiverNotificationType;
use App\Common\JobStatus;
use App\Models\AcceptJob;
use App\Models\AgencyPostJob;
use App\Models\CaregiverBidding;
use App\Models\CaregiverBiddingResultList;
use App\Models\CaregiverCertificate;
use App\Models\CaregiverFlag;
use App\Models\CaregiverNotification;
use App\Models\CaregiverProfileRegistration;
use App\Models\Reward;
use App\Models\Strike;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenerateBiddingList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generateBiddingList:cron';

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
            $get_all_biddings = CaregiverBidding::with('job')->where('status', JobStatus::BiddingEnded)->where('is_bid_ranked', 0)->where('is_job_awarded', 0)->get();
            
            $taskCounter = 0;
            $ongoing_job_id = 0;
            $bidding_end_time = 0;
            $bidder_user_id = 0;
            $bidded_job_title = null;

            foreach($get_all_biddings as $bids){

                $get_bidder_rewards = CaregiverProfileRegistration::where('user_id', $bids->user_id)->first('rewards_earned');
                $get_bidder_flags_count = CaregiverFlag::where('user_id', $bids->user_id)->count();
                $get_bidder_strikes_count = Strike::where('user_id', $bids->user_id)->count();
                $get_bidder_certificates_count = CaregiverCertificate::where('user_id', $bids->user_id)->count();

                $is_list_generation_started_for_this_job = CaregiverBiddingResultList::where('job_id', $bids->job_id)->exists();
                $win_position = 1;

                if($is_list_generation_started_for_this_job){

                    $get_last_bidder = CaregiverBiddingResultList::where('job_id', $bids->job_id)->latest()->first();
                    

                    if($get_last_bidder->user_id != $bids->user_id ){

                        $get_last_bidder_rewards = CaregiverProfileRegistration::where('user_id', $get_last_bidder->user_id)->first('rewards_earned');
                        $get_last_bidder_flags_count = CaregiverFlag::where('user_id', $get_last_bidder->user_id)->count();
                        $get_last_bidder_strikes_count = Strike::where('user_id', $get_last_bidder->user_id)->count();
                        $get_last_bidder_certificates_count = CaregiverCertificate::where('user_id', $get_last_bidder->user_id)->count();
                        
                        $get_last_bidder_win_position = $get_last_bidder->caregiver_bid_win_position;
                        $get_last_bidder_time_for_notification = $get_last_bidder->time_for_notification;

                        


                        // if($get_bidder_rewards->rewards_earned > $get_last_bidder_rewards->rewards_earned){
                        //     $win_position = $get_last_bidder_win_position;
                        // }else if($get_bidder_flags_count > $get_last_bidder_flags_count){
                        //     $win_position = $get_last_bidder_win_position;
                        // }else if($get_bidder_strikes_count > $get_last_bidder_strikes_count){
                        //     $win_position = $get_last_bidder_win_position;
                        // }else if($get_bidder_certificates_count > $get_last_bidder_certificates_count){
                        //     $win_position = $get_last_bidder_win_position;
                        // }else{
                        //     $win_position = $get_last_bidder_win_position + 1;
                        // }

                        // Log::info('Last bidder ==> '.$get_last_bidder->user_id.' Win Position for user Current Bidder ==> '.$get_last_bidder_win_position);

                        // Log::info('Current Bidder ==> '.$bids->user_id.' Win Position ==> '.$win_position);


                        try {

                            DB::beginTransaction();

                            CaregiverBiddingResultList::create([
                                'job_id' => $bids->job_id,
                                'user_id' => $bids->user_id,
                                'caregiver_bid_win_position' =>  $get_last_bidder_win_position + 1,
                                'time_for_notification' => Carbon::parse($get_last_bidder_time_for_notification)->addMinute()
                            ]);

                            // CaregiverBiddingResultList::where('job_id', $get_last_bidder->job_id)->where('user_id', $get_last_bidder->user_id, )->update([
                            //     'caregiver_bid_win_position' => $win_position
                            // ]);

                            CaregiverBidding::where('job_id', $bids->job_id)->where('user_id', $bids->user_id)->where('status', JobStatus::BiddingEnded)->update([
                                'is_bid_ranked' => 1
                            ]);

                            DB::commit();

                        } catch (\Exception $e) {
                            DB::rollBack();

                            Log::error( 'Oops! Something went wrong in generating bidding list cron job.');
                            Log::error( 'Error ==> '.$e->getMessage().'. On line number ==>'.$e->getLine() );
                            Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                        }
                    }

                }else{
                    try{
                        DB::beginTransaction();

                        CaregiverBiddingResultList::create([
                            'job_id' => $bids->job_id,
                            'user_id' => $bids->user_id,
                            'caregiver_bid_win_position' => $win_position,
                            'time_for_notification' => Carbon::now()->addMinutes(5)
                        ]);

                        CaregiverBidding::where('job_id', $bids->job_id)->where('user_id', $bids->user_id)->update([
                            'is_bid_ranked' => 1
                        ]);

                        DB::commit();

                    }catch(\Exception $e){
                        DB::rollBack();

                        Log::error('Oops! Something went wrong in generating bidding list cron job.');
                        Log::error('Error ==>'.$e->getMessage().' On line number ==>'.$e->getLine());
                        Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                    }
                    
                }

                $ongoing_job_id = $bids->job_id;

                $taskCounter++;

                $bidding_end_time = $bids->job->bidding_end_time;
                $bidder_user_id = $bids->user_id;
                $bidded_job_title = $bids->job->title;

            }

            // if($taskCounter === count($get_all_biddings)){

            //     try{
            //         DB::beginTransaction();

            //         CaregiverBiddingResultList::where('job_id', $ongoing_job_id)->update([
            //             'is_list_generation_complete' => 1
            //         ]);

            //         CaregiverNotification::create([
            //             'user_id' => $bidder_user_id,
            //             'content' => 'Hey there, the bidding list has been generated for the job "'.$bidded_job_title.'". Please wait for the results to be declared.',
            //             'type' => CaregiverNotificationType::Job,
                        
            //         ]);

            //         DB::commit();

            //     }catch(\Exception $e){
            //         DB::rollBack();

            //         Log::error('Oops! Something went wrong in generating bidding list cron job.');
            //         Log::error('Error ==>'.$e->getMessage().' On line number ==>'.$e->getLine());
            //         Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
            //         Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
            //     }

            // }
        }catch(\Exception $e){
            Log::info('Oops! Something went wrong while generating bidding list.');
            Log::error('Error ==> '.$e->getMessage().' '.'Error occured on line no. ===> '.$e->getLine() );
            Log::info('This cron job executed on ==> '. Carbon::now() );
            Log::info('XXXXXXXXXXXXXXXXXXXxx ------------------------ xxXXXXXXXXXXXXXXXXXXXX');
        }
    }
}
