<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePanichdPrioritiesAddMagnitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('panichd_priorities', function (Blueprint $table) {
            $table->integer('magnitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('panichd_priorities', function (Blueprint $table) {
            $table->dropColumn('magnitude');
        });
    }
}
