<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsMessageSentAndIsMessageSeenAndStatusToChatSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chat_systems', function (Blueprint $table) {
            $table->boolean('is_message_sent')->after('image_path')->default(0);
            $table->boolean('is_message_seen')->after('is_message_sent')->default(0);
            $table->boolean('status')->after('is_message_seen')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chat_systems', function (Blueprint $table) {
            //
        });
    }
}
