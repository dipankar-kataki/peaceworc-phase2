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
            $table->unsignedBigInteger('sent_id');
            $table->unsignedBigInteger('received_id');
            $table->text('message')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('sent_id');
            $table->index('received_id');

            // Foreign key constraints
            $table->foreign('sent_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('received_id')->references('id')->on('users')->onDelete('cascade');
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
