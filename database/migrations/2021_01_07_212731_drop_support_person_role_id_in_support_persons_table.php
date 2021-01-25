<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSupportPersonRoleIdInSupportPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_persons', function (Blueprint $table) {
            $table->dropForeign(['support_person_role_id']);
            $table->dropColumn('support_person_role_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_persons', function (Blueprint $table) {

            $table->foreignId('support_person_role_id')->constrained();
        });
    }
}
