<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommissionAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('commission',20,2);
            $table->date('date_of_inscription');
            $table->string('token');
            $table->integer('school_id')->unsigned()->nullable()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('commission_agents');
    }
}
