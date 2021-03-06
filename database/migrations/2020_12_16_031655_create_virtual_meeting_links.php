<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualMeetingLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_meeting_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_room_id')->constrained();
            $table->string('topic')->nullable();

            $table->string('password')->nullable();
            $table->boolean('waiting_room')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('virtual_meeting_links');
    }
}
