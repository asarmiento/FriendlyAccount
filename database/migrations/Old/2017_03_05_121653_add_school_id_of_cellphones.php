<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolIdOfCellphones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cellphones', function (Blueprint $table) {
            $table->integer('school_id')->unsigned()->index()->after('brand_id');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');

        });//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cellphones', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });//
    }
}
