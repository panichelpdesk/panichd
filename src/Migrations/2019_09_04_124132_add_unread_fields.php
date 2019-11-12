<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUnreadFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_tickets', function (Blueprint $table) {
            $table->integer('read_by_agent')->after('agent_id')->default('1');
        });

        Schema::table('panichd_comments', function (Blueprint $table) {
            $table->integer('read_by_agent')->after('ticket_id')->default('1');
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
            $table->dropColumn('read_by_agent');
        });

        Schema::table('panichd_comments', function (Blueprint $table) {
            $table->dropColumn('read_by_agent');
        });
    }
}
