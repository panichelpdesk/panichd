<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTicketitTableCreatorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit', function (Blueprint $table) {
            $table->integer('creator_id')->after('priority_id')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketit', function (Blueprint $table) {
            $table->dropColumn('creator_id');
        });
    }
}
