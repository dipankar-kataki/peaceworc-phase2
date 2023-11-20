<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRewardsEarnedToCaregiverProfileRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_profile_registrations', function (Blueprint $table) {
            $table->integer('rewards_earned')->default(0)->after('certificate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caregiver_profile_registrations', function (Blueprint $table) {
            //
        });
    }
}
