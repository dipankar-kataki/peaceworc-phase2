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
            $table->string('photo')->nullable();
            $table->string('company_name');
            $table->string('email')->unique()->nullable();
            $table->string('phone',15)->nullable();
            $table->string('legal_structure',50)->nullable();
            $table->string('organization_type',50)->nullable();
            $table->string('tax_id_or_ein_id',10)->nullable();
            $table->string('street',50)->nullable();
            $table->string('city_or_district',50)->nullable();
            $table->string('state',50)->nullable();
            $table->string('zip_code',10)->nullable();
            $table->string('number_of_employee',100)->nullable();
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
