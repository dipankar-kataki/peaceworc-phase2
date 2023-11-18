<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTimeForNotificationToCaregiverBiddingResultListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_bidding_result_lists', function (Blueprint $table) {
            $table->timestamp('time_for_notification')->after('is_job_rejected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caregiver_bidding_result_lists', function (Blueprint $table) {
            //
        });
    }
}
