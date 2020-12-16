<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeaultVirtualMeeitingLinkField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->unsignedBigInteger('default_virtual_meeting_link_id');

            $table->foreign('default_virtual_meeting_link_id')->references('id')->on('virtual_meeting_links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropForeign(['default_virtual_meeting_link_id']);
            $table->dropColumn('default_virtual_meeting_link_id');
        });
    }
}
