<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('charter');
            $table->string('code',10)->unique();
            $table->string('name')->unique();
            $table->string('contact');
            $table->string('phone');
            $table->string('phoneContact');
            $table->text('address');
            $table->decimal('amount',40,2);
            $table->decimal('balance',40,2);
            $table->string('email')->unique();
            $table->string('token');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
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
        Schema::dropIfExists('suppliers');
    }
}
