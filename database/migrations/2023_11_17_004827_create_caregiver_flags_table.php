<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaregiverFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caregiver_flags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('job_id');
            $table->string('flag_reason');
            $table->string('flag_lift_reason')->nullable();
            $table->string('start_date_time');
            $table->string('end_date_time');
            $table->string('banned_from_bidding');
            $table->string('banned_from_quick_call');
            $table->integer('rewards_loose')->default(0);  
            $table->boolean('status')->default('1');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caregiver_flags');
    }
}
