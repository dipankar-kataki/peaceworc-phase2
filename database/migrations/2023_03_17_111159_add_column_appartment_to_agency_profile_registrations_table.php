<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAppartmentToAgencyProfileRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_profile_registrations', function (Blueprint $table) {
            $table->string('appartment_or_unit')->after('street')->nullable();
            $table->string('floor_no')->after('appartment_or_unit')->nullable();
            $table->string('country')->after('zip_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_profile_registrations', function (Blueprint $table) {
            //
        });
    }
}
