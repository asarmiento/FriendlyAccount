<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCamposOfCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
            $table->dropColumn('name');
            $table->string('fname',80)->after('id');
            $table->string('sname',80)->after('fname');
            $table->string('flast',80)->after('sname');
            $table->string('slast',80)->after('flast');
            $table->string('phone',20)->after('slast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->dropColumn('fname');
            $table->dropColumn('sname');
            $table->dropColumn('flast');
            $table->dropColumn('slast');
            $table->dropColumn('phone');
        });
    }
}
