<?php

use Illuminate\Database\Seeder;

class CashDesksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cash_desks')->insert([
            'name' => 'Caja 1'
        ]);
    }
}
