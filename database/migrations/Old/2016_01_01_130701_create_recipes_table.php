<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('units');
            $table->string('unitsIn');
            $table->decimal('amount',20,2);
            $table->integer('cooked_product_id')->unsigned()->index();
            $table->foreign('cooked_product_id')->references('id')->on('cooked_products')->onDelete('cascade');
            $table->integer('rawProduct_id')->unsigned()->index();
            $table->foreign('rawProduct_id')->references('id')->on('raw_products')->onDelete('cascade');
            $table->string('token');
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
        Schema::dropIfExists('recipes');
    }
}
