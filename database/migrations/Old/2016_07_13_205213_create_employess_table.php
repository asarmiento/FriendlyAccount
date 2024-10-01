<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employess', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fname',60);
            $table->string('sname',60);
            $table->string('flast',60);
            $table->string('slast',60);
            $table->string('charter',30);
            $table->string('email',80);
            $table->string('phone',18);
            $table->string('token')->unique();
            $table->integer('school_id')->unsigned()->nullable()->index();
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('employess');
    }
}
