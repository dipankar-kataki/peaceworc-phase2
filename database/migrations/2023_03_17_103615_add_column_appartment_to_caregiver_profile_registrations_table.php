<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAppartmentToCaregiverProfileRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('caregiver_profile_registrations', function (Blueprint $table) {
            $table->string('appartment_or_unit')->after('street')->nullable();
            $table->string('floor_no')->after('appartment_or_unit')->nullable();
            $table->string('country')->after('zip_code')->nullable();
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
