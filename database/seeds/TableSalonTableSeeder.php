<?php

use Illuminate\Database\Seeder;

class TableSalonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 12; $i++) {
        	$name = 'Mesa '.($i+1);
        	\DB::table('table_salons')
        	->insert([
            	'name' => $name,
            	'token' => md5($name),
             	'school_id' => 2
            ]);
        }
    }
}
