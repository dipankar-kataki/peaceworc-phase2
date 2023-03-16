<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStreetNameToAgencyPostJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_post_jobs', function (Blueprint $table) {
            $table->string('street')->after('short_address');
            $table->string('appartment_or_unit')->after('street');
            $table->string('floor_no')->after('appartment_or_unit')->nullable();
            $table->string('city')->after('floor_no');
            $table->string('state')->after('city');
            $table->string('zip_code')->after('state');
            $table->string('country')->after('zipcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agency_post_jobs', function (Blueprint $table) {
            //
        });
    }
}
