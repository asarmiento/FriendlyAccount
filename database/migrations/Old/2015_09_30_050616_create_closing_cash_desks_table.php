<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosingCashDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_cash_desks', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('success');
            $table->float('taxed_sales',15,4);
            $table->float('exempt_sales',15,4);
            $table->float('tax_sales',15,4);
            $table->float('discount',15,4);
            $table->float('service_sales',15,4);
            $table->float('credit_sales',15,4);
            $table->float('cash_sales',15,4);
            $table->float('amount_annulled_sales',15,4);
            $table->integer('total_sales');
            $table->float('surplus');
            $table->float('missing');
            $table->integer('annulled_sales');
            $table->integer('total_receipts');
            $table->float('amount_receipts');
            $table->float('invoices_paid',15,4);
            $table->integer('cash_desk_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('validate_user_id')->unsigned();
            $table->timestamps();
            //relaciones
            $table->foreign('cash_desk_id')->references('id')->on('cash_desks');
            $table->foreign('user_id')->references('id')->on('cash_desks');
            $table->foreign('validate_user_id')->references('id')->on('cash_desks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('closing_cash_desks');
    }
}
