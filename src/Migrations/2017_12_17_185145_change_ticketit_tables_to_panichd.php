<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use PanicHD\PanicHD\Models\Ticket;

class ChangeTicketitTablesToPanichd extends Migration
{
    public $a_tables;

    public function __construct()
    {
        $this->a_tables = [
            'attachments',
            'audits',
            'categories',
            'categories_users',
            'closingreasons',
            'comments',
            'departments',
            'departments_persons',
            'priorities',
            'settings',
            'statuses',
            'taggables',
            'tags',
        ];
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('ticketit', 'panichd_tickets');
        foreach ($this->a_tables as $table) {
            Schema::rename('ticketit_'.$table, 'panichd_'.$table);
        }

        Ticket::whereNotNull('id')->update(['start_date'=>DB::raw('created_at')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('panichd_tickets', 'ticketit');
        foreach ($this->a_tables as $table) {
            Schema::rename('panichd_'.$table, 'ticketit_'.$table);
        }
    }
}
