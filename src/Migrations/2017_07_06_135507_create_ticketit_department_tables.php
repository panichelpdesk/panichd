<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketitDepartmentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticketit_departments_persons', function (Blueprint $table) {
            $table->integer('department_id')->unsigned();
            $table->integer('person_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('ticketit_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department');
            $table->string('shortening');
            $table->string('sub1')->nullable();
            $table->integer('department_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketit_departments_persons');

        Schema::dropIfExists('ticketit_departments');
    }
}
