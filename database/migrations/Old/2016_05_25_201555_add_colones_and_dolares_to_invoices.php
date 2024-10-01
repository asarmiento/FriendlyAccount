<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColonesAndDolaresToInvoices extends Migration
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
            $table->decimal('colones', 20, 2)->nullable()->after('user_auth_id');
            $table->decimal('dolares', 20, 2)->nullable()->after('colones');
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
            $table->dropColumn('colones');
            $table->dropColumn('dolares');
        });
    }
}
