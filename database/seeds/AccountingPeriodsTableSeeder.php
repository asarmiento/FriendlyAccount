<?php

use Illuminate\Database\Seeder;

class AccountingPeriodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('accounting_periods')->insert([
            'month' => 1,
            'year' => 2015,
            'school_id' => 1,
            'user_created' => 2,
            'token' => \Hash::make('123456'),
          ]);
        \DB::table('accounting_periods')->insert([
            'month' => 10,
            'year' => 2015,
            'school_id' => 2,
            'user_created' => 2,
            'token' => \Hash::make('1233456'),
        ]);
    }
}
