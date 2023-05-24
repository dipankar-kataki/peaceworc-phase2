<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShortAddressToClientProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            $table->string('short_address')->after('address');
            $table->string('street')->after('short_address');
            $table->string('appartment_or_unit')->after('street');
            $table->string('floor_no')->after('appartment_or_unit');
            $table->string('city')->after('floor_no');
            $table->string('state')->after('city');
            $table->string('zip_code')->after('state');
            $table->string('country')->after('zip_code');
            $table->string('lat')->after('country');
            $table->string('long')->after('lat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_profiles', function (Blueprint $table) {
            //
        });
    }
}
