<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('deposits', function(Blueprint $table) {
            $table->increments('id');
            $table->string('number', 150);
            $table->date('date');
            $table->string('auxiliaryReceipt');
            $table->decimal('amount',20,2)->unsigned();
            $table->integer('catalog_id')->unsigned();;
            $table->foreign('catalog_id')->references('id')->on('catalogs')->onDelete('no action');
            $table->integer('paymentsForm_id')->index()->unsigned();
            $table->foreign('paymentsForm_id')->references('id')->on('payment_froms')->onDelete('no action');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('no action');
            $table->string('token')->unique();
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
    public function down() {
        Schema::dropIfExists('deposits');
    }

}
