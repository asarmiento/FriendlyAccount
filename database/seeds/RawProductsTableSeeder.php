<?php

use Illuminate\Database\Seeder;

class RawProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AccountHon\Entities\Restaurant\RawMaterial::class,59)->create();
    }
}
