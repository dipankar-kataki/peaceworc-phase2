<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AwardBiddedJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'awardBiddedJob:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will award the job to those caregivers who has bidded for the job by sending push notification.';

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
        // try{

        // }catch(){

        // }
    }
}
