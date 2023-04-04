<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_post_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title',100);
            $table->string('care_type',50);
            $table->longText('care_items');
            $table->string('start_date',50);
            $table->string('start_time',50);
            $table->string('end_time',50);
            $table->string('amount',50);
            $table->longText('address');
            $table->longText('description');
            $table->longText('medical_history')->nullable();
            $table->longText('expertise')->nullable();
            $table->longText('other_requirements')->nullable();
            $table->longText('check_list')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_post_jobs');
    }
}
