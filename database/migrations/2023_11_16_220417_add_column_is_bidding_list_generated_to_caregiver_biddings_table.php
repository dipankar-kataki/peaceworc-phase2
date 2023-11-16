<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsBiddingListGeneratedToCaregiverBiddingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_biddings', function (Blueprint $table) {
            $table->boolean('is_bidding_list_generated')->default(0)->after('status');
            $table->boolean('is_job_awarded')->default(0)->after('is_bidding_list_generated');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caregiver_biddings', function (Blueprint $table) {
            //
        });
    }
}
