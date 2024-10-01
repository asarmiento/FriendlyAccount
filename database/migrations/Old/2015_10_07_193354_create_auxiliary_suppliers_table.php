<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuxiliarySuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auxiliary_suppliers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code', 35);
            $table->string('detail', 150);
            $table->decimal('amount', 30,2);
            $table->date('date');
            $table->date('dateBuy');
            $table->date('dateExpiration')->nullable();
            $table->string('bill');
            $table->string('token');
            $table->enum('type',['contado','credito']);
            $table->enum('status',['aplicado','no aplicado']);
            $table->integer('supplier_id')->unsigned()->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('no action');
            $table->integer('accounting_period_id')->unsigned()->index();
            $table->foreign('accounting_period_id')->references('id')->on('accounting_periods')->onDelete('no action');
            $table->integer('type_id')->unsigned()->index();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('no action');
            $table->integer('type_seat_id')->unsigned()->index();
            $table->foreign('type_seat_id')->references('id')->on('type_seats')->onDelete('no action');
            $table->integer('user_created')->unsigned()->nullable()->index();
            $table->foreign('user_created')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_updated')->unsigned()->nullable()->index();
            $table->foreign('user_updated')->references('id')->on('users')->onDelete('cascade');
            $table->engine = 'InnoDB';
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auxiliary_suppliers');
    }
}
