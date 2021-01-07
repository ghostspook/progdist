<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeFieldToBookingSupportPersons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_support_persons', function (Blueprint $table) {
            //
            $table->smallInteger('support_role')->comment('1: Coordinacion Academica 2: Soporte Academico 3: Soporte TI')->nullable();
            $table->smallInteger('support_type')->comment('1: Fisico 2: Virtual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_support_persons', function (Blueprint $table) {
            //
            $table->dropColumn('support_role');
            $table->smallInteger('support_type');

        });
    }
}
