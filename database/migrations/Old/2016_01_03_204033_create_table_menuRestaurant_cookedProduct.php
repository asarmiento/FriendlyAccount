<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMenuRestaurantCookedProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menuRestaurant_cookedProduct', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->enum('type',['Base','Adicional']);
            $table->integer('cooked_product_id')->unsigned()->index();
            $table->foreign('cooked_product_id')->references('id')->on('cooked_products')->onDelete('cascade');
            $table->integer('menu_restaurant_id')->unsigned()->index();
            $table->foreign('menu_restaurant_id')->references('id')->on('menu_restaurants')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('menuRestaurant_cookedProduct');
    }
}
