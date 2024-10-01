<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInventoriesOfAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('inventories', function ($table) {
            $table->dropColumn('amount');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        Schema::table('inventories', function ($table) {
           $table->decimal('amount', 20,8)->after('id');
            $table->timestamps('CURRENT_TIMESTAMP');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function ($table) {
            $table->decimal('amount', 20,2)->change();
        });
    }
}
