<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSalonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_salons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->integer('menu_restaurant_id')->unsigned()->index();
            $table->foreign('menu_restaurant_id')->references('id')->on('menu_restaurants')->onDelete('no action');
            $table->integer('table_salon_id')->unsigned()->index();
            $table->foreign('table_salon_id')->references('id')->on('table_salons')->onDelete('no action');
            $table->integer('qty');
            $table->date('date');
            $table->boolean('modify')->default('0');
            $table->boolean('canceled')->default('0');
            $table->text('description');
            $table->boolean('cooked')->default('0');
            $table->string('token');
            $table->enum('status',['no aplicado', 'aplicado'])->default('no aplicado');
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
        Schema::drop('order_salons');
    }
}
