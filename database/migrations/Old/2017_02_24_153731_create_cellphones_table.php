<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCellphonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cellphones', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('cost',20,2);
            $table->string('description');
            $table->enum('priority',['alta','medio','baja']);
            $table->string('serie');
            $table->string('elaborate_work');
            $table->string('damage');
            $table->string('password');
            $table->date('deu_date');
            $table->date('date_of_receipt');
            $table->date('warranty_date');
            $table->date('date_of_delivery');
            $table->integer('brand_id')->unsigned()->index();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('customer_id')->unsigned()->index();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
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
        Schema::drop('cellphones'); //
    }
}
