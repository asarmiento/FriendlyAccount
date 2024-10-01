<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifyOrderSalonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modify_order_salons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_salon_id')->unsigned()->index();
            $table->foreign('order_salon_id')->references('id')->on('order_salons')->onDelete('no action');
            $table->integer('cooked_product_id')->unsigned()->index();
            $table->foreign('cooked_product_id')->references('id')->on('cooked_products')->onDelete('no action');
            $table->enum('type',['Base','Adicional']);
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
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
        Schema::drop('modify_order_salons');
    }
}
