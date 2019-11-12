<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class UpdateRenameTicketitSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('ticketit_settings')) {
            Schema::rename('ticketit_settings', 'panichd_settings');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('panichd_settings')) {
            Schema::rename('panichd_settings', 'ticketit_settings');
        }
    }
}
