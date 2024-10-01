<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceIdOfSaleOfTheFirm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned()->nullable()->index()->after('customer_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
    }
}
