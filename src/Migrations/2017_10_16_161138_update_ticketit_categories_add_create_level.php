<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTicketitCategoriesAddCreateLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticketit_categories', function (Blueprint $table) {
            $table->integer('create_level')->default('1');
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
            $table->dropColumn('create_level');
        });
    }
}
