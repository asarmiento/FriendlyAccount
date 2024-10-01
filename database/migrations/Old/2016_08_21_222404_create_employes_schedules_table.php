<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployesSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->time('times',30,2);
            $table->integer('employes_id')->unsigned()->index();
            $table->foreign('employes_id')->references('id')->on('employess')->onDelete('no action');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employes_schedules');
    }
}
