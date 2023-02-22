<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agency_id');
            $table->unsignedBigInteger('job_id');
            $table->decimal('amount',10,2);
            $table->string('customer_id');
            $table->string('payment_mode')->nullable();
            $table->decimal('caregiver_charge', 10,2);
            $table->integer('peaceworc_percentage');
            $table->decimal('peaceworc_charge', 10,2);
            $table->boolean('payment_status');
            $table->timestamps();


            $table->foreign('agency_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('agency_post_jobs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agency_payments');
    }
}
