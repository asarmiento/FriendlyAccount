<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('charter', 35);
            $table->string('name', 150);
            $table->decimal('amount', 30,2);
            $table->date('date');
            $table->string('token');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
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
        Schema::dropIfExists('customers');
    }
}
