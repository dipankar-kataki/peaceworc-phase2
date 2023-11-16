<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStrikeStartTimeAndEndTimeToStrikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('strikes', function (Blueprint $table) {
            $table->string('start_date_time')->after('strike_lift_reason');
            $table->string('end_date_time')->after('start_date_time');
            $table->time('banned_from_bidding')->after('end_date_time');
            $table->time('banned_from_quick_call')->after('banned_from_bidding');
            $table->integer('rewards_loose')->after('banned_from_quick_call');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('strikes', function (Blueprint $table) {
            //
        });
    }
}
