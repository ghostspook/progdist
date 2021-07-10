<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportPersonConstraintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_person_constraints', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('from');
            $table->dateTime('to');
            $table->unsignedBigInteger('support_person_id');

            $table->foreign('support_person_id')->references('id')->on('support_persons');

            $table->smallInteger('constraint_type')->comment('0: Vacaciones 1: Enfermedad 2:Estudio')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_person_constraints');
    }
}
