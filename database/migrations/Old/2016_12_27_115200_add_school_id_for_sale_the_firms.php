<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSchoolIdForSaleTheFirms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            $table->integer('school_id')->unsigned()->index()->after('invoice_id');
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
        Schema::table('sale_of_the_firms', function (Blueprint $table) {
            $table->dropColumn('school_id');
        });
    }
}
