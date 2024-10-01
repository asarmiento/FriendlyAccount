<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSalonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_salons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('token')->unique();
            $table->boolean('barra')->default('0');
            $table->integer('school_id')->unsigned()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('no action');
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
        Schema::drop('table_salons');
    }
}
