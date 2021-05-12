<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTicketableColumnsToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->string('ticketable_type')->nullable();
            $table->string('ticketable_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->dropColumn(['ticketable_type', 'ticketable_id']);
        });
    }
}
