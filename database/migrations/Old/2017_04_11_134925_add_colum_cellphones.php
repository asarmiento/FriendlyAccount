<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumCellphones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cellphones', function (Blueprint $table) {
            $table->string('authorized')->after('date_of_delivery');
            $table->string('authorizedSign')->after('authorized');
            $table->string('equipment')->after('authorizedSign');
            $table->string('color')->after('equipment');
            $table->string('otherType')->after('color');
            $table->string('charger')->after('otherType');
            $table->string('chargerSeries')->after('charger');
            $table->string('case')->after('chargerSeries');
            $table->string('physicalSigns')->after('case');
            $table->string('additionalRequests')->after('physicalSigns');
            $table->string('reportedProblems')->after('additionalRequests');
            $table->string('firm')->after('reportedProblems');
            $table->integer('numeration')->after('firm');
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
            $table->dropColumn('authorized');
            $table->dropColumn('authorizedSign');
            $table->dropColumn('equipment');
            $table->dropColumn('color');
            $table->dropColumn('otherType');
            $table->dropColumn('charger');
            $table->dropColumn('chargerSeries');
            $table->dropColumn('case');
            $table->dropColumn('physicalSigns');
            $table->dropColumn('additionalRequests');
            $table->dropColumn('reportedProblems');
            $table->dropColumn('firm');
            $table->dropColumn('numeration');
        });//
    }
}
