<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->date('due_date');
            $table->string('numeration');
            $table->decimal('subtotal',20,2);
            $table->decimal('subtotal_taxed',20,2);
            $table->decimal('subtotal_exempt',20,2);
            $table->decimal('discount',20,2);
            $table->decimal('percent_discount',20,2);
            $table->decimal('tax',20,2);
            $table->decimal('service',20,2);
            $table->decimal('total',20,2);
            $table->decimal('paid',20,2);
            $table->decimal('changing',20,2);
            $table->decimal('surplus',20,2);           
            $table->decimal('missing',20,2);
            $table->integer('invoices_type_id')->unsigned()->index();
            $table->foreign('invoices_type_id')->references('id')->on('invoices_types')->onDelete('cascade');
            $table->integer('payment_method_id')->unsigned()->index();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->integer('waiter')->unsigned()->index()->nullable();
            $table->foreign('waiter')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('token');
            $table->boolean('cash');
            $table->string('client');
            $table->boolean('court')->default('0');
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
        Schema::dropIfExists('invoices');
    }
}
