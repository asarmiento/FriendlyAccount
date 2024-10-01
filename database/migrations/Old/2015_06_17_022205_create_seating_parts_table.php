<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatingPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
	{
	 Schema::create('seating_parts', function(Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('detail',150);
            $table->date('date');
            $table->decimal('amount',30,2);
            $table->enum('status',['Aplicado','No Aplicado']);
            $table->integer('seating_id')->unsigned()->index();
            $table->foreign('seating_id')->references('id')->on('seatings')->onDelete('no action');
            $table->integer('catalog_id')->unsigned()->index();
            $table->foreign('catalog_id')->references('id')->on('catalogs')->onDelete('no action');
            $table->integer('accounting_period_id')->unsigned()->index();
            $table->foreign('accounting_period_id')->references('id')->on('accounting_periods')->onDelete('no action');
            $table->integer('type_id')->unsigned()->index();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('no action');
            $table->integer('type_seat_id')->unsigned()->index();
            $table->foreign('type_seat_id')->references('id')->on('type_seats')->onDelete('no action');
            $table->integer('note_id')->unsigned()->nullable()->index();
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('no action');
            $table->integer('user_created')->unsigned()->nullable()->index();
            $table->foreign('user_created')->references('id')->on('users')->onDelete('cascade');
            $table->integer('user_updated')->unsigned()->nullable()->index();
            $table->foreign('user_updated')->references('id')->on('users')->onDelete('cascade');
            $table->string('token');
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
		Schema::dropIfExists('seating_parts');
	}
}
