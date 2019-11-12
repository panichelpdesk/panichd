<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePanichdTicketsAddHidden extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->integer('hidden')->default('0')->after('subject');
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
            $table->dropColumn('hidden');
        });
    }
}
