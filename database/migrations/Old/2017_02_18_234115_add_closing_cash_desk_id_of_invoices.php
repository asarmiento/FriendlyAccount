<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClosingCashDeskIdOfInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->integer('closed_cash_desk_id')->nullable()->unsigned()->index()->after('court');
            $table->foreign('closed_cash_desk_id')->references('id')->on('closed_cash_desks')->onDelete('cascade');

        });//
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('invoices');
        });//
    }
}
