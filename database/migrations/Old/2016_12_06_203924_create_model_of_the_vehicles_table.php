<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelOfTheVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_of_the_vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80);
            $table->boolean('status');
            $table->string('token');
            $table->integer('brand_id')->unsigned()->index();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('model_of_the_vehicles');
    }
}
