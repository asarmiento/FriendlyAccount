<?php

use Illuminate\Database\Seeder;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('types')->insert([
            'id' => 1,
            'name' => 'debito',
            'token' => 'dsfgdsfg65d4fgs4er6g4rserg4s6g4sd6g4fe8rgwerg4s'
        ]);
        \DB::table('types')->insert([
            'id' => 2,
            'name' => 'credito',
            'token' => 'dfasdf78yaf8f3ya3cw3ofyaowc3fyaw3fwa3ynfcwi'
        ]);
    }
}
