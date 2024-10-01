<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableConvertions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convertions', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amountIn',20,15);
            $table->enum('unitsIn',['Kg','gr','Oz','l','ml','Lb','cup','tbs','tsp','gl']);
            $table->decimal('amountOut',20,15);
            $table->enum('unitsOut',['Kg','gr','Oz','l','ml','Lb','cup','tbs','tsp','gl']);
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
        Schema::dropIfExists('convertions');
    }
}
