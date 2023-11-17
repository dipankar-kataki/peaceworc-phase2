<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsJobAcceptedByThisCaregiverToCaregiverBiddingResultListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_bidding_result_lists', function (Blueprint $table) {
            $table->boolean('is_list_generation_complete')->default(0)->after('is_bidded_job_awarded');
            $table->boolean('is_job_accepted')->default(0)->after('is_list_generation_complete');
            $table->boolean('is_job_rejected')->default(0)->after('is_job_accepted');
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
