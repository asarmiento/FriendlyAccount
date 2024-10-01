<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCookedProductInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooked_product_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cooked_product_id')->unsigned()->index();
            $table->foreign('cooked_product_id')->references('id')->on('cooked_products')->onDelete('no action');
            $table->integer('menu_restaurant_id')->unsigned()->index();
            $table->foreign('menu_restaurant_id')->references('id')->on('menu_restaurants')->onDelete('no action');
            $table->integer('invoice_id')->unsigned()->index();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('no action');
            $table->integer('amount');
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
        Schema::drop('cooked_product_invoices');
    }
}
