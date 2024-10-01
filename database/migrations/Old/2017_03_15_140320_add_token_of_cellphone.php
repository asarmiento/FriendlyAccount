<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTokenOfCellphone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('cellphones', function (Blueprint $table) {
            $table->integer('modelWorkshop_id')->unsigned()->index()->nullable()->after('brand_id');
            $table->string('token')->after('modelWorkshop_id');
            $table->engine = 'InnoDB';
        });//

        Schema::table('cellphones', function($table){
            $table->foreign('modelWorkshop_id')->references('id')->on('model_of_the_vehicles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cellphones', function (Blueprint $table) {
            $table->dropColumn('modelWorkshop_id');
            $table->dropColumn('token');
        });//
    }
}
