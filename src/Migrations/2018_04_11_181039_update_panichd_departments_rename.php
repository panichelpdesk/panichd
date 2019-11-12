<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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

        if (Schema::hasTable('ticketit_departments')) {
            Schema::rename('ticketit_departments', 'panichd_departments');

            foreach (Department::whereNotNull('sub1')->get() as $dep) {
                $dep->department = $dep->sub1;
                $dep->shortening = null;
                $dep->save();
            }
        }

        if (Schema::hasColumn('panichd_departments', 'department')) {
            Schema::table('panichd_departments', function (Blueprint $table) {
                $table->renameColumn('department', 'name');
            });
        }
        Schema::table('panichd_departments', function (Blueprint $table) {
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
        if (Schema::hasTable('panichd_departments')) {
            Schema::rename('panichd_departments', 'ticketit_departments');
        }

        Schema::table('ticketit_departments', function (Blueprint $table) {
            $table->renameColumn('name', 'department');
            $table->text('sub1')->nullable()->after('shortening');
        });
    }
}
