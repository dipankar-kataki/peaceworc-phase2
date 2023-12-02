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
            $get_bidders = CaregiverBidding::with('job')->where('status', JobStatus::BiddingEnded)->where('is_bid_ranked', 0)->orderByDesc('rewards_earned')->orderBy('created_at')->get();
            
            $taskCounter = 0;
            $ongoing_job_id = 0;
            $bidder_user_id = 0;
            $bidded_job_title = null;

            if(!$get_bidders->isEmpty()){
                
                foreach($get_bidders as $bidder){
                    $is_bided_job_exists = CaregiverBiddingResultList::where('job_id', $bidder->job_id)->exists();
                    if(!$is_bided_job_exists){
                        try{
    
                            DB::beginTransaction();
    
                            CaregiverBiddingResultList::create([
                                'job_id' => $bidder->job_id,
                                'user_id' => $bidder->user_id,
                                'time_for_notification' => Carbon::now()->addMinutes(5)
                            ]);
    
                            CaregiverBidding::where('user_id', $bidder->user_id)->where('job_id', $bidder->job_id)->update([
                                'is_bid_ranked' => 1,
                            ]);
    
                            DB::commit();
    
                        }catch(\Exception $e){
                            DB::rollBack();
    
                            Log::error('Oops! Something went wrong in generating bidding list transaction.');
                            Log::error('Error ==> '.$e->getMessage().' '.'Error occured on line no. ===> '.$e->getLine() );
                            Log::info('This cron job executed on ==> '. Carbon::now() );
                            Log::info('XXXXXXXXXXXXXXXXXXXxx ------------------------ xxXXXXXXXXXXXXXXXXXXXX');
                        }
                    }else{
                        $is_bidder_already_ranked = CaregiverBiddingResultList::where('user_id', $bidder->user_id)->where('job_id', $bidder->job_id)->exists();
                        if(!$is_bidder_already_ranked){
                            $get_previous_time_for_notification = CaregiverBiddingResultList::where('job_id', $bidder->job_id)->orderBy('id', 'DESC')->first();
                            
    
                            try{
    
                                $time_for_notification = Carbon::parse($get_previous_time_for_notification->time_for_notification)->addMinute();
    
                                DB::beginTransaction();
        
                                CaregiverBiddingResultList::create([
                                    'job_id' => $bidder->job_id,
                                    'user_id' => $bidder->user_id,
                                    'time_for_notification' => $time_for_notification
                                ]);
        
                                CaregiverBidding::where('user_id', $bidder->user_id)->where('job_id', $bidder->job_id)->update([
                                    'is_bid_ranked' => 1,
                                ]);
        
                                DB::commit();     
        
                            }catch(\Exception $e){
                                DB::rollBack();
        
                                Log::error('Oops! Something went wrong in generating bidding list transaction.');
                                Log::error('Error ==> '.$e->getMessage().' '.'Error occured on line no. ===> '.$e->getLine() );
                                Log::info('This cron job executed on ==> '. Carbon::now() );
                                Log::info('XXXXXXXXXXXXXXXXXXXxx ------------------------ xxXXXXXXXXXXXXXXXXXXXX');
        
                            }
                        }
                    }
                    
    
                    $ongoing_job_id = $bidder->job_id;
    
                    $taskCounter++;
    
                    $bidder_user_id = $bidder->user_id;
                    $bidded_job_title = $bidder->job->title;
                }
    
                if($taskCounter === count($get_bidders)){
    
                    try{
                        DB::beginTransaction();
    
                        CaregiverBiddingResultList::where('job_id', $ongoing_job_id)->update([
                            'is_list_generation_complete' => 1
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
                        Log::error('Error ==>'.$e->getMessage().' On line number ==>'.$e->getLine());
                        Log::info('Generate bidding list cron error. Command exceuted In : ' . Carbon::now());
                        Log::info("-------------------- xxxxxxxxxxxxxxxxxxxxx --------------------");
                    }
    
                }
            }

            
        }catch(\Exception $e){
            Log::info('Oops! Something went wrong while generating bidding list.');
            Log::error('Error ==> '.$e->getMessage().' '.'Error occured on line no. ===> '.$e->getLine() );
            Log::info('This cron job executed on ==> '. Carbon::now() );
            Log::info('XXXXXXXXXXXXXXXXXXXxx ------------------------ xxXXXXXXXXXXXXXXXXXXXX');
        }
        
    }
}
