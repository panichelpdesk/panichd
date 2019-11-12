<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTicketitAdminAgentToPanichd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('ticketit_agent', 'panichd_agent');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('ticketit_admin', 'panichd_admin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('panichd_agent', 'ticketit_agent');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('panichd_admin', 'ticketit_admin');
        });
    }
}
