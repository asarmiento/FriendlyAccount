<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardEmailInInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('card')->after('cash')->nullable();
            $table->string('emails')->after('client')->nullable();
            $table->string('keys')->after('client')->nullable();
            $table->string('consecutive')->after('client')->nullable();
            $table->boolean('fe')->after('client');
        });
        Schema::table('invoice_services', function (Blueprint $table) {
            $table->string('card')->after('customer')->nullable();
            $table->string('email')->after('customer')->nullable();
            $table->string('keys')->after('customer')->nullable();
            $table->string('consecutive')->after('customer')->nullable();
            $table->boolean('fe')->after('customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
