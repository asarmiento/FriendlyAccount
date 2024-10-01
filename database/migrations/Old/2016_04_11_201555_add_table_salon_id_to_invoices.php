<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableSalonIdToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('invoices', function($table)
        {
            $table->integer('table_salon_id')->unsigned()->index();
            $table->foreign('table_salon_id')->references('id')->on('table_salons')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function($table)
        {
            //
            $table->dropForeign('invoices_table_salon_id_foreign');
            $table->dropColumn('table_salon_id');
        });
    }
}