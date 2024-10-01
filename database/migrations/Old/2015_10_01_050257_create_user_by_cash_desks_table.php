<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserByCashDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_by_cash_desks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('cash_desk_id')->unsigned();
            $table->timestamps();
            //relaciones
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cash_desk_id')->references('id')->on('cash_desks')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('user_by_cash_desks');
    }
}
