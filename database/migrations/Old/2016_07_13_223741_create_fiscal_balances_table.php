<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_balances', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('balance',30,2);
            $table->date('date');
            $table->integer('schools_months_fiscal_id')->unsigned()->index();
            $table->foreign('schools_months_fiscal_id')->references('id')->on('schools_months_fiscal')->onDelete('no action');
            $table->integer('catalog_id')->unsigned()->index();
            $table->foreign('catalog_id')->references('id')->on('catalogs')->onDelete('no action');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fiscal_balances');
    }
}
