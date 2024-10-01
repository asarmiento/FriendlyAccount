<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolIdOfCashDesks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_desks', function (Blueprint $table) {
            $table->integer('school_id')->after('name');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_desks', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });//
    }
}
