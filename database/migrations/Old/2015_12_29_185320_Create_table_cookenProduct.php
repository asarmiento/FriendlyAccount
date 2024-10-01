<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCookenProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooked_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->decimal('price',20,2);
            $table->integer('numberOfDishes');
            $table->enum('type',['comida','bebida']);
            $table->enum('money',['colones','dolares']);
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
        Schema::dropIfExists('cooked_products');
    }
}
