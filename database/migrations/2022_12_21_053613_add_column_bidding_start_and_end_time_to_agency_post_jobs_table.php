<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBiddingStartAndEndTimeToAgencyPostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_post_jobs', function (Blueprint $table) {
            $table->string('bidding_start_time')->after('status')->nullable();
            $table->string('bidding_end_time')->after('bidding_start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_post_jobs', function (Blueprint $table) {
            //
        });
    }
}
