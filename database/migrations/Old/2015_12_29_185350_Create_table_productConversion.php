<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductConversion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_conversion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('amount');
            $table->enum('units',['kg','g','cup','l','ml','tbs','cup','tps','lb']);
            $table->decimal('discount',20,2);
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
        Schema::dropIfExists('product_conversion');
    }
}
