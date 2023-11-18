<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsNotificationSentToCaregiverBiddingResultListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_bidding_result_lists', function (Blueprint $table) {
            $table->boolean('is_notification_sent')->default(0)->after('time_for_notification');
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
