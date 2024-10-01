<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_methods')->insert([
            'id' => 1,
            'name' => 'Contado',
            'type' => 'buy'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 2,
            'name' => 'Credito',
            'type' => 'buy'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 3,
            'name' => 'Colones',
            'type' => 'sale'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 4,
            'name' => 'Dólares',
            'type' => 'sale'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 5,
            'name' => 'Tarjeta',
            'type' => 'sale'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 6,
            'name' => 'cocina',
            'type' => 'order'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 7,
            'name' => 'Colones y Dólares',
            'type' => 'sale'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 8,
            'name' => 'Tarjeta y Colones',
            'type' => 'sale'
        ]);

        \DB::table('payment_methods')->insert([
            'id' => 9,
            'name' => 'Tarjeta y Dólares',
            'type' => 'sale'
        ]);
    }
}
