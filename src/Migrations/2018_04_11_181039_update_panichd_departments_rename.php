<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use PanicHD\PanicHD\Models\Department;

class UpdatePanichdDepartmentsRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ticketit_departments_persons');
		
		Schema::rename('ticketit_departments', 'panichd_departments');
		
		foreach (Department::whereNotNull('sub1')->get() as $dep){
			$dep->department = $dep->sub1;
			$dep->shortening = null;
			$dep->save();
		}
		
		Schema::table('panichd_departments', function (Blueprint $table) {
			$table->renameColumn('department', 'name');
			$table->dropColumn('sub1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('panichd_departments', 'ticketit_departments');
		
		Schema::table('ticketit_departments', function (Blueprint $table) {
            $table->renameColumn('name', 'department');
			$table->text('sub1')->nullable()->after('shortening');
        });
    }
}
