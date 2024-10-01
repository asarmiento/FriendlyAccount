<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolsMonthsFiscal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schools_months_fiscal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year',4);
            $table->enum('month_first',[1,2,3,4,5,6,7,8,9,10,11,12]);
            $table->enum('month_end',[1,2,3,4,5,6,7,8,9,10,11,12]);
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('no action');
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
        Schema::drop('schools_months_fiscal');
    }
}
