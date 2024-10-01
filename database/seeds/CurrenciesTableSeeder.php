<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $curriences = ['Cinco Colones' => 5,
        			   'Diez Colones' => 10,
        			   'Veinticinco Colones' => 25,
        			   'Cincuenta Colones' => 50,
        			   'Cien Colones' => 100,
        			   'Quinientos Colones' => 500,
        			   'Mil Colones' => 1000,
        			   'Dos Mil Colones' => 2000,
        			   'Cinco Mil Colones' => 5000,
        			   'Diez Mil Colones' => 10000,
        			   'Veinte Mil Colones' => 20000,
        			   'Cincuenta Mil Colones' => 50000,
        			   'Dolares' => 520,
        			   'Tarjetas' => 1
        			   ];
     	foreach ($curriences as $key => $value) {
     		\DB::table('currencies')->insert([
	            'name' => $key,
	            'school_id' => 2,
	            'value' => $value
	        ]);
     	}
    }
}
