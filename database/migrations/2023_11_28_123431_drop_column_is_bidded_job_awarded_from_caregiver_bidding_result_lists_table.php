<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnIsBiddedJobAwardedFromCaregiverBiddingResultListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_bidding_result_lists', function (Blueprint $table) {
            $table->dropColumn('is_bidded_job_awarded');
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
