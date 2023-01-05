<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAboutCompanyToAgencyProfileRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agency_profile_registrations', function (Blueprint $table) {
            $table->string('about_company')->nullable()->after('company_name');
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
