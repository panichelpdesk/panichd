<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketitTableStartLimitDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable();
			$table->timestamp('limit_date')->nullable();
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
            $table->dropColumn('start_date');
            $table->dropColumn('limit_date');
        });
    }
}
