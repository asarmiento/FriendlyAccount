<?php

use Illuminate\Database\Seeder;

class SupplierTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AccountHon\Entities\Accounting\Supplier::class,10)->create();
    }
}
