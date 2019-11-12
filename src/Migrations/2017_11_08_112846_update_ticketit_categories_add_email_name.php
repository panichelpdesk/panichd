<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTicketitCategoriesAddEmailName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit_categories', function (Blueprint $table) {
            $table->string('email_name')->nullable()->after('name');
            $table->boolean('email_replies')->default(0)->after('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticketit_categories', function (Blueprint $table) {
            $table->dropColumn('email_name');
            $table->dropColumn('email_replies');
        });
    }
}
