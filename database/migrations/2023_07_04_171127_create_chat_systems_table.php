<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_systems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sent_by');
            $table->unsignedBigInteger('received_by');
            $table->text('message')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('sent_by');
            $table->index('received_by');

            // Foreign key constraints
            $table->foreign('sent_by')->references('id')->on('users');
            $table->foreign('received_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_systems');
    }
}
