<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_controls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('auxiliary_table_name');
            $table->integer('catalogs_id')->unsigned()->index();
            $table->foreign('catalogs_id')->references('id')->on('catalogs');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::drop('account_controls'); //
    }
}
