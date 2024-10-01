<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRawProductInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rawProduct_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->string('units');
            $table->decimal('price',20,2);
            $table->decimal('discount',20,2);
            $table->integer('invoice_id')->unsigned()->index();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->integer('raw_product_id')->unsigned()->index();
            $table->foreign('raw_product_id')->references('id')->on('raw_products')->onDelete('cascade');
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
        Schema::dropIfExists('rawProduct_invoices');
    }
}
