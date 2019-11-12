<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTicketitAttachmentsAddImageSizes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit_attachments', function (Blueprint $table) {
            $table->string('image_sizes')->after('mimetype')->nullable();
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
            $table->dropColumn('image_sizes');
        });
    }
}
