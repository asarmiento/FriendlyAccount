<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleOfTheFirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_of_the_firms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number',50);
            $table->decimal('amount',20,2);
            $table->date('date');
            $table->boolean('status');
            $table->string('token');
            $table->integer('customer_id')->unsigned()->nullable()->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('sale_of_the_firms');
    }
}
