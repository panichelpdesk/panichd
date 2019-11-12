<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTicketitAttachmentsTableDescription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit_attachments', function (Blueprint $table) {
            $table->string('new_filename')->nullable()->after('original_filename');
            $table->longText('description')->nullable()->after('new_filename');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketit_attachments', function (Blueprint $table) {
            $table->dropColumn('new_filename');
            $table->dropColumn('description');
        });
    }
}
