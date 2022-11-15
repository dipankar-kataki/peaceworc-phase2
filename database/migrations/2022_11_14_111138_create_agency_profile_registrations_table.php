<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgencyProfileRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agency_profile_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('phone',15);
            $table->string('legal_structure',50);
            $table->string('organization_type',50);
            $table->string('tax_id_or_ein_id',10);
            $table->string('street',50);
            $table->string('city_or_district',50);
            $table->string('state',50);
            $table->string('zip_code',10);
            $table->integer('number_of_employee')->nullable();
            $table->integer('years_in_business')->nullable();
            $table->string('country_of_business')->nullable();
            $table->string('annual_business_revenue')->nullable();
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
        Schema::dropIfExists('agency_profile_registrations');
    }
}
