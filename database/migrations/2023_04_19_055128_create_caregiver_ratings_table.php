<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaregiverRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caregiver_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caregiver_id');
            $table->unsignedBigInteger('agency_id');
            $table->string('review')->nullable();
            $table->string('rating',5);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('caregiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caregiver_ratings');
    }
}
