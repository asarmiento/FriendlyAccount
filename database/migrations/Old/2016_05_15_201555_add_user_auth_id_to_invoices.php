<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserAuthIdToInvoices extends Migration
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
            $table->integer('user_auth_id')->unsigned()->index();
            $table->foreign('user_auth_id')->references('id')->on('users')->onDelete('no action');

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
            $table->dropForeign('invoices_user_auth_id_foreign');
            $table->dropColumn('user_auth_id');
        });
    }
}
