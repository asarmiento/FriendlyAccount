<?php

use Illuminate\Database\Seeder;

class InvoicesTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('invoices_types')->insert([
            'id' => 1,
            'name' => 'Compra'
        ]);
        \DB::table('invoices_types')->insert([
            'id' => 2,
            'name' => 'Venta'
        ]);
        \DB::table('invoices_types')->insert([
            'id' => 3,
            'name' => 'pedido cocina'
        ]);
    }
}
