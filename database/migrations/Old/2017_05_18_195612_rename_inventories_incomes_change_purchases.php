<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameInventoriesIncomesChangePurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = "RENAME TABLE `inventories_incomes` TO `purchases`;";
            DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = "RENAME TABLE `purchases` TO `inventories_incomes`;";
            DB::connection()->getPdo()->exec($sql);

    }
}
