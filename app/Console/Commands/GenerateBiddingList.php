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
        try {
            /*
                Here I am checking if the ( total rewards, strikes count, flags count, certificate count, created at time ) of the bidder 
                is greater than last bidder. if greater then create a record of the bidder by giving the win position of the last bidder
                and simultaneously updating the win position of the last bidder by +1. 
                if no bidders found inside the bidding list table than simply give the current bidder 1st win position.

                Note: Please understand the scenerio first and carefully review the code before performing any modifications. 

            */

            $get_bidding_ended_jobs = CaregiverBidding::with('job')->where('status', JobStatus::BiddingEnded)->get();

            if (!$get_bidding_ended_jobs->isEmpty()) {

                $taskCounter = 0;
                $ongoing_job_id = 0;
                $bidding_end_time = 0;
                $bidder_user_id = 0;
                $bidded_job_title = null;

                foreach ($get_bidding_ended_jobs as $bid_ended_job) {
                    $get_total_rewards_of_the_bidder = CaregiverProfileRegistration::where('user_id', $bid_ended_job->user_id)->first();
                    $get_total_strikes_of_the_bidder = Strike::where('user_id', $bid_ended_job->user_id)->count();
                    $get_total_flags_of_the_bidder = CaregiverFlag::where('user_id', $bid_ended_job->user_id)->count();
                    $check_if_bidder_have_any_certificate = CaregiverCertificate::where('user_id', $bid_ended_job->user_id)->count();
                    $get_bid_placing_time_of_the_bidder = CaregiverBidding::where('job_id', $bid_ended_job->job_id)->where('user_id', $bid_ended_job->user_id)->first();

                    $get_caregiver_position_from_bidding_result_list = CaregiverBiddingResultList::where('job_id', $bid_ended_job->job_id)->get();

                    if (!$get_caregiver_position_from_bidding_result_list->isEmpty()) {

                        $get_last_bidder = CaregiverBiddingResultList::where('job_id', $bid_ended_job->job_id)->latest()->first();

                        $get_total_rewards_of_the_last_bidder = CaregiverProfileRegistration::where('user_id', $get_last_bidder->user_id)->first();
                        $get_total_strikes_of_the_last_bidder = Strike::where('user_id', $get_last_bidder->user_id)->count();
                        $get_total_flags_of_the_last_bidder = CaregiverFlag::where('user_id', $get_last_bidder->user_id)->count();
                        $check_if_the_last_bidder_have_any_certificate = CaregiverCertificate::where('user_id', $get_last_bidder->user_id)->count();
                        $get_bid_placing_time_of_the_last_bidder = CaregiverBidding::where('job_id', $bid_ended_job->job_id)->where('user_id', $get_last_bidder->user_id)->first();
                        if($bid_ended_job->user_id != $get_last_bidder->user_id){
                            if (($get_total_rewards_of_the_bidder->rewards_earned > $get_total_rewards_of_the_last_bidder->rewards_earned) && ($get_total_strikes_of_the_bidder < $get_total_strikes_of_the_last_bidder) && ($get_total_flags_of_the_bidder < $get_total_flags_of_the_last_bidder) && ($check_if_bidder_have_any_certificate > $check_if_the_last_bidder_have_any_certificate) && (Carbon::parse($get_bid_placing_time_of_the_bidder->created_at)->gt( Carbon::parse($get_bid_placing_time_of_the_last_bidder->created_at) ) )) {
                                try {
                                    DB::beginTransaction();
    
                                    CaregiverBiddingResultList::create([
                                        'job_id' => $bid_ended_job->job_id,
                                        'user_id' => $bid_ended_job->user_id,
                                        'caregiver_bid_win_position' => $get_last_bidder->caregiver_bid_win_position,
                                        'time_for_notification' => Carbon::parse($get_last_bidder->time_for_notification)->addSeconds(30)
                                    ]);
    
                                    CaregiverBiddingResultList::where('job_id', $bid_ended_job->job_id)->where('user_id', $get_last_bidder->user_id, )->update([
                                        'caregiver_bid_win_position' => $get_last_bidder->caregiver_bid_win_position + 1
                                    ]);
    
                                    CaregiverBidding::where('job_id', $bid_ended_job->job_id)->where('user_id', $bid_ended_job->user_id)->where('status', JobStatus::BiddingEnded)->update([
                                        'is_bidding_list_generated' => 1
                                    ]);
    
                                    DB::commit();
    
                                } catch (\Exception $e) {
                                    DB::rollBack();
    
                                    Log::error('Oops! Something went wrong in generating bidding list cron job.');
                                    var_dump('Error ==>', $e->getMessage());
                                    Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                                    Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                                }
                            } else {
                                try {
                                    DB::beginTransaction();
    
                                    CaregiverBiddingResultList::create([
                                        'job_id' => $bid_ended_job->job_id,
                                        'user_id' => $bid_ended_job->user_id,
                                        'caregiver_bid_win_position' => $get_last_bidder->caregiver_bid_win_position + 1,
                                        'time_for_notification' => Carbon::parse($get_last_bidder->time_for_notification)->addSeconds(30)
                                    ]);
    
                                    CaregiverBidding::where('job_id', $bid_ended_job->job_id)->where('user_id', $bid_ended_job->user_id)->where('status', JobStatus::BiddingEnded)->update([
                                        'is_bidding_list_generated' => 1
                                    ]);
    
                                    DB::commit();
    
                                } catch (\Exception $e) {
                                    DB::rollBack();
    
                                    Log::error('Oops! Something went wrong in generating bidding list cron job.');
                                    var_dump('Error ==>', $e->getMessage());
                                    Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                                    Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                                }
                            }
                        }
                        

                    } else {

                        try {
                            DB::beginTransaction();

                            CaregiverBiddingResultList::create([
                                'job_id' => $bid_ended_job->job_id,
                                'user_id' => $bid_ended_job->user_id,
                                'caregiver_bid_win_position' => 1,
                                'time_for_notification' => Carbon::now()
                            ]);

                            CaregiverBidding::where('job_id', $bid_ended_job->job_id)->where('user_id', $bid_ended_job->user_id)->where('status', JobStatus::BiddingEnded)->update([
                                'is_bidding_list_generated' => 1
                            ]);

                            DB::commit();

                        } catch (\Exception $e) {
                            DB::rollBack();

                            Log::error('Oops! Something went wrong in generating bidding list cron job.');
                            var_dump('Error ==>', $e->getMessage());
                            Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                        }

                    }

                    $ongoing_job_id = $bid_ended_job->job_id;

                    $taskCounter++;

                    $bidding_end_time = $bid_ended_job->job->bidding_end_time;
                    $bidder_user_id = $bid_ended_job->user_id;
                    $bidded_job_title = $bid_ended_job->job->title;

                }

                // if(!Carbon::parse($bidding_end_time)->gt(Carbon::now())){
                    if($taskCounter === count($get_bidding_ended_jobs)){
                        $check_if_job_id_exist = CaregiverBiddingResultList::where('job_id', $ongoing_job_id)->exists();
    
                        if($check_if_job_id_exist){
                            try{
                                DB::beginTransaction();
    
                                CaregiverBiddingResultList::where('job_id', $ongoing_job_id)->update([
                                    'is_list_generation_complete' => 1
                                ]);
    
                                CaregiverBidding::where('job_id', $ongoing_job_id)->update([
                                    'is_bidding_list_generated' => 1
                                ]);

                                CaregiverNotification::create([
                                    'user_id' => $bidder_user_id,
                                    'content' => 'Hey there, the bidding list has been generated for the job "'.$bidded_job_title.'". Please wait for the results to be declared.',
                                    'type' => CaregiverNotificationType::Job,
                                    
                                ]);
    
                                DB::commit();
    
                            }catch(\Exception $e){
                                DB::rollBack();
    
                                Log::error('Oops! Something went wrong in generating bidding list cron job.');
                                var_dump('Error ==>', $e->getMessage());
                                Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                                Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                            }
                        }
                    }
                // }
                
            }




        } catch (\Exception $e) {
            Log::error('Oops! Something went wrong in generating bidding list cron job.');
            var_dump('Error ==>', $e->getMessage());
            Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
            Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
        }
    }
}
