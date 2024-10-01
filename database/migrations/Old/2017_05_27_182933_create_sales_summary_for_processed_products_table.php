<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesSummaryForProcessedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_summary_for_processed_products', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->decimal('amount',20,2);
            $table->integer('processed_product_id')->unsigned()->index();
            $table->foreign('processed_product_id','processed_product_id_foreign')->references('id')->on('processed_products');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id','user_id_foreign')->references('id')->on('users');
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
        Schema::drop('sales_summary_for_processed_products'); //
    }
}
