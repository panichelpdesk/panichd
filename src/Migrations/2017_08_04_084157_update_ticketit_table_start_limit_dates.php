<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kordy\Ticketit\Models\Ticket;

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
            $table->timestamp('start_date')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('limit_date')->nullable();
        });
		
		Ticket::whereNotNull('id')->update(['start_date'=>DB::raw('created_at')]);
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
