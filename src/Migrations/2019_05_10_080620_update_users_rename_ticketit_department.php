<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersRenameTicketitDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('ticketit_department', 'panichd_notice_group_id');
        });
		
		Schema::table('users', function (Blueprint $table) {
			$table->integer('panichd_group_id')->nullable()->after('panichd_admin');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('panichd_notice_group_id', 'ticketit_department');
        });
		
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('panichd_group_id');
		});
    }
}
