<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRawProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',10)->unique();
            $table->string('description')->unique();
            $table->string('units');
            $table->enum('type',['gravado','exento']);
            $table->integer('brand_id')->unsigned()->index();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
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
        Schema::dropIfExists('raw_products');
    }
}
