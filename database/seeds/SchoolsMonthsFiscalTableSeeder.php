<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Illuminate\Database\Seeder;
/**
 * Description of SchoolsHasUsersTableSeeder
 *
 * @author Anwar Sarmiento
 */
class SchoolsMonthsFiscalTableSeeder extends Seeder{
    //put your code here
     public function run() {
        \DB::table('schools_months_fiscal')->insert([
            'month_first' => 1,
            'month_end' => 12,
            'school_id' => 1
         ]);
        
        \DB::table('schools_months_fiscal')->insert([
            'month_first' => 1,
            'month_end' => 12,
            'school_id' => 2
         ]);
    }
}
