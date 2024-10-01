<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyByClosingCashDesksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('currency_by_closing_cash_desks', function(Blueprint $table)
        {
            $table->increments('id');
            $table->decimal('amount',15,4);
            $table->integer('closing_cash_desk_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->timestamps();
            //relaciones
            $table->foreign('closing_cash_desk_id')->references('id')->on('closing_cash_desks');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('currency_by_closing_cash_desks');
    }
}
