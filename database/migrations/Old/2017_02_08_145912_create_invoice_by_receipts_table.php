<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceByReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_by_receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount',20,2);
            $table->integer('invoice_id')->unsigned()->index();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->integer('receipt_id')->unsigned()->index();
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
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
        Schema::drop('invoice_by_receipts'); //
    }
}
