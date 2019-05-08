<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenamePanichdDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('panichd_departments')){
			Schema::rename('panichd_departments', 'panichd_groups');
		}
		Schema::table('panichd_groups', function (Blueprint $table) {
            $table->string('full_name')->after('name')->nullable();
		});
		
		Schema::table('panichd_groups', function (Blueprint $table) {
			$table->renameColumn('department_id', 'group_id');
        });
		
		Schema::table('panichd_groups', function (Blueprint $table) {
			$table->dropColumn('shortening');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('panichd_groups')){
			Schema::rename('panichd_groups', 'panichd_departments');
		}
		
		Schema::table('panichd_departments', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
		
		Schema::table('panichd_departments', function (Blueprint $table) {
			$table->renameColumn('group_id', 'department_id');
        });
		
		Schema::table('panichd_departments', function (Blueprint $table) {
			$table->string('shortening')->nullable();
        });
    }
}
