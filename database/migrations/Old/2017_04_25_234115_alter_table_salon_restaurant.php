<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSalonRestaurant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_salons', function($table)  {
            $sql = "ALTER TABLE `table_salons` CHANGE `restaurant` `restaurant` ENUM('no','si','express') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;";
            DB::connection()->getPdo()->exec($sql);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_salons', function($table) {
            $sql = "ALTER TABLE `table_salons` CHANGE `restaurant` `restaurant` ENUM('no','si') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;";
            DB::connection()->getPdo()->exec($sql);
        });
    }
}
