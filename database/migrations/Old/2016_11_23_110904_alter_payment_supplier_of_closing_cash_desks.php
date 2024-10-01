<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentSupplierOfClosingCashDesks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('closing_cash_desks', function (Blueprint $table) {
            //
            $table->decimal('payment_supplier',20,2)->after('success');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('closing_cash_desks', function (Blueprint $table) {
            $table->dropColumn('payment_supplier');
        });
    }
}
