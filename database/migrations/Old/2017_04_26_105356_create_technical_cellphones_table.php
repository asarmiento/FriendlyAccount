<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicalCellphonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::Create('technical_cellphones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('diagnosis');
            $table->string('work_done');
            $table->string('answer_used');
            $table->string('recommendations');
            $table->string('delivered');
            $table->decimal('final_cost',20,2);
            $table->integer('cellphone_id')->unsigned()->index();
            $table->string('token');
            $table->foreign('cellphone_id')->references('id')->on('cellphones')->onDelete('cascade');
            $table->engine = 'InnoDB';
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
        Schema::drop('technical_cellphones');//
    }
}
