<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable->constrained();
            $table->foreignId('instructor_id')->nullable->constrained();
            $table->foreignId('virtual_meeting_link_id')->nullable->constrained();
            $table->foreignId('physical_room_id')->nullable->constrained();
            $table->string('topic');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');


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
        Schema::dropIfExists('bookings');
    }
}
